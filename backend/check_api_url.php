<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/api/products/featured', 'GET');
$controller = app()->make(App\Http\Controllers\Api\AffiliateProductController::class);
$response = $controller->featured($request);

echo json_encode($response->toResponse($request)->getData(), JSON_PRETTY_PRINT);
