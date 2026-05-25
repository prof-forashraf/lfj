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
        Schema::table('categories', function (Blueprint $table) {
            // Open Graph fields
            $table->string('og_title')->nullable();
            $table->string('og_description', 255)->nullable();
            $table->string('og_image')->nullable();

            // Schema.org type
            $table->string('schema_type')->default('CollectionPage');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'og_title',
                'og_description',
                'og_image',
                'schema_type'
            ]);
        });
    }
};
