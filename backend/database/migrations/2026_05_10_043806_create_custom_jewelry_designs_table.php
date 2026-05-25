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
        Schema::create('custom_jewelry_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->string('jewelry_type'); // ring, necklace, etc.
            $table->json('materials'); // metals, stones
            $table->json('customizations'); // engravings, sizes, etc.
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->boolean('is_public')->default(false);
            $table->json('preview_image')->nullable(); // base64 or URL
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_jewelry_designs');
    }
};
