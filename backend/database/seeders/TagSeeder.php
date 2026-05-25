<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'diamond', 'slug' => 'diamond'],
            ['name' => 'necklace', 'slug' => 'necklace'],
            ['name' => 'engagement', 'slug' => 'engagement'],
            ['name' => 'wedding', 'slug' => 'wedding'],
            ['name' => 'pearl', 'slug' => 'pearl'],
            ['name' => 'earrings', 'slug' => 'earrings'],
            ['name' => 'ring', 'slug' => 'ring'],
            ['name' => 'gold', 'slug' => 'gold'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['slug' => $tag['slug']],
                $tag
            );
        }
    }
}
