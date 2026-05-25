<?php

namespace Tests\Feature\Api;

use Illuminate\Support\Carbon;

class FakeGoldApiService extends \App\Services\GoldApiService
{
    public $calls = 0;

    // override constructor to avoid parent checks
    public function __construct() {}

    public function fetchRate(string $symbol, string $currency = 'USD') : array
    {
        $this->calls++;

        if ($this->calls < 3) {
            throw new \RuntimeException('Simulated transient error');
        }

        return [
            'price' => $symbol === 'XAU' ? 2000.0 : 25.0,
            'timestamp' => time(),
            'unit' => 'ounce',
        ];
    }

    public function getTimestamp(array $payload): \Illuminate\Support\Carbon {
        return Carbon::createFromTimestamp((int)$payload['timestamp']);
    }

    public function getPricePerOunce(array $payload): float
    {
        return (float)$payload['price'];
    }

    public function getUnit(array $payload): string
    {
        return $payload['unit'] ?? 'ounce';
    }
}

use App\Jobs\SyncGoldApiPricesJob;
use App\Services\GoldApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RetryBehaviorTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_retries_and_eventually_succeeds_without_partial_writes()
    {
        Cache::flush();

        // Do not spy on Log facade to avoid channel mock issues

        // Fake GoldApiService that fails twice then succeeds
        $fake = new \Tests\Feature\Api\FakeGoldApiService();
        $this->app->instance(GoldApiService::class, $fake);

        $job = new SyncGoldApiPricesJob(['XAU', 'XAG'], 'USD');

        // First attempt - should throw and not write DB/cache
        try {
            $job->handle(app(GoldApiService::class), app()->make(\App\Repositories\GoldPriceRepository::class), app()->make(\App\Repositories\DailyMetalPriceRepository::class));
            $this->fail('Expected exception on first attempt');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Simulated transient error', $e->getMessage());
        }

        $this->assertDatabaseCount('daily_metal_prices', 0);
        $this->assertFalse(Cache::has('metal_prices:XAU:USD:latest'));

        // Second attempt - should still fail
        try {
            $job->handle(app(GoldApiService::class), app()->make(\App\Repositories\GoldPriceRepository::class), app()->make(\App\Repositories\DailyMetalPriceRepository::class));
            $this->fail('Expected exception on second attempt');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Simulated transient error', $e->getMessage());
        }

        $this->assertDatabaseCount('daily_metal_prices', 0);
        $this->assertFalse(Cache::has('metal_prices:XAU:USD:latest'));

        // Third attempt - should succeed
        $job->handle(app(GoldApiService::class), app()->make(\App\Repositories\GoldPriceRepository::class), app()->make(\App\Repositories\DailyMetalPriceRepository::class));

        $this->assertDatabaseHas('daily_metal_prices', [
            'metal_symbol' => 'XAU',
            'base_currency' => 'USD',
        ]);

        $this->assertTrue(Cache::has('metal_prices:XAU:USD:latest'));
        $this->assertTrue(Cache::has('metal_prices:XAG:USD:latest'));

        // Monitoring keys should be present
        $this->assertNotNull(Cache::get('metal_sync:last_success'));
        $this->assertNotNull(Cache::get('metal_sync:last_duration_ms'));
    }

    public function test_job_configures_retry_policy()
    {
        $job = new SyncGoldApiPricesJob(['XAU', 'XAG'], 'USD');

        $this->assertSame(3, $job->tries);
        $this->assertSame(300, $job->backoff);
        $this->assertNull($job->queue);
    }

    public function test_circuit_breaker_opens_after_multiple_failures()
    {
        Cache::flush();
        config(['services.metal_api.key' => 'test-key']);
        config(['services.metal_api.base_url' => 'https://www.goldapi.io/api']);
        config(['services.metal_api.circuit_breaker.failure_threshold' => 5]);
        config(['services.metal_api.circuit_breaker.cooldown_minutes' => 15]);

        Http::fakeSequence()
            ->push([], 500)
            ->push([], 500)
            ->push([], 500)
            ->push([], 500)
            ->push([], 500);

        $service = new \App\Services\GoldApiService();

        for ($i = 1; $i <= 5; $i++) {
            try {
                $service->fetchRate('XAU', 'USD');
                $this->fail('Expected failure on attempt ' . $i);
            } catch (\RuntimeException $e) {
                $this->assertStringContainsString('Failed to fetch metal price from GoldAPI', $e->getMessage());
            }
        }

        $circuitState = Cache::get('metal_sync:circuit_breaker');
        $this->assertSame('open', $circuitState['status']);
        $this->assertGreaterThanOrEqual(5, $circuitState['failures']);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('GoldAPI circuit breaker is open.');
        $service->fetchRate('XAU', 'USD');
    }

    public function test_failed_method_records_failure()
    {
        Cache::flush();
        $job = new SyncGoldApiPricesJob(['XAU'], 'USD');
        $job->job = new class {
            public function attempts()
            {
                return 3;
            }
        };

        Log::shouldReceive('channel')->with('api')->twice()->andReturnSelf();
        Log::shouldReceive('error')->once();
        Log::shouldReceive('warning')->andReturnNull()->byDefault();

        $ex = new \RuntimeException('terminal failure');
        $job->failed($ex);

        $this->assertNotNull(Cache::get('metal_sync:last_failed'));
        $this->assertGreaterThanOrEqual(1, Cache::get('metal_sync:failure_count'));
    }
}
