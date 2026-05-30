<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    DB::connection()->getPdo();
    echo "DB Connection: OK\n";
} catch(Exception $e) {
    echo "DB Connection: FAILED - " . $e->getMessage() . "\n";
}

$post = App\Models\Post::first();
echo "Post reading_time: ";
var_dump($post->reading_time);
echo "Type: " . gettype($post->reading_time) . "\n";

echo "Posts count: " . App\Models\Post::count() . "\n";
echo "Products count: " . App\Models\AffiliateProduct::count() . "\n";
echo "Categories count: " . App\Models\Category::count() . "\n";