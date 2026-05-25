<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    $stmt = $pdo->query("SELECT id, amazon_asin, image_urls, local_image_path FROM affiliate_products WHERE amazon_asin = 'B013YF2OJ2'");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    
    // Also, how many products actually have 'B0...' ASIN?
    $stmt2 = $pdo->query("SELECT COUNT(*) FROM affiliate_products WHERE amazon_asin LIKE 'B0%'");
    echo "Count of B0 ASINs: " . $stmt2->fetchColumn() . "\n";
    
} catch (Exception $e) { echo $e->getMessage(); }
