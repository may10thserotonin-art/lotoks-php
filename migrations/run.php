<?php
/**
 * Run pending migrations.
 * Usage: php migrations/run.php
 */
require_once __DIR__ . '/../db/connect.php';

$db = getDb();
$migrationsDir = __DIR__;

$files = glob($migrationsDir . '/*.sql');
sort($files);

foreach ($files as $file) {
    $basename = basename($file);
    if ($basename === basename(__FILE__)) continue;

    echo "Running: {$basename} ... ";

    $sql = file_get_contents($file);
    try {
        $db->exec($sql);
        echo "OK\n";
    } catch (Exception $e) {
        $msg = $e->getMessage();
        if (str_contains($msg, 'Duplicate column name') || str_contains($msg, 'already exists')) {
            echo "SKIP (already applied)\n";
        } else {
            echo "ERROR: {$msg}\n";
        }
    }
}

echo "\nDone.\n";
