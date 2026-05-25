<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_products', function (Blueprint $table) {
            $table->id();
            $table->string('amazon_asin')->unique()->comment('Amazon Standard Identification Number');
            $table->text('product_name_snapshot')->comment('Name of product at time of adding'); // For your reference
            $table->string('amazon_url')->nullable()->comment('Direct affiliate link (optional if generated elsewhere)');
            $table->text('your_notes')->nullable()->comment('Why you recommend it, keywords, etc.');
            $table->string('main_image_url_snapshot')->nullable()->comment('Snapshot of main image URL (from Amazon)');
            // You might add foreign keys to your own Categories/Tags here
            // $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->string('status')->default('active'); // e.g., active, needs_review, outdated
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_products');
    }
};