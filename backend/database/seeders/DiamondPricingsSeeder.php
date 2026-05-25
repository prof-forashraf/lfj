<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiamondPricingsSeeder extends Seeder
{
    public function run(): void
    {
        $diamondPricings = [
            // Excellent Cut, Premium Colors
            ['carat_range_min' => 0.50, 'carat_range_max' => 0.99, 'cut_grade' => 'Excellent', 'color_grade' => 'D', 'clarity_grade' => 'FL', 'base_price_per_carat' => 12000, 'price_multiplier' => 1.35, 'shape' => 'Round', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 1.00, 'carat_range_max' => 1.99, 'cut_grade' => 'Excellent', 'color_grade' => 'D', 'clarity_grade' => 'FL', 'base_price_per_carat' => 13500, 'price_multiplier' => 1.40, 'shape' => 'Round', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 2.00, 'carat_range_max' => 2.99, 'cut_grade' => 'Excellent', 'color_grade' => 'D', 'clarity_grade' => 'FL', 'base_price_per_carat' => 15000, 'price_multiplier' => 1.45, 'shape' => 'Round', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 3.00, 'carat_range_max' => 4.99, 'cut_grade' => 'Excellent', 'color_grade' => 'D', 'clarity_grade' => 'FL', 'base_price_per_carat' => 16500, 'price_multiplier' => 1.50, 'shape' => 'Round', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 5.00, 'carat_range_max' => 9.99, 'cut_grade' => 'Excellent', 'color_grade' => 'D', 'clarity_grade' => 'FL', 'base_price_per_carat' => 18000, 'price_multiplier' => 1.55, 'shape' => 'Round', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            
            // Very Good Cut, Near Colorless
            ['carat_range_min' => 0.50, 'carat_range_max' => 0.99, 'cut_grade' => 'Very Good', 'color_grade' => 'G', 'clarity_grade' => 'VS1', 'base_price_per_carat' => 6500, 'price_multiplier' => 1.10, 'shape' => 'Round', 'fluorescence' => 'Faint', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 1.00, 'carat_range_max' => 1.99, 'cut_grade' => 'Very Good', 'color_grade' => 'G', 'clarity_grade' => 'VS1', 'base_price_per_carat' => 7200, 'price_multiplier' => 1.15, 'shape' => 'Round', 'fluorescence' => 'Faint', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 2.00, 'carat_range_max' => 2.99, 'cut_grade' => 'Very Good', 'color_grade' => 'G', 'clarity_grade' => 'VS1', 'base_price_per_carat' => 8000, 'price_multiplier' => 1.20, 'shape' => 'Round', 'fluorescence' => 'Faint', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            
            // Good Cut, Medium Colors
            ['carat_range_min' => 0.50, 'carat_range_max' => 0.99, 'cut_grade' => 'Good', 'color_grade' => 'J', 'clarity_grade' => 'SI2', 'base_price_per_carat' => 3500, 'price_multiplier' => 0.95, 'shape' => 'Round', 'fluorescence' => 'Medium', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 1.00, 'carat_range_max' => 1.99, 'cut_grade' => 'Good', 'color_grade' => 'J', 'clarity_grade' => 'SI2', 'base_price_per_carat' => 4000, 'price_multiplier' => 1.00, 'shape' => 'Round', 'fluorescence' => 'Medium', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            
            // Cushion Cut Diamonds
            ['carat_range_min' => 1.00, 'carat_range_max' => 1.99, 'cut_grade' => 'Excellent', 'color_grade' => 'E', 'clarity_grade' => 'VVS1', 'base_price_per_carat' => 9000, 'price_multiplier' => 1.25, 'shape' => 'Cushion', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 2.00, 'carat_range_max' => 2.99, 'cut_grade' => 'Excellent', 'color_grade' => 'E', 'clarity_grade' => 'VVS1', 'base_price_per_carat' => 10500, 'price_multiplier' => 1.30, 'shape' => 'Cushion', 'fluorescence' => 'None', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            
            // Princess Cut Diamonds
            ['carat_range_min' => 0.75, 'carat_range_max' => 1.49, 'cut_grade' => 'Very Good', 'color_grade' => 'F', 'clarity_grade' => 'VS2', 'base_price_per_carat' => 6800, 'price_multiplier' => 1.12, 'shape' => 'Princess', 'fluorescence' => 'Faint', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
            ['carat_range_min' => 1.50, 'carat_range_max' => 2.49, 'cut_grade' => 'Very Good', 'color_grade' => 'F', 'clarity_grade' => 'VS2', 'base_price_per_carat' => 7800, 'price_multiplier' => 1.18, 'shape' => 'Princess', 'fluorescence' => 'Faint', 'certification' => 'GIA', 'date_updated' => now()->toDateString(), 'market_region' => 'Global', 'is_active' => 1],
        ];

        foreach ($diamondPricings as $pricing) {
            DB::table('diamond_pricings')->insertOrIgnore($pricing);
        }
    }
}
