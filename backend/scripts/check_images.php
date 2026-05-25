<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AffiliateProduct;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

echo "Checking AffiliateProduct images...\n";
$products = AffiliateProduct::whereNotNull('main_image_url_snapshot')->take(8)->get();
if ($products->isEmpty()) {
    echo "No AffiliateProduct records with main_image_url_snapshot found.\n";
} else {
    foreach ($products as $p) {
        $path = $p->main_image_url_snapshot;
        $exists = Storage::disk('public')->exists($path) ? 'YES' : 'NO';
        echo "Product {$p->id} ASIN {$p->amazon_asin} path={$path} exists={$exists}\n";
    }
}

echo "\nChecking Post featured images...\n";
$posts = Post::whereNotNull('featured_image')->take(8)->get();
if ($posts->isEmpty()) {
    echo "No Post records with featured_image found.\n";
} else {
    foreach ($posts as $post) {
        $path = $post->featured_image;
        $exists = Storage::disk('public')->exists($path) ? 'YES' : 'NO';
        $title = str_replace("\n", ' ', substr($post->title, 0, 40));
        echo "Post {$post->id} title=\"{$title}\" path={$path} exists={$exists}\n";
    }
}

echo "\nDone.\n";
