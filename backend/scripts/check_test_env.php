<?php

// Ensure testing environment is set before booting the app.
putenv('APP_ENV=testing');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo 'ENV=' . getenv('APP_ENV') . PHP_EOL;
echo 'DB_CONN=' . getenv('DB_CONNECTION') . PHP_EOL;
echo 'DB_DB=' . getenv('DB_DATABASE') . PHP_EOL;
echo 'cfg.default=' . $app->make('config')->get('database.default') . PHP_EOL;
echo 'cfg.sqlite=' . $app->make('config')->get('database.connections.sqlite.database') . PHP_EOL;
