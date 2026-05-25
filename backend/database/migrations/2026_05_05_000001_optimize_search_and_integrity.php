<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration was previously trying to add indexes that are causing duplicates
        // All indexes and optimizations are handled in their respective table creation migrations
        // This is now a no-op migration to maintain migration history
    }

    public function down(): void
    {
        // No-op migration
    }
};
