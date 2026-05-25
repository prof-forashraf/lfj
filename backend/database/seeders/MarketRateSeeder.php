<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metals = ['gold', 'silver', 'platinum', 'palladium'];
        $basePrices = [
            'gold' => 2000,
            'silver' => 25,
            'platinum' => 900,
            'palladium' => 1500,
        ];

        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i);
            foreach ($metals as $metal) {
                \App\Models\MarketRate::create([
                    'metal_type' => $metal,
                    'price' => $basePrices[$metal] + rand(-50, 50), // Add some variation
                    'currency' => 'USD',
                    'rate_date' => $date,
                    'updated_by' => 1, // Assuming admin user ID 1
                ]);
            }
        }
    }
}
