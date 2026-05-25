<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_metal_prices', function (Blueprint $table) {
            $table->date('price_date')->after('id');
            $table->string('base_currency', 3)->after('price_date');
            $table->string('metal_symbol', 20)->after('base_currency');
            $table->decimal('price_per_unit', 20, 8)->after('metal_symbol');
            $table->string('unit', 20)->after('price_per_unit');
            $table->unique(['price_date', 'base_currency', 'metal_symbol'], 'daily_metal_prices_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_metal_prices', function (Blueprint $table) {
            $table->dropUnique('daily_metal_prices_unique');
            $table->dropColumn(['price_date', 'base_currency', 'metal_symbol', 'price_per_unit', 'unit']);
        });
    }
};