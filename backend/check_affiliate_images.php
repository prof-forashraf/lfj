<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    $stmt = $pdo->query("SELECT id, product_name_snapshot, amazon_asin, local_image_path, image_urls, is_featured FROM affiliate_products WHERE is_featured = 1 LIMIT 10");
    $featured = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->query("SELECT id, product_name_snapshot, amazon_asin, local_image_path, image_urls, is_featured FROM affiliate_products WHERE is_featured = 0 LIMIT 5");
    $nonFeatured = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    $results = array_merge($featured, $nonFeatured);

    foreach ($results as &$row) {
        $expectedPath = 'affiliate-images/' . $row['amazon_asin'] . '-large.webp';
        $fileExists = file_exists(__DIR__ . '/public/storage/' . $expectedPath);
        $row['expected_file'] = $expectedPath;
        $row['file_exists'] = $fileExists;
        
        $localExists = false;
        if ($row['local_image_path']) {
            $localExists = file_exists(__DIR__ . '/public/storage/' . $row['local_image_path']);
        }
        $row['local_exists'] = $localExists;
    }
    
    echo json_encode($results, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
