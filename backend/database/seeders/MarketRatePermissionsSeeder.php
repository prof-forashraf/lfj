<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketRatePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view market rates']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'create market rates']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'update market rates']);
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'delete market rates']);
    }
}
