<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    echo "--- affiliate_products ---\n";
    $stmt = $pdo->query("SELECT id, image_url FROM affiliate_products LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | image_url: " . $row['image_url'] . "\n";
    }

    echo "--- posts ---\n";
    $stmt = $pdo->query("SELECT id, featured_image FROM posts LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | featured_image: " . $row['featured_image'] . "\n";
    }

    echo "--- categories ---\n";
    $stmt = $pdo->query("SELECT id, image FROM categories LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: " . $row['id'] . " | image: " . $row['image'] . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
