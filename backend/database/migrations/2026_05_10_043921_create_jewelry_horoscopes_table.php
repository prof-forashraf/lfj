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
        Schema::create('jewelry_horoscopes', function (Blueprint $table) {
            $table->id();
            $table->string('zodiac_sign');
            $table->date('date');
            $table->text('description');
            $table->json('recommended_jewelry');
            $table->string('lucky_color')->nullable();
            $table->string('lucky_gemstone')->nullable();
            $table->timestamps();

            $table->unique(['zodiac_sign', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelry_horoscopes');
    }
};
