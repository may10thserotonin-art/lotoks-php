<?php
require_once __DIR__ . '/db/connect.php';
$db = getDb();

$sql = "
CREATE TABLE IF NOT EXISTS activity_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NULL,
  admin_id INT NULL,
  action VARCHAR(255),
  description TEXT,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
);

ALTER TABLE applications ADD COLUMN IF NOT EXISTS admin_notes TEXT DEFAULT NULL;
ALTER TABLE applications ADD COLUMN IF NOT EXISTS reviewed_by INT DEFAULT NULL;
ALTER TABLE applications ADD COLUMN IF NOT EXISTS reviewed_at TIMESTAMP NULL;
";

try {
    // Cannot do multiple ALTER TABLE IF NOT EXISTS in all MySQL versions cleanly without errors if they already exist, 
    // but PHP PDO can execute multi-queries if emulated.
    // Let's do them one by one.
    $db->exec("CREATE TABLE IF NOT EXISTS activity_log (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        admin_id INT NULL,
        action VARCHAR(255),
        description TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (admin_id) REFERENCES admins(id) ON DELETE SET NULL
    )");
    
    try { $db->exec("ALTER TABLE applications ADD COLUMN admin_notes TEXT DEFAULT NULL"); } catch(Exception $e) {}
    try { $db->exec("ALTER TABLE applications ADD COLUMN reviewed_by INT DEFAULT NULL"); } catch(Exception $e) {}
    try { $db->exec("ALTER TABLE applications ADD COLUMN reviewed_at TIMESTAMP NULL"); } catch(Exception $e) {}
    try { $db->exec("ALTER TABLE applications ADD CONSTRAINT fk_reviewed_by FOREIGN KEY (reviewed_by) REFERENCES admins(id) ON DELETE SET NULL"); } catch(Exception $e) {}
    
    echo "Schema updated successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
