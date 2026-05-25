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
        $table->json('meta_keywords')->nullable()->change();
    });
}
public function down(): void
{
    Schema::table('posts', function (Blueprint $table) {
        // Be careful with down, might lose data or need to cast back to text
        $table->text('meta_keywords')->nullable()->change();
    });
}
};
