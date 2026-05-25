<?php

use App\Models\DailyMetalPrice;
use App\Models\GoldPrice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('metal sync artisan command creates gold and daily metal price records', function () {
    Config::set('services.metal_api.key', 'test-key');
    Config::set('services.metal_api.base_url', 'https://www.goldapi.io/api');

    Http::fake([
        'https://www.goldapi.io/api/XAU/USD' => Http::response([
            'price' => 2035.25,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
            'open' => 2030.00,
            'high' => 2040.00,
            'low' => 2025.00,
            'previous_close' => 2028.75,
            'volume' => 1200,
        ], 200),
        'https://www.goldapi.io/api/XAG/USD' => Http::response([
            'price' => 24.35,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
        ], 200),
    ]);

    $this->artisan('metal:sync-prices --sync')
        ->assertExitCode(0);

    $this->assertDatabaseHas('gold_prices', [
        'currency_code' => 'USD',
        'price_per_ounce' => 2035.25,
    ]);

    $this->assertDatabaseHas('daily_metal_prices', [
        'metal_symbol' => 'XAU',
        'base_currency' => 'USD',
        'price_per_unit' => 2035.25,
    ]);

    $this->assertDatabaseHas('daily_metal_prices', [
        'metal_symbol' => 'XAG',
        'base_currency' => 'USD',
        'price_per_unit' => 24.35,
    ]);

    // Check standardized cache keys written
    $this->assertTrue(Cache::has('metal_prices:XAU:USD:latest'));
    $this->assertTrue(Cache::has('metal_prices:XAG:USD:latest'));

    // Ensure cache values match DB rows
    $xauCache = Cache::get('metal_prices:XAU:USD:latest');
    $xauDb = DB::table('daily_metal_prices')->where('metal_symbol', 'XAU')->where('base_currency', 'USD')->first();
    expect((float) $xauCache['price_per_unit'])->toBe((float) $xauDb->price_per_unit);

    Http::assertSentCount(2);
});

test('monitoring sync-metal-prices endpoint requires sanctum authentication', function () {
    $response = $this->postJson('/api/monitoring/sync-metal-prices');
    $response->assertStatus(401);
});

test('authenticated monitoring endpoints queue sync and return latest prices', function () {
    Config::set('services.metal_api.key', 'test-key');
    Config::set('services.metal_api.base_url', 'https://www.goldapi.io/api');

    Http::fake([
        'https://www.goldapi.io/api/XAU/USD' => Http::response([
            'price' => 2032.75,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
            'open' => 2030.00,
            'high' => 2040.00,
            'low' => 2025.00,
            'previous_close' => 2028.75,
            'volume' => 1200,
        ], 200),
        'https://www.goldapi.io/api/XAG/USD' => Http::response([
            'price' => 24.47,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
        ], 200),
    ]);

    $user = User::factory()->create();
    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson('/api/monitoring/sync-metal-prices', [
        'symbols' => ['XAU', 'XAG'],
        'currency' => 'USD',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'status' => 'queued',
            'currency' => 'USD',
        ]);

    $this->assertDatabaseHas('daily_metal_prices', [
        'metal_symbol' => 'XAU',
        'base_currency' => 'USD',
    ]);
    $this->assertDatabaseHas('daily_metal_prices', [
        'metal_symbol' => 'XAG',
        'base_currency' => 'USD',
    ]);

    $response = $this->getJson('/api/monitoring/latest-metal-prices?symbols[]=XAU&symbols[]=XAG&currency=USD');
    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'currency',
            'prices' => [
                '*' => [
                    'metal_symbol',
                    'base_currency',
                    'price_date',
                    'price_per_unit',
                    'unit',
                ],
            ],
        ]);

    expect($response->json('prices'))->toHaveCount(2);
    Http::assertSentCount(2);
});

test('sync job refreshes stale cache values', function () {
    Config::set('services.metal_api.key', 'test-key');
    Config::set('services.metal_api.base_url', 'https://www.goldapi.io/api');

    // Pre-warm cache with stale values
    Cache::put('metal_prices:XAU:USD:latest', [
        'symbol' => 'XAU',
        'currency' => 'USD',
        'price_per_unit' => 100.0, // stale
        'unit' => 'ounce',
        'price_date' => now()->subDays(2)->toDateString(),
    ], now()->addHours(6));

    Http::fake([
        'https://www.goldapi.io/api/XAU/USD' => Http::response([
            'price' => 2100.5,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
        ], 200),
        'https://www.goldapi.io/api/XAG/USD' => Http::response([
            'price' => 25.0,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
        ], 200),
    ]);

    $this->artisan('metal:sync-prices --sync')->assertExitCode(0);

    // Cache should be updated to new value
    $cached = Cache::get('metal_prices:XAU:USD:latest');
    expect($cached['price_per_unit'])->toBe(2100.5);
});

test('sync job rolls back on partial failure', function () {
    Config::set('services.metal_api.key', 'test-key');
    Config::set('services.metal_api.base_url', 'https://www.goldapi.io/api');

    // XAU will succeed, XAG will fail (500)
    Http::fake([
        'https://www.goldapi.io/api/XAU/USD' => Http::response([
            'price' => 2005.0,
            'timestamp' => 1710000000,
            'unit' => 'ounce',
        ], 200),
        'https://www.goldapi.io/api/XAG/USD' => Http::response([], 500),
    ]);

    // Run the command; it may throw because one API failed — swallow exception
    try {
        $this->artisan('metal:sync-prices --sync')->run();
    } catch (\Exception $e) {
        // expected: upstream HTTP failure
    }

    // Ensure no daily_metal_prices were inserted (transaction rollback)
    $this->assertDatabaseMissing('daily_metal_prices', [
        'metal_symbol' => 'XAU',
        'base_currency' => 'USD',
    ]);
});
