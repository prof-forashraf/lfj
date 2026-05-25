<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$stmt = $pdo->query("SELECT TABLE_NAME, ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA='lfja' AND TABLE_NAME IN ('settings','audit_logs')");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    printf("%s: %s\n", $row['TABLE_NAME'], $row['ENGINE']);
}
