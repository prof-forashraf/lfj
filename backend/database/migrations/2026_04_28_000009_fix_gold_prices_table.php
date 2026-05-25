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
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->string('source', 100)->nullable()->default('API')->change();
            $table->unsignedBigInteger('volume')->nullable()->change(); // change to bigint
            $table->unique(['date_recorded', 'currency_code'], 'gold_prices_date_currency_unique');
            $table->index('timestamp_recorded', 'gold_prices_timestamp_index');
            $table->collation('utf8_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gold_prices', function (Blueprint $table) {
            $table->dropUnique('gold_prices_date_currency_unique');
            $table->dropIndex('gold_prices_timestamp_index');
            $table->decimal('volume', 20, 8)->nullable()->change();
            $table->string('source', 100)->nullable()->default('Manual')->change();
        });
    }
};