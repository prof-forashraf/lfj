<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MarketRatePermissionsSeeder::class,
            RolesAndPermissionsSeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            UserSeeder::class,
            BlogPostsSeeder::class,
            ZakatRatesSeeder::class,
            GoldPricesSeeder::class,
            DiamondPricingsSeeder::class,
            GemstonePricingsSeeder::class,
            DailyMetalPricesSeeder::class,
            EventsSeeder::class,
            PostSeeder::class,
            AffiliateProductSeeder::class,
            AffiliateProductPostSeeder::class,
            CategoryPostSeeder::class,
            PostTagSeeder::class,
        ]);
    }
}