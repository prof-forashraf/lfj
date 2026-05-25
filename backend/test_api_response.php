<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$products = \App\Models\AffiliateProduct::where('is_featured', 1)->take(1)->get();
echo "\n--- Affiliate Products ---\n";
echo json_encode($products->toArray(), JSON_PRETTY_PRINT);

$posts = \App\Models\Post::take(1)->get();
echo "\n--- Posts ---\n";
echo json_encode($posts->toArray(), JSON_PRETTY_PRINT);

$categories = \App\Models\Category::take(1)->get();
echo "\n--- Categories ---\n";
echo json_encode($categories->toArray(), JSON_PRETTY_PRINT);
