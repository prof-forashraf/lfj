<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Tag;

class PostTagSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            'unveiling-the-allure-of-diamond-necklaces-a-timeless-symbol-of-elegance' => ['diamond', 'necklace'],
            'the-timeless-allure-of-diamond-necklaces' => ['diamond', 'necklace'],
            'choosing-the-perfect-engagement-ring-a-symbol-of-everlasting-love' => ['engagement', 'ring', 'wedding'],
            'pearl-jewelry-timeless-elegance-for-every-occasion' => ['pearl', 'necklace'],
            'cyber-couture-jewelry-meets-the-metaverse-diamonds-for-your-digital-avatar' => ['diamond', 'gold'],
        ];

        foreach ($mappings as $postSlug => $tagSlugs) {
            $post = Post::where('slug', $postSlug)->first();
            if (! $post) {
                continue;
            }

            $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id')->toArray();
            if (count($tagIds) === 0) {
                continue;
            }

            $post->tags()->syncWithoutDetaching($tagIds);
        }
    }
}
