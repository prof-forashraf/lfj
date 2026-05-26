<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialPriceControllerSilverTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_rate_from_cache_silver_latest()
    {
        Cache::flush();

        // Warm standardized cache key as the sync job would
        Cache::put('metal_prices:XAG:USD:latest', [
            'symbol' => 'XAG',
            'currency' => 'USD',
            'price_per_unit' => 25.0,
            'price_date' => now()->toDateString(),
        ], now()->addHours(6));

        $response = $this->getJson('/api/tools/silver-price/USD');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'rates' => ['XAG']]);

        $this->assertEquals(1 / 25.0, $response->json('rates.XAG'));
    }

    public function test_falls_back_to_daily_metal_prices_for_silver()
    {
        Cache::flush();

        // Insert a daily_metal_prices record (price_per_unit is per ounce)
        DB::table('daily_metal_prices')->updateOrInsert([
            'price_date' => now()->toDateString(),
            'base_currency' => 'USD',
            'metal_symbol' => 'XAG',
        ], [
            'price_per_unit' => 26.0,
            'unit' => 'ounce',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/tools/silver-price/USD');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'rates' => ['XAG']]);

        $this->assertEquals(1 / 26.0, $response->json('rates.XAG'));

        // Cache should be warmed
        $this->assertTrue(Cache::has('metal_prices:XAG:USD:latest'));
    }

    public function test_returns_404_when_no_silver_data()
    {
        Cache::flush();

        // ensure seeded data does not interfere
        DB::table('daily_metal_prices')
            ->where('price_date', now()->toDateString())
            ->where('base_currency', 'USD')
            ->where('metal_symbol', 'XAG')
            ->delete();

        DB::table('gold_prices')
            ->where('currency_code', 'USD')
            ->delete();

        $response = $this->getJson('/api/tools/silver-price/USD');

        $response->assertStatus(404)
            ->assertJson(['error' => 'No synchronized silver price data available.']);
    }
}
