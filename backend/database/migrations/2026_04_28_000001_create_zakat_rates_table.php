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
        Schema::create('zakat_rates', function (Blueprint $table) {
            $table->increments('id'); // int NOT bigint
            $table->year('calculation_year');
            $table->decimal('nisab_gold_grams', 8, 3)->default(87.480);
            $table->decimal('nisab_silver_grams', 8, 3)->default(612.360);
            $table->decimal('zakat_percentage', 5, 4)->default(2.5000);
            $table->decimal('gold_price_per_gram', 10, 2);
            $table->decimal('silver_price_per_gram', 10, 2);
            $table->string('currency_code', 3)->default('USD');
            $table->smallInteger('lunar_year_days')->default(354);
            $table->smallInteger('holding_period_days')->default(354);
            $table->boolean('is_active')->default(true);
            $table->date('date_effective');
            $table->timestamps();

            $table->unique(['calculation_year', 'currency_code'], 'zakat_rates_calculation_year_currency_unique');
            $table->collation('utf8_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zakat_rates');
    }
};