<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    // Un-feature all
    $pdo->exec("UPDATE affiliate_products SET is_featured = 0");
    
    // Feature 4 products that have valid images
    $stmt = $pdo->query("SELECT id FROM affiliate_products WHERE image_urls IS NOT NULL AND image_urls != '' AND status='active' LIMIT 4");
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($ids) > 0) {
        $idStr = implode(',', $ids);
        $pdo->exec("UPDATE affiliate_products SET is_featured = 1 WHERE id IN ($idStr)");
        echo "Featured products updated to IDs: $idStr\n";
    } else {
        echo "No products with images found!\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
