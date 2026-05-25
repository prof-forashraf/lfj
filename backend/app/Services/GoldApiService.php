<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use RuntimeException;

class GoldApiService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.metal_api.key');
        $this->baseUrl = config('services.metal_api.base_url', 'https://www.goldapi.io/api');

        if (empty($this->apiKey)) {
            throw new RuntimeException('GoldAPI API key is not configured. Set METAL_API_KEY in your environment.');
        }
    }

    public function fetchRate(string $symbol, string $currency = 'USD'): array
    {
        $symbol = strtoupper($symbol);
        $currency = strtoupper($currency);
        $url = rtrim($this->baseUrl, '/') . "/{$symbol}/{$currency}";

        $circuitState = $this->getCircuitBreakerState();

        if ($circuitState['status'] === 'open' && now()->lessThan(Carbon::parse($circuitState['cooldown_until']))) {
            $message = 'GoldAPI circuit breaker is open, skipping external request.';
            Log::channel('api')->warning($message, ['circuit_state' => $circuitState]);
            $this->sendAlert('GoldAPI circuit breaker opened', ['circuit_state' => $circuitState]);
            throw new RuntimeException('GoldAPI circuit breaker is open.');
        }

        if ($circuitState['status'] === 'open' && now()->greaterThanOrEqualTo(Carbon::parse($circuitState['cooldown_until']))) {
            $circuitState['status'] = 'half-open';
            $circuitState['last_checked_at'] = now()->toISOString();
            $this->setCircuitBreakerState($circuitState);
        }

        try {
            $start = microtime(true);
            $response = Http::timeout(15)
                ->acceptJson()
                ->withHeaders([
                    'x-access-token' => $this->apiKey,
                    'User-Agent' => 'lfj-metal-sync/1.0',
                ])
                ->get($url);

            $latencyMs = (microtime(true) - $start) * 1000;

            Log::channel('api')->info('GoldAPI request completed', [
                'symbol' => $symbol,
                'currency' => $currency,
                'url' => $url,
                'latency_ms' => round($latencyMs, 2),
                'circuit_state' => $circuitState,
            ]);

            $response->throw();

            $payload = $response->json();

            if (!is_array($payload) || empty($payload['price'])) {
                throw new RuntimeException('GoldAPI returned an unexpected response for ' . $symbol . '/' . $currency);
            }

            $this->resetCircuitBreaker();

            return $payload;
        } catch (RequestException $exception) {
            $this->recordCircuitFailure($exception->getMessage(), $symbol, $currency, $url);

            throw new RuntimeException('Failed to fetch metal price from GoldAPI.', 0, $exception);
        } catch (RuntimeException $exception) {
            $this->recordCircuitFailure($exception->getMessage(), $symbol, $currency, $url);
            throw $exception;
        }
    }

    public function fetchRates(array $symbols, string $currency = 'USD'): array
    {
        $results = [];

        foreach ($symbols as $symbol) {
            $results[$symbol] = $this->fetchRate($symbol, $currency);
        }

        return $results;
    }

    public function getPricePerOunce(array $payload): float
    {
        if (!isset($payload['price'])) {
            throw new RuntimeException('Missing price field returned by GoldAPI.');
        }

        return (float) $payload['price'];
    }

    protected function getCircuitBreakerState(): array
    {
        return Cache::get($this->circuitBreakerKey(), [
            'status' => 'closed',
            'failures' => 0,
            'opened_at' => null,
            'cooldown_until' => null,
            'last_checked_at' => now()->toISOString(),
        ]);
    }

    protected function circuitBreakerKey(): string
    {
        return 'metal_sync:circuit_breaker';
    }

    protected function setCircuitBreakerState(array $state): void
    {
        Cache::put($this->circuitBreakerKey(), $state, now()->addDays(7));
    }

    protected function resetCircuitBreaker(): void
    {
        Cache::put('metal_sync:consecutive_failures', 0, now()->addDays(7));
        $this->setCircuitBreakerState([
            'status' => 'closed',
            'failures' => 0,
            'opened_at' => null,
            'cooldown_until' => null,
            'last_checked_at' => now()->toISOString(),
        ]);
    }

    public function resetCircuitBreakerState(): void
    {
        $this->resetCircuitBreaker();
    }

    public function getCircuitBreakerStatus(): array
    {
        return $this->getCircuitBreakerState();
    }

    protected function recordCircuitFailure(string $reason, string $symbol, string $currency, string $url): void
    {
        $failureCount = Cache::increment('metal_sync:consecutive_failures');
        if ($failureCount === 1) {
            Cache::put('metal_sync:consecutive_failures', 1, now()->addDays(7));
        }

        $threshold = config('services.metal_api.circuit_breaker.failure_threshold', 5);

        if ($failureCount >= $threshold) {
            $cooldown = config('services.metal_api.circuit_breaker.cooldown_minutes', 15);
            $state = [
                'status' => 'open',
                'failures' => $failureCount,
                'opened_at' => now()->toISOString(),
                'cooldown_until' => now()->addMinutes($cooldown)->toISOString(),
                'last_checked_at' => now()->toISOString(),
            ];
            $this->setCircuitBreakerState($state);
            $this->sendAlert('GoldAPI circuit breaker opened', [
                'symbol' => $symbol,
                'currency' => $currency,
                'url' => $url,
                'failure_count' => $failureCount,
                'cooldown_minutes' => $cooldown,
            ]);
        }

        Log::channel('api')->error('GoldAPI request failed', [
            'symbol' => $symbol,
            'currency' => $currency,
            'url' => $url,
            'error' => $reason,
            'consecutive_failures' => $failureCount,
        ]);
    }

    protected function sendAlert(string $title, array $payload = []): void
    {
        try {
            Notification::route('log', 'metal_sync')
                ->notify(new \App\Notifications\MetalSyncAlert($title, $payload));

            $webhookUrl = config('services.metal_sync.alert_webhook');
            if (!empty($webhookUrl)) {
                Http::post($webhookUrl, [
                    'text' => $title,
                    'details' => $payload,
                ]);
            }
        } catch (\Throwable $e) {
            Log::channel('api')->warning('Failed to send metal sync alert', [
                'error' => $e->getMessage(),
                'title' => $title,
            ]);
        }
    }

    public function getUnit(array $payload): string
    {
        return $payload['unit'] ?? 'ounce';
    }

    public function getTimestamp(array $payload): Carbon
    {
        if (!empty($payload['timestamp'])) {
            return Carbon::createFromTimestamp((int) $payload['timestamp'])->timezone(config('app.timezone', 'UTC'));
        }

        if (!empty($payload['date'])) {
            return Carbon::parse($payload['date'])->timezone(config('app.timezone', 'UTC'));
        }

        return Carbon::now(config('app.timezone', 'UTC'));
    }
}
