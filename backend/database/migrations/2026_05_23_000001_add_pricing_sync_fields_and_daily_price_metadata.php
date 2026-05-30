<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jewelry_materials', function (Blueprint $table) {
            if (!Schema::hasColumn('jewelry_materials', 'markup_percentage')) {
                $table->decimal('markup_percentage', 8, 2)->default(0)->after('price_per_unit');
            }

            if (!Schema::hasColumn('jewelry_materials', 'is_auto_synced')) {
                $table->boolean('is_auto_synced')->default(false)->after('market_region');
            }

            if (!Schema::hasColumn('jewelry_materials', 'sync_source')) {
                $table->string('sync_source', 50)->nullable()->after('is_auto_synced');
            }

            if (!Schema::hasColumn('jewelry_materials', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable()->after('sync_source');
            }

            if (!Schema::hasColumn('jewelry_materials', 'manual_override')) {
                $table->boolean('manual_override')->default(false)->after('last_synced_at');
            }

            if (!Schema::hasColumn('jewelry_materials', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('is_active');
            }

            if (!Schema::hasIndex('jewelry_materials', 'idx_jewelry_materials_auto_synced_type')) {
                $table->index(['is_auto_synced', 'material_type'], 'idx_jewelry_materials_auto_synced_type');
            }
        });

        Schema::table('daily_metal_prices', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_metal_prices', 'source')) {
                $table->string('source', 100)->nullable()->after('unit');
            }

            if (!Schema::hasColumn('daily_metal_prices', 'notes')) {
                $table->text('notes')->nullable()->after('source');
            }

            if (!Schema::hasIndex('daily_metal_prices', 'idx_daily_metal_prices_symbol_currency_date')) {
                $table->index(['metal_symbol', 'base_currency', 'price_date'], 'idx_daily_metal_prices_symbol_currency_date');
            }
        });

        Schema::table('gold_prices', function (Blueprint $table) {
            // Ensure we only try to add indexes for columns that exist in the schema.
            // The `gold_prices` table uses `currency_code` for the currency field.
            if (Schema::hasColumn('gold_prices', 'currency_code') && Schema::hasColumn('gold_prices', 'date_recorded')) {
                try {
                    $table->index(['currency_code', 'date_recorded'], 'idx_gold_prices_currency_date');
                } catch (\Exception $e) {
                    // Index already exists or cannot be created on this platform; ignore.
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_metal_prices', function (Blueprint $table) {
            if (Schema::hasColumn('daily_metal_prices', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('daily_metal_prices', 'source')) {
                $table->dropColumn('source');
            }
        });

        Schema::table('jewelry_materials', function (Blueprint $table) {
            if (Schema::hasColumn('jewelry_materials', 'updated_by')) {
                $table->dropColumn('updated_by');
            }
            if (Schema::hasColumn('jewelry_materials', 'manual_override')) {
                $table->dropColumn('manual_override');
            }
            if (Schema::hasColumn('jewelry_materials', 'last_synced_at')) {
                $table->dropColumn('last_synced_at');
            }
            if (Schema::hasColumn('jewelry_materials', 'sync_source')) {
                $table->dropColumn('sync_source');
            }
            if (Schema::hasColumn('jewelry_materials', 'is_auto_synced')) {
                $table->dropColumn('is_auto_synced');
            }
            if (Schema::hasColumn('jewelry_materials', 'markup_percentage')) {
                $table->dropColumn('markup_percentage');
            }
        });
    }
};
