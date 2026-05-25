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
        Schema::create('jewelry_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('gemstone_type'); // diamond, emerald, ruby, sapphire, etc.
            $table->string('jewelry_type'); // ring, necklace, earrings, bracelet
            $table->integer('difficulty_level'); // 1-5
            $table->integer('score');
            $table->integer('time_taken'); // seconds
            $table->json('answer_details'); // track which questions were answered correctly
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jewelry_matches');
    }
};
