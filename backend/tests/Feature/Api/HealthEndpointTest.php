<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_health_endpoint_returns_metal_sync_indicators()
    {
        Cache::put('metal_sync:last_success', now()->subMinutes(15)->toISOString(), now()->addDays(7));
        Cache::put('metal_sync:last_failed', now()->subHours(1)->toISOString(), now()->addDays(7));
        Cache::put('metal_sync:last_duration_ms', 894.2, now()->addDays(7));
        Cache::put('metal_sync:last_rows_updated', 3, now()->addDays(7));
        Cache::put('metal_sync:last_cache_refresh_count', 5, now()->addDays(7));

        Cache::put('metal_prices:XAU:USD:latest', [
            'symbol' => 'XAU',
            'currency' => 'USD',
            'price_per_unit' => 1800.0,
            'unit' => 'ounce',
            'price_date' => now()->toDateString(),
        ], now()->addHours(6));

        Cache::put('metal_prices:XAG:USD:latest', [
            'symbol' => 'XAG',
            'currency' => 'USD',
            'price_per_unit' => 24.0,
            'unit' => 'ounce',
            'price_date' => now()->toDateString(),
        ], now()->addHours(6));

        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'services',
                'system_health',
                'business_metrics',
                'metal_sync',
            ])
            ->assertJsonPath('status', 'healthy');

        $this->assertNotNull($response->json('metal_sync.last_success'));
        $this->assertNotNull($response->json('metal_sync.last_failed'));
        $this->assertNotNull($response->json('metal_sync.consecutive_failures'));
        $this->assertNotNull($response->json('metal_sync.circuit_breaker.status'));
        $this->assertNotNull($response->json('metal_sync.cache_status'));
        $this->assertNotNull($response->json('metal_sync.queue.name'));
        $this->assertNotNull($response->json('metal_sync.latest_cached_prices.XAU'));
        $this->assertNotNull($response->json('metal_sync.latest_cached_prices.XAG'));
    }

    public function test_health_endpoint_exposes_metal_sync_queue_and_lock_metrics()
    {
        Cache::put('metal_sync:last_queue_wait_ms', 22.4, now()->addDays(7));
        Cache::put('metal_sync:last_lock_wait_ms', 35.2, now()->addDays(7));
        Cache::put('metal_sync:last_lock_status', 'acquired', now()->addDays(7));
        Cache::put('metal_sync:last_execution_duration_ms', 540.1, now()->addDays(7));
        Cache::put('metal_sync:last_execution_memory_mb', 28.7, now()->addDays(7));

        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonPath('metal_sync.last_queue_wait_ms', 22.4)
            ->assertJsonPath('metal_sync.last_lock_wait_ms', 35.2)
            ->assertJsonPath('metal_sync.last_lock_status', 'acquired')
            ->assertJsonPath('metal_sync.last_execution_duration_ms', 540.1)
            ->assertJsonPath('metal_sync.last_execution_memory_mb', 28.7);
    }

    public function test_health_endpoint_exposes_prune_telemetry()
    {
        Cache::put('metal_sync:last_prune_run', now()->subHours(1)->toISOString(), now()->addDays(7));
        Cache::put('metal_sync:last_prune_duration_ms', 73.2, now()->addDays(7));
        Cache::put('metal_sync:last_pruned_rows', ['daily_metal_prices' => 7, 'gold_prices' => 1], now()->addDays(7));
        Cache::put('metal_sync:last_prune_status', 'success', now()->addDays(7));

        $response = $this->getJson('/api/health');

        $response->assertStatus(200)
            ->assertJsonPath('metal_sync.last_prune_run', Cache::get('metal_sync:last_prune_run'))
            ->assertJsonPath('metal_sync.last_prune_duration_ms', 73.2)
            ->assertJsonPath('metal_sync.last_pruned_rows.daily_metal_prices', 7)
            ->assertJsonPath('metal_sync.last_prune_status', 'success');
    }
}
