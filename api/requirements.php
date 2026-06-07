<?php
/**
 * api/requirements.php
 * Serves document requirements from the `requirements` table.
 * Used by apply.php to dynamically fetch required documents per service type.
 * GET ?service_type=visa  → returns requirements for that type (default: all)
 * Returns flat array of {id, name, desc, required, accept, group, service_type}
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

// Allow unauthenticated access for the application form (auth is checked by the form itself)
header('Content-Type: application/json');

try {
    $db = getDb();
    $serviceType = trim($_GET['service_type'] ?? '');

    if ($serviceType) {
        $stmt = $db->prepare("SELECT * FROM requirements WHERE service_type = ?");
        $stmt->execute([$serviceType]);
        $rows = $stmt->fetchAll();
    } else {
        $rows = $db->query("SELECT * FROM requirements ORDER BY service_type")->fetchAll();
    }

    $allRequirements = [];

    foreach ($rows as $r) {
        $categories = json_decode($r['categories'], true) ?: [];
        foreach ($categories as $group) {
            $groupName = $group['name'] ?? 'General';
            $items     = $group['items'] ?? [];
            foreach ($items as $item) {
                // If item is a string, treat it as the name; if array, extract fields
                if (is_string($item)) {
                    $allRequirements[] = [
                        'id'           => sanitize_id($item),
                        'name'         => $item,
                        'desc'         => '',
                        'required'     => true,
                        'accept'       => '.pdf,.jpg,.jpeg,.png,.doc,.docx',
                        'group'        => $groupName,
                        'service_type' => $r['service_type'],
                    ];
                } elseif (is_array($item)) {
                    $allRequirements[] = [
                        'id'           => sanitize_id($item['name'] ?? $item['label'] ?? ''),
                        'name'         => $item['name'] ?? $item['label'] ?? 'Untitled',
                        'desc'         => $item['desc'] ?? $item['description'] ?? '',
                        'required'     => isset($item['required']) ? (bool)$item['required'] : true,
                        'accept'       => $item['accept'] ?? '.pdf,.jpg,.jpeg,.png,.doc,.docx',
                        'group'        => $groupName,
                        'service_type' => $r['service_type'],
                    ];
                }
            }
        }
    }

    echo json_encode(['success' => true, 'requirements' => $allRequirements]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

/**
 * Convert a string to a URL-safe document ID.
 */
function sanitize_id(string $str): string {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9]+/', '_', $str);
    $str = trim($str, '_');
    return $str ?: 'doc_' . substr(md5($str), 0, 8);
}
