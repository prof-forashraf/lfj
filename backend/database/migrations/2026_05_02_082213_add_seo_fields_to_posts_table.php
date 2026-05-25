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
        Schema::table('posts', function (Blueprint $table) {
            // Open Graph fields
            $table->string('og_title')->nullable();
            $table->string('og_description', 255)->nullable();
            $table->string('og_image')->nullable();

            // Twitter Card fields
            $table->string('twitter_title')->nullable();
            $table->string('twitter_description', 255)->nullable();

            // Schema.org type
            $table->string('schema_type')->default('Article');

            // SEO fields
            $table->string('focus_keyword')->nullable();
            $table->tinyInteger('reading_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'og_title',
                'og_description',
                'og_image',
                'twitter_title',
                'twitter_description',
                'schema_type',
                'focus_keyword',
                'reading_time'
            ]);
        });
    }
};
