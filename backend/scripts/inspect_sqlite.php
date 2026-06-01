<?php
$db = new PDO('sqlite:' . __DIR__ . '/../database/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_COLUMN);
echo "Found tables: " . implode(', ', $tables) . "\n\n";
$check = ['posts','categories','products','affiliate_products','users','events','pages'];
foreach ($check as $t) {
    if (in_array($t, $tables)) {
        $cnt = $db->query("SELECT COUNT(*) FROM \"$t\"")->fetchColumn();
        echo "$t: $cnt\n";
    }
}

// list recent posts
if (in_array('posts', $tables)) {
    echo "\nSample posts:\n";
    $cols = $db->query("PRAGMA table_info(posts)")->fetchAll(PDO::FETCH_ASSOC);
    $colNames = array_map(function($c){ return $c['name']; }, $cols);
    echo "posts columns: " . implode(', ', $colNames) . "\n";
    $rows = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $parts = [];
        foreach (['id','title','slug','status','published_at','is_published'] as $k) {
            if (array_key_exists($k, $r)) $parts[] = "$k=" . (string)$r[$k];
        }
        echo ' - ' . implode(' | ', $parts) . "\n";
    }
}
