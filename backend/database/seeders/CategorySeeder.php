<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Necklaces', 'slug' => 'necklaces', 'description' => 'Explore our stunning collection of necklaces', 'is_featured' => true],
            ['name' => 'Earrings', 'slug' => 'earrings', 'description' => 'Discover beautiful earrings for every style', 'is_featured' => true],
            ['name' => 'Bracelets', 'slug' => 'bracelets', 'description' => 'Find the perfect bracelet for your wrist', 'is_featured' => false],
            ['name' => 'Rings', 'slug' => 'rings', 'description' => 'Browse our elegant ring collection', 'is_featured' => true],
            ['name' => 'Anklets', 'slug' => 'anklets', 'description' => 'Add sparkle to your ankles with our anklets', 'is_featured' => false],
            ['name' => 'Brooches', 'slug' => 'brooches', 'description' => 'Classic and contemporary brooches', 'is_featured' => false],
            ['name' => 'Watches', 'slug' => 'watches', 'description' => 'Luxury and stylish watches', 'is_featured' => false],
            ['name' => 'Pendants', 'slug' => 'pendants', 'description' => 'Meaningful pendant jewelry', 'is_featured' => false],
            ['name' => 'Holiday Gifts', 'slug' => 'holiday-gifts', 'description' => 'Perfect gifts for the holidays', 'is_featured' => true],
            ['name' => 'Blog', 'slug' => 'blog', 'description' => 'Jewelry news and trends', 'is_featured' => false],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
