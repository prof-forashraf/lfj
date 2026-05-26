<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MaterialPriceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_rate_from_cache_gold_prices_latest()
    {
        Cache::flush();

        // Warm standardized cache key as the sync job would
        Cache::put('metal_prices:XAU:USD:latest', [
            'symbol' => 'XAU',
            'currency' => 'USD',
            'price_per_unit' => 2000.0,
            'price_date' => now()->toDateString(),
        ], now()->addHours(6));

        $response = $this->getJson('/api/tools/gold-price/USD');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'rates' => ['XAU']]);

        $this->assertEquals(1 / 2000.0, $response->json('rates.XAU'));
    }

    public function test_falls_back_to_daily_metal_prices()
    {
        Cache::flush();

        // Insert a daily_metal_prices record (price_per_unit is per ounce)
        DB::table('daily_metal_prices')->updateOrInsert([
            'price_date' => now()->toDateString(),
            'base_currency' => 'USD',
            'metal_symbol' => 'XAU',
        ], [
            'price_per_unit' => 1800.0,
            'unit' => 'ounce',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/tools/gold-price/USD');

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'rates' => ['XAU']]);

        $this->assertEquals(1 / 1800.0, $response->json('rates.XAU'));

        // Cache should be warmed (standardized key)
        $this->assertTrue(Cache::has('metal_prices:XAU:USD:latest'));
    }

    public function test_returns_404_when_no_data()
    {
        Cache::flush();

        // Ensure any seeded price records for the current date/currency/metal are removed
        DB::table('daily_metal_prices')
            ->where('price_date', now()->toDateString())
            ->where('base_currency', 'USD')
            ->where('metal_symbol', 'XAU')
            ->delete();

        // also ensure any gold_prices entries for the currency are removed
        DB::table('gold_prices')
            ->where('currency_code', 'USD')
            ->delete();

        $response = $this->getJson('/api/tools/gold-price/USD');

        $response->assertStatus(404)
            ->assertJson(['error' => 'No synchronized gold price data available.']);
    }
}
