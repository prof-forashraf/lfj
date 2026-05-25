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
            $table->json('image_urls')->nullable()->after('main_image_url_snapshot');
            $table->text('try_on_image_url')->nullable()->after('image_urls');
            $table->text('item_type')->nullable()->after('try_on_image_url');
            $table->boolean('is_featured')->default(false)->after('item_type');
            $table->string('status')->default('active')->change(); // already exists, ensure default
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null')->after('status');
            $table->string('local_image_path')->nullable()->after('category_id');
            $table->string('try_on_type', 50)->nullable()->after('local_image_path');
            $table->string('try_on_anchor', 50)->nullable()->after('try_on_type');
            $table->decimal('try_on_scale_default', 5, 3)->default(0.100)->after('try_on_anchor');
            $table->integer('try_on_offset_y')->default(0)->after('try_on_scale_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'image_urls', 'try_on_image_url', 'item_type', 'is_featured',
                'category_id', 'local_image_path', 'try_on_type', 'try_on_anchor',
                'try_on_scale_default', 'try_on_offset_y'
            ]);
        });
    }
};