<?php
/**
 * admin/api/manage-requirements.php
 * CRUD for admin Requirements Manager.
 * Actions: fetch, save, delete_tag
 */

require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

$admin   = get_admin();
$adminId = $admin['id'];
$db      = getDb();
$action  = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'fetch':
        // GET: fetch all requirements — no CSRF check (read-only)
        $serviceType = trim($_GET['service_type'] ?? '');
        if ($serviceType) {
            $stmt = $db->prepare("SELECT * FROM requirements WHERE service_type = ?");
            $stmt->execute([$serviceType]);
            $rows = $stmt->fetchAll();
        } else {
            $rows = $db->query("SELECT * FROM requirements ORDER BY service_type")->fetchAll();
        }

        $result = [];
        foreach ($rows as $r) {
            $categories = json_decode($r['categories'], true) ?: [];
            $result[] = [
                'service_type' => $r['service_type'],
                'categories'   => $categories,
            ];
        }
        json_ok(['requirements' => $result]);
        break;

    case 'save':
        csrf_verify_or_fail();
        // POST: save full requirements for a service type (replace all)
        $serviceType = trim($_POST['service_type'] ?? '');
        $categoriesRaw = $_POST['categories'] ?? '[]';

        if (!$serviceType) {
            json_error('Service type is required.', 400);
        }
        $decoded = json_decode($categoriesRaw, true);
        if ($decoded === null) {
            json_error('Invalid JSON for categories.', 400);
        }

        // Upsert
        $stmt = $db->prepare("REPLACE INTO requirements (service_type, categories) VALUES (?, ?)");
        $stmt->execute([$serviceType, $categoriesRaw]);

        log_activity(null, $adminId, 'requirements_updated',
            "Admin updated requirements for service type: {$serviceType}");

        json_ok(['message' => 'Requirements saved successfully.']);
        break;

    case 'delete':
        csrf_verify_or_fail();
        // POST: delete all requirements for a service type
        $serviceType = trim($_POST['service_type'] ?? '');

        if (!$serviceType) {
            json_error('Service type is required.', 400);
        }

        $stmt = $db->prepare("DELETE FROM requirements WHERE service_type = ?");
        $stmt->execute([$serviceType]);

        log_activity(null, $adminId, 'requirements_deleted',
            "Admin deleted requirements for service type: {$serviceType}");

        json_ok(['message' => 'Requirements deleted.']);
        break;

    default:
        json_error('Invalid action.', 400);
}
