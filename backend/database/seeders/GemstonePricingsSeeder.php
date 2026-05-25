<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GemstonePricingsSeeder extends Seeder
{
    public function run(): void
    {
        $gemstones = [
            ['name' => 'Ruby', 'price_per_carat' => 850.00],
            ['name' => 'Sapphire', 'price_per_carat' => 600.00],
            ['name' => 'Emerald', 'price_per_carat' => 500.00],
            ['name' => 'Pearl', 'price_per_carat' => 120.00],
            ['name' => 'Topaz', 'price_per_carat' => 80.00],
            ['name' => 'Amethyst', 'price_per_carat' => 25.00],
            ['name' => 'Citrine', 'price_per_carat' => 30.00],
            ['name' => 'Garnet', 'price_per_carat' => 40.00],
            ['name' => 'Turquoise', 'price_per_carat' => 50.00],
            ['name' => 'Opal', 'price_per_carat' => 200.00],
            ['name' => 'Jade', 'price_per_carat' => 100.00],
            ['name' => 'Moonstone', 'price_per_carat' => 35.00],
            ['name' => 'Aquamarine', 'price_per_carat' => 200.00],
            ['name' => 'Tanzanite', 'price_per_carat' => 350.00],
            ['name' => 'Peridot', 'price_per_carat' => 120.00],
        ];

        foreach ($gemstones as $gemstone) {
            DB::table('gemstone_pricings')->insertOrIgnore([
                'name' => $gemstone['name'],
                'slug' => Str::slug($gemstone['name']),
                'price_per_carat' => $gemstone['price_per_carat'],
                'is_active' => 1,
            ]);
        }
    }
}
