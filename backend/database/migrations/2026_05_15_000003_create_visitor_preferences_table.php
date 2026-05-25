<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_preferences', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 64)->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('theme')->default('system');
            $table->string('language')->default('en');
            $table->string('layout')->default('standard');
            $table->json('favorite_categories')->nullable();
            $table->json('favorite_tags')->nullable();
            $table->json('recently_viewed_products')->nullable();
            $table->json('recently_viewed_posts')->nullable();
            $table->json('saved_preferences')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_preferences');
    }
};
