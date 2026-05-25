<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$tables = [
    'affiliate_products',
    'categories',
    'posts',
    'users',
    'diamond_pricings',
    'tags',
    'category_post',
    'affiliate_product_post',
    'settings',
    'audit_logs',
];
foreach ($tables as $t) {
    echo "\n-- $t --\n";
    try {
        $stmt = $pdo->query("SHOW CREATE TABLE `$t`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo ($row['Create Table'] ?? $row['CREATE TABLE']) . "\n";
    } catch (Exception $e) {
        echo "[missing table]\n";
    }
}
