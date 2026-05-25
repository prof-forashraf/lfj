<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    echo "Fixing Categories...\n";
    $stmt = $pdo->query("SELECT id, slug FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $catUpdate = $pdo->prepare("UPDATE categories SET image = :image WHERE id = :id");
    foreach ($categories as $cat) {
        $imagePath = 'categories/' . $cat['slug'] . '.jpg';
        if (file_exists(__DIR__ . '/public/storage/' . $imagePath)) {
            $catUpdate->execute(['image' => $imagePath, 'id' => $cat['id']]);
            echo "Category {$cat['slug']} updated to {$imagePath}\n";
        } else {
            echo "Image not found for category {$cat['slug']}\n";
        }
    }

    echo "\nFixing Posts...\n";
    $stmt = $pdo->query("SELECT id, featured_image FROM posts WHERE featured_image IS NOT NULL AND featured_image != ''");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $postUpdate = $pdo->prepare("UPDATE posts SET featured_image = :new_image WHERE id = :id");
    foreach ($posts as $post) {
        $img = $post['featured_image'];
        if (strpos($img, 'post-featured-images/') === false) {
            $newImg = 'post-featured-images/' . basename($img);
            if (file_exists(__DIR__ . '/public/storage/' . $newImg)) {
                $postUpdate->execute(['new_image' => $newImg, 'id' => $post['id']]);
                echo "Post {$post['id']} updated to {$newImg}\n";
            }
        }
    }

    echo "\nFixing Affiliate Products...\n";
    $stmt = $pdo->query("SELECT id, amazon_asin FROM affiliate_products WHERE status = 'active'");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $prodUpdate = $pdo->prepare("UPDATE affiliate_products SET image_urls = :image_urls, local_image_path = :local_image_path WHERE id = :id");
    
    foreach ($products as $prod) {
        $asin = $prod['amazon_asin'];
        $small = "affiliate-images/{$asin}-small.webp";
        $medium = "affiliate-images/{$asin}-medium.webp";
        $large = "affiliate-images/{$asin}-large.webp";
        
        if (file_exists(__DIR__ . '/public/storage/' . $small)) {
            $imageUrls = json_encode([
                'small' => $small,
                'medium' => file_exists(__DIR__ . '/public/storage/' . $medium) ? $medium : $small,
                'large' => file_exists(__DIR__ . '/public/storage/' . $large) ? $large : $small,
            ]);
            $prodUpdate->execute([
                'image_urls' => $imageUrls,
                'local_image_path' => $large,
                'id' => $prod['id']
            ]);
            // echo "Product {$asin} updated.\n";
        }
    }
    echo "Affiliate products fixed.\n";

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
