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
        Schema::create('trending_looks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_url');
            $table->json('jewelry_items'); // array of jewelry IDs used in this look
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            $table->integer('shares')->default(0);
            $table->enum('status', ['draft', 'published', 'trending'])->default('draft');
            $table->string('category')->nullable(); // daily, weekly, celebrity, occasion, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trending_looks');
    }
};
