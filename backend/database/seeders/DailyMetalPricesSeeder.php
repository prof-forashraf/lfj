<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DailyMetalPricesSeeder extends Seeder
{
    public function run(): void
    {
        $metals = [
            ['symbol' => 'XAU', 'name' => 'Gold'],
            ['symbol' => 'XAG', 'name' => 'Silver'],
            ['symbol' => 'XPT', 'name' => 'Platinum'],
            ['symbol' => 'XPD', 'name' => 'Palladium'],
        ];

        $today = now()->startOfDay();

        foreach ($metals as $metal) {
            for ($i = 0; $i < 7; $i++) {
                $date = $today->copy()->subDays($i);
                $priceRange = $this->getPriceRange($metal['symbol']);
                
                DB::table('daily_metal_prices')->insertOrIgnore([
                    'price_date' => $date->toDateString(),
                    'base_currency' => 'USD',
                    'metal_symbol' => $metal['symbol'],
                    'price_per_unit' => $priceRange['base'] + (rand(-100, 100) / 100),
                    'unit' => $metal['symbol'] === 'XAU' ? 'per ounce' : 'per ounce',
                ]);
            }
        }
    }

    private function getPriceRange($symbol): array
    {
        $ranges = [
            'XAU' => ['base' => 2065.50, 'min' => 2000, 'max' => 2100],
            'XAG' => ['base' => 28.75, 'min' => 25, 'max' => 35],
            'XPT' => ['base' => 1050.00, 'min' => 900, 'max' => 1200],
            'XPD' => ['base' => 980.00, 'min' => 850, 'max' => 1100],
        ];

        return $ranges[$symbol] ?? $ranges['XAU'];
    }
}
