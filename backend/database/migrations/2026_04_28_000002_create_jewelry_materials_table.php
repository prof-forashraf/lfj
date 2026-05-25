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
        Schema::create('jewelry_materials', function (Blueprint $table) {
            $table->increments('id'); // int NOT bigint
            $table->enum('material_type', ['Metal', 'Gemstone', 'Finding', 'Labor']);
            $table->string('material_name', 100);
            $table->string('material_category', 50);
            $table->enum('unit_type', ['gram', 'carat', 'piece', 'hour']);
            $table->decimal('price_per_unit', 10, 2);
            $table->string('currency_code', 3)->default('USD');
            $table->string('purity_grade', 20)->nullable();
            $table->string('quality_grade', 20)->nullable();
            $table->string('supplier', 100)->nullable();
            $table->decimal('minimum_order_quantity', 8, 3)->default(0.000);
            $table->date('date_updated');
            $table->date('price_valid_until')->nullable();
            $table->string('market_region', 50)->default('Global');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->collation('utf8_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelry_materials');
    }
};