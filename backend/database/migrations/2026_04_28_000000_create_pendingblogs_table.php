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
        Schema::create('pendingblogs', function (Blueprint $table) {
            $table->increments('id'); // int NOT bigint
            $table->string('topic');
            $table->text('description');
            $table->string('local_image_path')->nullable();
            $table->string('product_link')->nullable();
            $table->tinyInteger('status_completed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendingblogs');
    }
};