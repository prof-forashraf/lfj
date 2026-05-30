<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    
    echo "Fixing Posts without images...\n";
    $stmt = $pdo->query("SELECT id FROM posts WHERE featured_image IS NULL OR featured_image = ''");
    $posts = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $postUpdate = $pdo->prepare("UPDATE posts SET featured_image = :new_image WHERE id = :id");
    
    // get available images
    $images = array_values(array_diff(scandir(__DIR__ . '/public/storage/post-featured-images/'), ['.', '..']));
    $imgCount = count($images);
    
    if ($imgCount > 0) {
        foreach ($posts as $index => $postId) {
            $randomImage = $images[$index % $imgCount];
            $newImg = 'post-featured-images/' . $randomImage;
            $postUpdate->execute(['new_image' => $newImg, 'id' => $postId]);
        }
        echo "Updated " . count($posts) . " posts with random images.\n";
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
