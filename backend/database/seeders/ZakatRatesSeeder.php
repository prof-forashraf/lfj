<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZakatRatesSeeder extends Seeder
{
    public function run(): void
    {
        $today = now();
        $currentYear = $today->year;

        $zakatRates = [
            [
                'calculation_year' => $currentYear - 2,
                'nisab_gold_grams' => 87.480,
                'nisab_silver_grams' => 612.360,
                'zakat_percentage' => 2.5,
                'gold_price_per_gram' => 60.50,
                'silver_price_per_gram' => 0.75,
                'currency_code' => 'USD',
                'lunar_year_days' => 354,
                'holding_period_days' => 354,
                'is_active' => 0,
                'date_effective' => $today->copy()->subYears(2)->toDateString(),
            ],
            [
                'calculation_year' => $currentYear - 1,
                'nisab_gold_grams' => 87.480,
                'nisab_silver_grams' => 612.360,
                'zakat_percentage' => 2.5,
                'gold_price_per_gram' => 62.00,
                'silver_price_per_gram' => 0.78,
                'currency_code' => 'USD',
                'lunar_year_days' => 354,
                'holding_period_days' => 354,
                'is_active' => 0,
                'date_effective' => $today->copy()->subYears(1)->toDateString(),
            ],
            [
                'calculation_year' => $currentYear,
                'nisab_gold_grams' => 87.480,
                'nisab_silver_grams' => 612.360,
                'zakat_percentage' => 2.5,
                'gold_price_per_gram' => 65.90,
                'silver_price_per_gram' => 0.85,
                'currency_code' => 'USD',
                'lunar_year_days' => 354,
                'holding_period_days' => 354,
                'is_active' => 1,
                'date_effective' => $today->toDateString(),
            ],
        ];

        foreach ($zakatRates as $rate) {
            DB::table('zakat_rates')->insertOrIgnore($rate);
        }
    }
}
