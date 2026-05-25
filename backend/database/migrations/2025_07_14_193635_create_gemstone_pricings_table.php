<?php
// new gemstone_pricing migration file
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('gemstone_pricing', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 'Diamond', 'Ruby'
            $table->string('slug')->unique(); // e.g., 'diamond', 'ruby'
            $table->decimal('price_per_carat', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    // ... down() method
};