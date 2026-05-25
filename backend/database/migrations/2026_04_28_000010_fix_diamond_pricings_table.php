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
        Schema::rename('diamond_pricing', 'diamond_pricings');

        Schema::table('diamond_pricings', function (Blueprint $table) {
            $table->enum('cut_grade', ['Excellent', 'Very Good', 'Good', 'Fair', 'Poor'])->change();
            $table->enum('color_grade', ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'])->change();
            $table->enum('clarity_grade', ['FL', 'IF', 'VVS1', 'VVS2', 'VS1', 'VS2', 'SI1', 'SI2', 'I1', 'I2', 'I3'])->change();
            $table->enum('fluorescence', ['None', 'Faint', 'Medium', 'Strong'])->change();
            $table->collation('utf8_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diamond_pricings', function (Blueprint $table) {
            $table->string('cut_grade', 20)->change();
            $table->string('color_grade', 5)->change();
            $table->string('clarity_grade', 10)->change();
            $table->string('fluorescence', 20)->change();
        });

        Schema::rename('diamond_pricings', 'diamond_pricing');
    }
};