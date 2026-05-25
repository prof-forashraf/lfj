<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoldPricesSeeder extends Seeder
{
    public function run(): void
    {
        $today = now()->startOfDay();
        
        $goldPrices = [
            [
                'date_recorded' => $today->copy()->subDays(5)->toDateString(),
                'timestamp_recorded' => $today->copy()->subDays(5),
                'currency_code' => 'USD',
                'price_per_ounce' => 2050.50,
                'price_per_gram_24k' => 65.90,
                'price_per_gram_22k' => 60.45,
                'price_per_gram_18k' => 49.43,
                'price_per_gram_14k' => 38.28,
                'price_per_gram_10k' => 27.30,
                'market_open' => 2048.00,
                'market_high' => 2065.00,
                'market_low' => 2040.00,
                'market_close' => 2050.50,
                'volume' => 5000000,
                'source' => 'Live Prices API',
                'is_active' => 1,
            ],
            [
                'date_recorded' => $today->copy()->subDays(4)->toDateString(),
                'timestamp_recorded' => $today->copy()->subDays(4),
                'currency_code' => 'USD',
                'price_per_ounce' => 2055.75,
                'price_per_gram_24k' => 66.08,
                'price_per_gram_22k' => 60.60,
                'price_per_gram_18k' => 49.55,
                'price_per_gram_14k' => 38.37,
                'price_per_gram_10k' => 27.37,
                'market_open' => 2050.50,
                'market_high' => 2070.00,
                'market_low' => 2045.00,
                'market_close' => 2055.75,
                'volume' => 5200000,
                'source' => 'Live Prices API',
                'is_active' => 1,
            ],
            [
                'date_recorded' => $today->copy()->subDays(3)->toDateString(),
                'timestamp_recorded' => $today->copy()->subDays(3),
                'currency_code' => 'USD',
                'price_per_ounce' => 2045.25,
                'price_per_gram_24k' => 65.72,
                'price_per_gram_22k' => 60.27,
                'price_per_gram_18k' => 49.28,
                'price_per_gram_14k' => 38.17,
                'price_per_gram_10k' => 27.22,
                'market_open' => 2055.75,
                'market_high' => 2060.00,
                'market_low' => 2035.00,
                'market_close' => 2045.25,
                'volume' => 4800000,
                'source' => 'Live Prices API',
                'is_active' => 1,
            ],
            [
                'date_recorded' => $today->copy()->subDays(2)->toDateString(),
                'timestamp_recorded' => $today->copy()->subDays(2),
                'currency_code' => 'USD',
                'price_per_ounce' => 2060.00,
                'price_per_gram_24k' => 66.20,
                'price_per_gram_22k' => 60.72,
                'price_per_gram_18k' => 49.64,
                'price_per_gram_14k' => 38.45,
                'price_per_gram_10k' => 27.43,
                'market_open' => 2045.25,
                'market_high' => 2075.00,
                'market_low' => 2040.00,
                'market_close' => 2060.00,
                'volume' => 5300000,
                'source' => 'Live Prices API',
                'is_active' => 1,
            ],
            [
                'date_recorded' => $today->toDateString(),
                'timestamp_recorded' => $today,
                'currency_code' => 'USD',
                'price_per_ounce' => 2065.50,
                'price_per_gram_24k' => 66.38,
                'price_per_gram_22k' => 60.89,
                'price_per_gram_18k' => 49.79,
                'price_per_gram_14k' => 38.56,
                'price_per_gram_10k' => 27.51,
                'market_open' => 2060.00,
                'market_high' => 2070.00,
                'market_low' => 2055.00,
                'market_close' => 2065.50,
                'volume' => 5100000,
                'source' => 'Live Prices API',
                'is_active' => 1,
            ],
        ];

        foreach ($goldPrices as $price) {
            DB::table('gold_prices')->insertOrIgnore($price);
        }
    }
}
