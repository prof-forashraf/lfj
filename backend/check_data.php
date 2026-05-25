<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    $tables = ['users', 'posts', 'categories', 'tags', 'roles', 'permissions', 'zakat_rates', 'gold_prices', 'gemstone_pricings', 'events', 'diamond_pricings', 'daily_metal_prices', 'videos', 'affiliate_products'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $table . ": " . $result['count'] . " records\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
