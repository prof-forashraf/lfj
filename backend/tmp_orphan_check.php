<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja;charset=utf8mb4','root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$queries = [
    'affiliate_products with missing category' => 'SELECT COUNT(*) AS count FROM affiliate_products ap LEFT JOIN categories c ON ap.category_id = c.id WHERE ap.category_id IS NOT NULL AND c.id IS NULL',
    'posts with missing user' => 'SELECT COUNT(*) AS count FROM posts p LEFT JOIN users u ON p.user_id = u.id WHERE u.id IS NULL',
    'category_post orphaned categories' => 'SELECT COUNT(*) AS count FROM category_post cp LEFT JOIN categories c ON cp.category_id = c.id WHERE c.id IS NULL',
    'category_post orphaned posts' => 'SELECT COUNT(*) AS count FROM category_post cp LEFT JOIN posts p ON cp.post_id = p.id WHERE p.id IS NULL',
    'affiliate_product_post orphaned products' => 'SELECT COUNT(*) AS count FROM affiliate_product_post app LEFT JOIN affiliate_products ap ON app.affiliate_product_id = ap.id WHERE ap.id IS NULL',
    'affiliate_product_post orphaned posts' => 'SELECT COUNT(*) AS count FROM affiliate_product_post app LEFT JOIN posts p ON app.post_id = p.id WHERE p.id IS NULL',
    'users without roles' => 'SELECT COUNT(*) AS count FROM users u LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id AND mhr.model_type = "App\\Models\\User" WHERE mhr.model_id IS NULL',
    'audit_logs with missing user' => 'SELECT COUNT(*) AS count FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id WHERE al.user_id IS NOT NULL AND u.id IS NULL',
];
foreach ($queries as $label => $sql) {
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "$label: {$row['count']}\n";
}
