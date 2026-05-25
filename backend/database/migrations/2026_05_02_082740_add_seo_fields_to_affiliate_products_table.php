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
        Schema::table('affiliate_products', function (Blueprint $table) {
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description', 255)->nullable();
            $table->string('schema_type')->default('Product');
        });
    }

    public function down(): void
    {
        Schema::table('affiliate_products', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'meta_description',
                'og_title',
                'og_description',
                'schema_type'
            ]);
        });
    }
};
