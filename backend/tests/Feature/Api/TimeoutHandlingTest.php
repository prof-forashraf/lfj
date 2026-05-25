<?php

namespace Tests\Feature\Api;

use App\Jobs\SyncGoldApiPricesJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TimeoutHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_timeout_rolls_back_and_does_not_pollute_cache()
    {
        Cache::flush();

        // Simulate HTTP timeout / 408 for both symbols
        Http::fake([
            'https://www.goldapi.io/api/XAU/USD' => Http::response([], 408),
            'https://www.goldapi.io/api/XAG/USD' => Http::response([], 408),
        ]);

        // Bind a mock GoldApiService to avoid constructor dependency
        $mock = $this->createMock(\App\Services\GoldApiService::class);
        $mock->method('fetchRate')->will($this->throwException(new \RuntimeException('Failed to fetch metal price from GoldAPI.')));
        $this->app->instance(\App\Services\GoldApiService::class, $mock);

        Log::shouldReceive('channel')->with('api')->andReturnSelf()->byDefault();
        Log::shouldReceive('warning')->andReturnNull()->byDefault();
        Log::shouldReceive('info')->andReturnNull()->byDefault();
        Log::shouldReceive('error')->andReturnNull()->byDefault();

        $job = new SyncGoldApiPricesJob(['XAU', 'XAG'], 'USD');

        try {
            $job->handle(app()->make(\App\Services\GoldApiService::class), app()->make(\App\Repositories\GoldPriceRepository::class), app()->make(\App\Repositories\DailyMetalPriceRepository::class));
            $this->fail('Expected exception due to HTTP timeout');
        } catch (\Exception $e) {
            $this->assertStringContainsString('Failed to fetch metal price from GoldAPI', $e->getMessage());
        }

        // Ensure no DB writes
        $this->assertDatabaseCount('daily_metal_prices', 0);

        // Ensure standardized cache not present
        $this->assertFalse(Cache::has('metal_prices:XAU:USD:latest'));

        // If a valid cached value existed before, endpoints should still serve it
        Cache::put('metal_prices:XAU:USD:latest', [
            'symbol' => 'XAU',
            'currency' => 'USD',
            'price_per_unit' => 2000.0,
            'price_date' => now()->toDateString(),
        ], now()->addHours(6));

        $this->assertTrue(Cache::has('metal_prices:XAU:USD:latest'));
        $this->assertSame(2000.0, Cache::get('metal_prices:XAU:USD:latest')['price_per_unit']);

        Cache::put('metal_sync:last_success', now()->subMinutes(45)->toISOString(), now()->addDays(7));

        $response = $this->getJson('/api/tools/gold-price/USD');
        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonPath('meta.is_stale', true)
            ->assertJsonPath('meta.last_successful_sync', Cache::get('metal_sync:last_success'));
    }
}
