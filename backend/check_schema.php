<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    $tables = ['gold_prices', 'diamond_pricings', 'daily_metal_prices', 'gemstone_pricings', 'events'];
    
    foreach ($tables as $table) {
        echo "\n=== $table ===\n";
        $stmt = $pdo->query("DESCRIBE $table");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
