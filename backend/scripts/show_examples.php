<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AffiliateProduct;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

$product = AffiliateProduct::first();
if ($product) {
    echo "AffiliateProduct id={$product->id}\n";
    echo "main_image_url_snapshot: {$product->main_image_url_snapshot}\n";
    echo "try_on_image_url: {$product->try_on_image_url}\n";
    $accessor = $product->image_url ?? 'NULL';
    echo "image_url (accessor): {$accessor}\n";
    echo "effective_og_image: " . ($product->effective_og_image ?? 'NULL') . "\n";
    foreach (['local_image_path', 'main_image_url_snapshot', 'try_on_image_url'] as $field) {
        $val = $product->getAttributes()[$field] ?? null;
        if ($val && !str_starts_with($val, 'http')) {
            $exists = Storage::disk('public')->exists($val) ? 'YES' : 'NO';
            echo "{$field}={$val} exists={$exists}\n";
        }
    }
    echo "\n";
} else {
    echo "No AffiliateProduct found.\n\n";
}

$post = Post::first();
if ($post) {
    echo "Post id={$post->id}\n";
    echo "featured_image: {$post->featured_image}\n";
    echo "image_url (accessor): " . ($post->image_url ?? 'NULL') . "\n";
    echo "effective_og_image: " . ($post->effective_og_image ?? 'NULL') . "\n";
    if ($post->featured_image && !str_starts_with($post->featured_image, 'http')) {
        $exists = Storage::disk('public')->exists($post->featured_image) ? 'YES' : 'NO';
        echo "featured_image exists={$exists}\n";
    }
} else {
    echo "No Post found.\n";
}
