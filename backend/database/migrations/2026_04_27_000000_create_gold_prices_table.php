<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gold_prices', function (Blueprint $table) {
            $table->id();
            $table->date('date_recorded');
            $table->dateTime('timestamp_recorded')->nullable();
            $table->string('currency_code', 3)->default('USD');
            $table->decimal('price_per_ounce', 18, 6)->default(0);
            $table->decimal('price_per_gram_24k', 18, 6)->default(0);
            $table->decimal('price_per_gram_22k', 18, 6)->default(0);
            $table->decimal('price_per_gram_18k', 18, 6)->default(0);
            $table->decimal('price_per_gram_14k', 18, 6)->default(0);
            $table->decimal('price_per_gram_10k', 18, 6)->default(0);
            $table->decimal('market_open', 18, 6)->nullable();
            $table->decimal('market_high', 18, 6)->nullable();
            $table->decimal('market_low', 18, 6)->nullable();
            $table->decimal('market_close', 18, 6)->nullable();
            $table->decimal('volume', 20, 8)->nullable();
            $table->string('source', 100)->nullable()->default('Manual');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['date_recorded', 'currency_code'], 'gold_prices_date_currency_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gold_prices');
    }
};
