<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach (App\Models\User::all() as $u) {
    echo $u->email . ' | ' . ($u->roles->pluck('name')->implode(', ') ?: 'no role') . PHP_EOL;
}
