<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AffiliateProduct;
use App\Models\Post;

class AffiliateProductPostSeeder extends Seeder
{
    public function run(): void
    {
        $relations = [
            'B08XYZ123A' => ['unveiling-the-allure-of-diamond-necklaces-a-timeless-symbol-of-elegance'],
            'B09ABC456B' => ['the-timeless-allure-of-diamond-necklaces', 'pearl-jewelry-timeless-elegance-for-every-occasion'],
            'B07DEF789C' => ['choosing-the-perfect-engagement-ring-a-symbol-of-everlasting-love'],
            'B0CKLMN10D' => ['choosing-the-perfect-engagement-ring-a-symbol-of-everlasting-love'],
            'B07H5RFZ9M' => ['pearl-jewelry-timeless-elegance-for-every-occasion'],
        ];

        foreach ($relations as $asin => $postSlugs) {
            $product = AffiliateProduct::where('amazon_asin', $asin)->first();
            if (! $product) {
                continue;
            }

            $postIds = Post::whereIn('slug', $postSlugs)->pluck('id')->toArray();
            if (count($postIds) === 0) {
                continue;
            }

            $product->posts()->syncWithoutDetaching($postIds);
        }
    }
}
