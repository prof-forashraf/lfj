<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cookie_consent_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cookie_consent_id')->constrained('cookie_consents')->cascadeOnDelete();
            $table->string('action');
            $table->json('previous_state')->nullable();
            $table->json('new_state')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cookie_consent_logs');
    }
};
