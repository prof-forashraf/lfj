<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip view creation for SQLite as it has limitations with views during migrations
        if (config('database.default') === 'sqlite') {
            return;
        }

        // Create current_gold_prices view
        DB::statement("
            CREATE OR REPLACE VIEW current_gold_prices AS
            SELECT gp.*
            FROM gold_prices gp
            INNER JOIN (
                SELECT currency_code, MAX(CONCAT(date_recorded, ' ', COALESCE(timestamp_recorded, '00:00:00'))) as max_datetime
                FROM gold_prices
                WHERE is_active = 1
                GROUP BY currency_code
            ) latest ON gp.currency_code = latest.currency_code
            AND CONCAT(gp.date_recorded, ' ', COALESCE(gp.timestamp_recorded, '00:00:00')) = latest.max_datetime
            WHERE gp.is_active = 1
        ");

        // Create current_material_prices view
        DB::statement("
            CREATE OR REPLACE VIEW current_material_prices AS
            SELECT *
            FROM jewelry_materials
            WHERE is_active = 1
            AND (price_valid_until IS NULL OR price_valid_until > NOW())
        ");

        // Create current_zakat_rates view
        DB::statement("
            CREATE OR REPLACE VIEW current_zakat_rates AS
            SELECT *
            FROM zakat_rates
            WHERE is_active = 1
            AND calculation_year = YEAR(NOW())
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip view dropping for SQLite
        if (config('database.default') === 'sqlite') {
            return;
        }

        DB::statement('DROP VIEW IF EXISTS current_zakat_rates');
        DB::statement('DROP VIEW IF EXISTS current_material_prices');
        DB::statement('DROP VIEW IF EXISTS current_gold_prices');
    }
};