<?php
/**
 * admin/api/get-requirements.php
 * Returns global requirements for a given service_type.
 * GET param: service_type (optional — returns all grouped if omitted)
 *
 * The `requirements` table has:
 *   service_type VARCHAR (visa, job, edu, pr)
 *   categories   LONGTEXT (JSON array of {name, items[]})
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

$db           = getDb();
$service_type = trim($_GET['service_type'] ?? '');

if ($service_type) {
    $stmt = $db->prepare("SELECT * FROM requirements WHERE service_type = ?");
    $stmt->execute([$service_type]);
    $rows = $stmt->fetchAll();
} else {
    $rows = $db->query("SELECT * FROM requirements ORDER BY service_type")->fetchAll();
}

// Flatten categories into individual requirement items
$requirements = [];
foreach ($rows as $r) {
    $categories = json_decode($r['categories'], true) ?: [];
    foreach ($categories as $group) {
        $groupName = $group['name'] ?? 'General';
        $items     = $group['items'] ?? [];
        foreach ($items as $item) {
            $requirements[] = [
                'label'           => $item,
                'group'           => $groupName,
                'service_type'    => $r['service_type'],
            ];
        }
    }
}

json_ok(['requirements' => $requirements]);
