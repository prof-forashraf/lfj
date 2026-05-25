<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;

class CategoryPostSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            'unveiling-the-allure-of-diamond-necklaces-a-timeless-symbol-of-elegance' => ['necklaces'],
            'the-timeless-allure-of-diamond-necklaces' => ['necklaces'],
            'choosing-the-perfect-engagement-ring-a-symbol-of-everlasting-love' => ['rings'],
            'pearl-jewelry-timeless-elegance-for-every-occasion' => ['necklaces'],
            'cyber-couture-jewelry-meets-the-metaverse-diamonds-for-your-digital-avatar' => ['blog'],
        ];

        foreach ($mappings as $postSlug => $categorySlugs) {
            $post = Post::where('slug', $postSlug)->first();
            if (! $post) {
                continue;
            }

            $categoryIds = Category::whereIn('slug', $categorySlugs)->pluck('id')->toArray();
            if (count($categoryIds) === 0) {
                continue;
            }

            $post->categories()->syncWithoutDetaching($categoryIds);
        }
    }
}
