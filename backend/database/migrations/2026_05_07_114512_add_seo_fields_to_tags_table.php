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
        Schema::table('tags', function (Blueprint $table) {
            $table->string('og_title')->nullable();
            $table->string('og_description', 255)->nullable();
            $table->string('og_image')->nullable();
            $table->string('schema_type')->nullable()->default('CollectionPage');
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropColumn([
                'og_title',
                'og_description',
                'og_image',
                'schema_type',
            ]);
        });
    }
};
