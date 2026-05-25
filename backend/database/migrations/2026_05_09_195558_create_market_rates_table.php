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
        Schema::create('market_rates', function (Blueprint $table) {
            $table->id();
            $table->string('metal_type'); // e.g., 'gold', 'silver', 'platinum'
            $table->decimal('price', 10, 2); // price per unit
            $table->string('currency', 3)->default('USD'); // currency code
            $table->date('rate_date')->index();
            $table->unsignedBigInteger('updated_by')->nullable(); // who updated it
            $table->softDeletes(); // for historical archive
            $table->timestamps();

            $table->unique(['metal_type', 'rate_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_rates');
    }
};
