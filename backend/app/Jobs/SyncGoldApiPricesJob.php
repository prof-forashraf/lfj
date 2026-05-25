<?php

namespace App\Jobs;

use App\Repositories\DailyMetalPriceRepository;
use App\Repositories\GoldPriceRepository;
use App\Services\GoldApiService;
use App\Jobs\Middleware\TrackMetalSyncMetrics;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use Throwable;

class SyncGoldApiPricesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $metals;
    public string $currency;

    public int $tries = 3;
    public int $backoff = 300;

    public function __construct(array $metals = ['XAU', 'XAG'], string $currency = 'USD')
    {
        $this->metals = array_map('strtoupper', $metals);
        $this->currency = strtoupper($currency);
    }

    public function middleware(): array
    {
        return [new TrackMetalSyncMetrics()];
    }

    public function handle(GoldApiService $goldApiService, GoldPriceRepository $goldPriceRepository, DailyMetalPriceRepository $dailyMetalPriceRepository): void
    {
        $start = microtime(true);
        $cacheRefreshCount = 0;
        $rowsUpdated = 0;
        $cacheUpdates = [];

        Cache::put('metal_sync:last_execution_started_at', now()->toISOString(), now()->addDays(7));

        $lockWaitStart = microtime(true);
        $lock = Cache::lock($this->lockKey(), config('services.metal_sync.lock_ttl_seconds', 900));

        try {
            $lock->block(config('services.metal_sync.lock_wait_seconds', 10), function () use ($goldApiService, $goldPriceRepository, $dailyMetalPriceRepository, &$rowsUpdated, &$cacheUpdates) {
                DB::transaction(function () use ($goldApiService, $goldPriceRepository, $dailyMetalPriceRepository, &$rowsUpdated, &$cacheUpdates) {
                    foreach ($this->metals as $symbol) {
                        $payload = $goldApiService->fetchRate($symbol, $this->currency);

                        $timestamp = $goldApiService->getTimestamp($payload);
                        $pricePerOunce = $goldApiService->getPricePerOunce($payload);
                        $unit = $goldApiService->getUnit($payload);
                        $priceDate = $timestamp->toDateString();

                        $dailyMetalPriceRepository->upsert($priceDate, $this->currency, $symbol, $pricePerOunce, $unit);
                        $rowsUpdated++;

                        $payloadData = [
                            'symbol' => $symbol,
                            'currency' => $this->currency,
                            'price_per_unit' => $pricePerOunce,
                            'unit' => $unit,
                            'price_date' => $priceDate,
                            'timestamp' => $timestamp->toISOString(),
                        ];

                        $cacheUpdates[] = [
                            'key' => "metal_prices:{$symbol}:{$this->currency}:latest",
                            'value' => $payloadData,
                            'ttl' => now()->addHours(6),
                        ];

                        if ($symbol === 'XAU') {
                            $goldPriceRepository->upsert([
                                'date_recorded' => $priceDate,
                                'timestamp_recorded' => $timestamp->toDateTimeString(),
                                'currency_code' => $this->currency,
                                'price_per_ounce' => $pricePerOunce,
                                'price_per_gram_24k' => $pricePerOunce / 31.1035,
                                'price_per_gram_22k' => ($pricePerOunce / 31.1035) * (22 / 24),
                                'price_per_gram_18k' => ($pricePerOunce / 31.1035) * (18 / 24),
                                'price_per_gram_14k' => ($pricePerOunce / 31.1035) * (14 / 24),
                                'price_per_gram_10k' => ($pricePerOunce / 31.1035) * (10 / 24),
                                'market_open' => $payload['open'] ?? null,
                                'market_high' => $payload['high'] ?? null,
                                'market_low' => $payload['low'] ?? null,
                                'market_close' => $payload['previous_close'] ?? $payload['price'] ?? null,
                                'volume' => $payload['volume'] ?? null,
                                'source' => 'GoldAPI',
                                'is_active' => true,
                            ]);
                            $rowsUpdated++;
                        }
                    }
                });
            });

            $lockWaitMs = round((microtime(true) - $lockWaitStart) * 1000, 2);
            Cache::put('metal_sync:last_lock_wait_ms', $lockWaitMs, now()->addDays(7));
            Cache::put('metal_sync:last_lock_status', 'acquired', now()->addDays(7));

            foreach ($cacheUpdates as $cacheUpdate) {
                Cache::put($cacheUpdate['key'], $cacheUpdate['value'], $cacheUpdate['ttl']);
                $cacheRefreshCount++;
            }

            $durationMs = round((microtime(true) - $start) * 1000, 2);

            Cache::put('metal_sync:last_success', now()->toISOString(), now()->addDays(7));
            Cache::put('metal_sync:last_duration_ms', $durationMs, now()->addDays(7));
            Cache::put('metal_sync:last_execution_duration_ms', $durationMs, now()->addDays(7));
            Cache::put('metal_sync:last_execution_memory_mb', round(memory_get_peak_usage(true) / 1024 / 1024, 2), now()->addDays(7));
            Cache::put('metal_sync:last_rows_updated', $rowsUpdated, now()->addDays(7));
            Cache::put('metal_sync:last_cache_refresh_count', $cacheRefreshCount, now()->addDays(7));
            Cache::put('metal_sync:consecutive_failures', 0, now()->addDays(7));

            Log::channel('api')->info('Metal price sync completed', [
                'metals' => $this->metals,
                'currency' => $this->currency,
                'synced_at' => Carbon::now()->toIsoString(),
                'duration_ms' => $durationMs,
                'rows_updated' => $rowsUpdated,
                'cache_refresh_count' => $cacheRefreshCount,
                'lock_wait_ms' => $lockWaitMs,
            ]);
        } catch (LockTimeoutException $e) {
            $lockWaitMs = round((microtime(true) - $lockWaitStart) * 1000, 2);
            Cache::put('metal_sync:last_lock_wait_ms', $lockWaitMs, now()->addDays(7));
            Cache::put('metal_sync:last_lock_status', 'timeout', now()->addDays(7));

            Log::channel('api')->warning('Metal price sync lock could not be acquired.', [
                'queue' => config('services.metal_sync.queue_name', 'metal-sync'),
                'lock_wait_ms' => $lockWaitMs,
            ]);

            return;
        } catch (\Throwable $e) {
            Cache::put('metal_sync:last_failed', now()->toISOString(), now()->addDays(7));
            Cache::increment('metal_sync:failure_count');
            Cache::increment('metal_sync:consecutive_failures');

            $this->sendAlert('Metal price sync failed', [
                'error' => $e->getMessage(),
                'metals' => $this->metals,
                'currency' => $this->currency,
            ]);

            Log::channel('api')->error('Metal price sync failed during handle', [
                'error' => $e->getMessage(),
                'metals' => $this->metals,
                'currency' => $this->currency,
            ]);

            throw $e;
        }
    }

    protected function lockKey(): string
    {
        return 'metal_sync:lock';
    }

    public function failed(Throwable $exception): void
    {
        Cache::put('metal_sync:last_failed', now()->toISOString(), now()->addDays(7));
        Cache::increment('metal_sync:failure_count');
        Cache::increment('metal_sync:consecutive_failures');

        $attempts = null;
        try {
            $attempts = $this->job?->attempts() ?? null;
        } catch (\Throwable $_) {
            // ignore
        }

        $this->sendAlert('Metal price sync job failed', [
            'metals' => $this->metals,
            'currency' => $this->currency,
            'error' => $exception->getMessage(),
            'attempts' => $attempts,
        ]);

        Log::channel('api')->error('Metal price sync job failed', [
            'metals' => $this->metals,
            'currency' => $this->currency,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'attempts' => $attempts,
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
}
