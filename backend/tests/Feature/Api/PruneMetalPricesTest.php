<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PruneMetalPricesTest extends TestCase
{
    use RefreshDatabase;

    public function test_prune_command_deletes_only_records_older_than_retention_and_preserves_latest_snapshot()
    {
        Cache::flush();
        config(['services.metal_sync.retention.daily_metal_prices_days' => 30]);
        config(['services.metal_sync.retention.gold_prices_days' => 15]);

        $oldDailyDate = now()->subDays(40)->toDateString();
        $latestDailyDate = now()->subDays(35)->toDateString();
        $recentDailyDate = now()->subDays(10)->toDateString();

        DB::table('daily_metal_prices')->insert([
            ['price_date' => $oldDailyDate, 'base_currency' => 'USD', 'metal_symbol' => 'XAU', 'price_per_unit' => 1700.00, 'unit' => 'ounce', 'created_at' => now(), 'updated_at' => now()],
            ['price_date' => $latestDailyDate, 'base_currency' => 'USD', 'metal_symbol' => 'XAU', 'price_per_unit' => 1750.00, 'unit' => 'ounce', 'created_at' => now(), 'updated_at' => now()],
            ['price_date' => $recentDailyDate, 'base_currency' => 'USD', 'metal_symbol' => 'XAG', 'price_per_unit' => 24.00, 'unit' => 'ounce', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $oldGoldDate = now()->subDays(40)->toDateString();
        $latestGoldDate = now()->subDays(35)->toDateString();
        $recentGoldDate = now()->subDays(5)->toDateString();

        DB::table('gold_prices')->insert([
            ['date_recorded' => $oldGoldDate, 'timestamp_recorded' => now()->subDays(40), 'currency_code' => 'USD', 'price_per_ounce' => 1700.00, 'price_per_gram_24k' => 54.629, 'price_per_gram_22k' => 50.085, 'price_per_gram_18k' => 40.955, 'price_per_gram_14k' => 31.746, 'price_per_gram_10k' => 22.762, 'market_open' => null, 'market_high' => null, 'market_low' => null, 'market_close' => null, 'volume' => null, 'source' => 'GoldAPI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['date_recorded' => $latestGoldDate, 'timestamp_recorded' => now()->subDays(35), 'currency_code' => 'USD', 'price_per_ounce' => 1750.00, 'price_per_gram_24k' => 56.215, 'price_per_gram_22k' => 51.513, 'price_per_gram_18k' => 42.206, 'price_per_gram_14k' => 32.714, 'price_per_gram_10k' => 23.451, 'market_open' => null, 'market_high' => null, 'market_low' => null, 'market_close' => null, 'volume' => null, 'source' => 'GoldAPI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['date_recorded' => $recentGoldDate, 'timestamp_recorded' => now()->subDays(5), 'currency_code' => 'EUR', 'price_per_ounce' => 1800.00, 'price_per_gram_24k' => 57.853, 'price_per_gram_22k' => 53.047, 'price_per_gram_18k' => 43.205, 'price_per_gram_14k' => 33.254, 'price_per_gram_10k' => 23.901, 'market_open' => null, 'market_high' => null, 'market_low' => null, 'market_close' => null, 'volume' => null, 'source' => 'GoldAPI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $exitCode = Artisan::call('metal:prune-prices');

        $this->assertSame(0, $exitCode);

        $this->assertDatabaseMissing('daily_metal_prices', ['price_date' => $oldDailyDate, 'metal_symbol' => 'XAU']);
        $this->assertDatabaseHas('daily_metal_prices', ['price_date' => $latestDailyDate, 'metal_symbol' => 'XAU']);
        $this->assertDatabaseHas('daily_metal_prices', ['price_date' => $recentDailyDate, 'metal_symbol' => 'XAG']);

        $this->assertDatabaseMissing('gold_prices', ['date_recorded' => $oldGoldDate, 'currency_code' => 'USD']);
        $this->assertDatabaseHas('gold_prices', ['date_recorded' => $latestGoldDate, 'currency_code' => 'USD']);
        $this->assertDatabaseHas('gold_prices', ['date_recorded' => $recentGoldDate, 'currency_code' => 'EUR']);
    }

    public function test_prune_command_dry_run_does_not_delete_any_records()
    {
        Cache::flush();
        config(['services.metal_sync.retention.daily_metal_prices_days' => 30]);
        config(['services.metal_sync.retention.gold_prices_days' => 15]);

        $dailyDate = now()->subDays(40)->toDateString();
        $goldDate = now()->subDays(40)->toDateString();

        DB::table('daily_metal_prices')->insert([
            ['price_date' => $dailyDate, 'base_currency' => 'USD', 'metal_symbol' => 'XAU', 'price_per_unit' => 1700.00, 'unit' => 'ounce', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('gold_prices')->insert([
            ['date_recorded' => $goldDate, 'timestamp_recorded' => now()->subDays(40), 'currency_code' => 'USD', 'price_per_ounce' => 1700.00, 'price_per_gram_24k' => 54.629, 'price_per_gram_22k' => 50.085, 'price_per_gram_18k' => 40.955, 'price_per_gram_14k' => 31.746, 'price_per_gram_10k' => 22.762, 'market_open' => null, 'market_high' => null, 'market_low' => null, 'market_close' => null, 'volume' => null, 'source' => 'GoldAPI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $exitCode = Artisan::call('metal:prune-prices --dry-run');

        $this->assertSame(0, $exitCode);
        $this->assertDatabaseHas('daily_metal_prices', ['price_date' => $dailyDate, 'metal_symbol' => 'XAU']);
        $this->assertDatabaseHas('gold_prices', ['date_recorded' => $goldDate, 'currency_code' => 'USD']);
        $this->assertNull(Cache::get('metal_sync:last_prune_status'));
    }

    public function test_prune_command_updates_telemetry_on_success()
    {
        Cache::flush();
        config(['services.metal_sync.retention.daily_metal_prices_days' => 30]);
        config(['services.metal_sync.retention.gold_prices_days' => 15]);

        DB::table('daily_metal_prices')->insert([
            ['price_date' => now()->subDays(40)->toDateString(), 'base_currency' => 'USD', 'metal_symbol' => 'XAU', 'price_per_unit' => 1700.00, 'unit' => 'ounce', 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('gold_prices')->insert([
            ['date_recorded' => now()->subDays(40)->toDateString(), 'timestamp_recorded' => now()->subDays(40), 'currency_code' => 'USD', 'price_per_ounce' => 1700.00, 'price_per_gram_24k' => 54.629, 'price_per_gram_22k' => 50.085, 'price_per_gram_18k' => 40.955, 'price_per_gram_14k' => 31.746, 'price_per_gram_10k' => 22.762, 'market_open' => null, 'market_high' => null, 'market_low' => null, 'market_close' => null, 'volume' => null, 'source' => 'GoldAPI', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        Artisan::call('metal:prune-prices');

        $this->assertNotNull(Cache::get('metal_sync:last_prune_run'));
        $this->assertNotNull(Cache::get('metal_sync:last_prune_duration_ms'));
        $this->assertSame('success', Cache::get('metal_sync:last_prune_status'));
        $this->assertEquals([
            'daily_metal_prices' => 0,
            'gold_prices' => 0,
        ], Cache::get('metal_sync:last_pruned_rows'));
    }
}
