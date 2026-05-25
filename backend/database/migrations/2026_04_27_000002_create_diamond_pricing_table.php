<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diamond_pricing', function (Blueprint $table) {
            $table->id();
            $table->decimal('carat_range_min', 4, 2);
            $table->decimal('carat_range_max', 4, 2);
            $table->string('cut_grade', 20);
            $table->string('color_grade', 5);
            $table->string('clarity_grade', 10);
            $table->decimal('base_price_per_carat', 12, 2);
            $table->decimal('price_multiplier', 5, 2)->default(1.00);
            $table->string('shape', 20)->default('Round');
            $table->string('fluorescence', 20)->default('None');
            $table->string('certification', 20)->default('GIA');
            $table->date('date_updated');
            $table->string('market_region', 50)->default('Global');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['carat_range_min', 'carat_range_max'], 'diamond_carat_range_index');
            $table->index(['cut_grade', 'color_grade', 'clarity_grade'], 'diamond_grades_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diamond_pricing');
    }
};
