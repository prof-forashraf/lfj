<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    echo "--- affiliate_products ---\n";
    $stmt = $pdo->query("SELECT id, image_urls, local_image_path, main_image_url_snapshot FROM affiliate_products WHERE is_featured=1 LIMIT 3");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\n--- posts ---\n";
    $stmt = $pdo->query("SELECT id, featured_image FROM posts LIMIT 3");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));

    echo "\n--- categories ---\n";
    $stmt = $pdo->query("SELECT id, image FROM categories LIMIT 3");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
