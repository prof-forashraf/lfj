<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    $tables = ['affiliate_products', 'posts', 'categories'];
    foreach ($tables as $t) {
        echo "\n--- $t ---\n";
        $stmt = $pdo->query("DESCRIBE $t");
        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $r['Field'] . ' | ';
        }
    }
} catch (Exception $e) { echo $e->getMessage(); }
