<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lotoks', 'root', '');
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . count($tables) . "\n";
    echo implode(', ', $tables) . "\n\n";
    foreach ($tables as $t) {
        $cols = $pdo->query("DESCRIBE `$t`")->fetchAll(PDO::FETCH_ASSOC);
        echo "=== $t ===\n";
        foreach ($cols as $c) {
            echo "  {$c['Field']}  {$c['Type']}  NULL:{$c['Null']}  KEY:{$c['Key']}  Default:{$c['Default']}\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
