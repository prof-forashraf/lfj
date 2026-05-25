<?php
$files = glob(__DIR__ . '/../database/migrations/*_create_*_table.php');
if (!$files) {
    echo "No migrations matched\n";
}
foreach ($files as $file) {
    echo basename($file) . PHP_EOL;
}
$groups = [];
foreach ($files as $file) {
    $name = basename($file);
    if (preg_match('/^.*_create_(.+)_table\.php$/', $name, $matches)) {
        echo "MATCH: $name => {$matches[1]}\n";
        $groups[$matches[1]][] = $file;
    } else {
        echo "NO MATCH: $name\n";
    }
}
foreach ($groups as $table => $migrationFiles) {
    if (count($migrationFiles) > 1) {
        echo "DUPLICATE: " . $table . ': ' . implode(', ', $migrationFiles) . PHP_EOL;
    }
}
