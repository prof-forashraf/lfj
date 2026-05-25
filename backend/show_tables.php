<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=lfja', 'root', '');
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
    
    // Drop views first
    $stmt = $pdo->query('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = "lfja"');
    while ($row = $stmt->fetch()) {
        $pdo->exec("DROP VIEW `{$row[0]}`");
        echo "Dropped view {$row[0]}\n";
    }
    
    // Then drop tables
    $stmt = $pdo->query('SHOW TABLES');
    $tables = [];
    while ($row = $stmt->fetch()) {
        $tables[] = $row[0];
    }
    
    // Force drop gemstone_pricing and gemstone_pricings if they exist
    foreach (['gemstone_pricing', 'gemstone_pricings'] as $table) {
        try {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
            echo "Dropped table $table (if existed)\n";
        } catch (Exception $e) {
            // Ignore
        }
    }
    
    foreach ($tables as $table) {
        try {
            $pdo->exec("DROP TABLE `$table`");
            echo "Dropped table $table\n";
        } catch (Exception $e) {
            echo "Could not drop $table: " . $e->getMessage() . "\n";
        }
    }
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    echo "All tables and views dropped.\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}