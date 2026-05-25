<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime')->nullable();
            $table->string('location_name')->nullable();
            $table->string('location_address')->nullable();
            $table->string('event_url')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('status')->default('upcoming');
            $table->string('seo_title')->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->timestamps();

            $table->index(['start_datetime', 'status'], 'events_datetime_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
