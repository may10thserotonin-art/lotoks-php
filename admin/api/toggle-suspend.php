<?php
/**
 * admin/api/toggle-suspend.php
 * AJAX endpoint — toggle a user's suspended status.
 * Only accessible by super_admins.
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

csrf_verify_or_fail();

if (!is_super_admin()) {
    json_error('Forbidden — super admin role required', 403);
}

$db = getDb();
$userId = (int)($_POST['user_id'] ?? 0);
$action = $_POST['action'] ?? '';  // 'suspend' or 'unsuspend'

if (!$userId) {
    json_error('Invalid user ID', 400);
}
if (!in_array($action, ['suspend', 'unsuspend'], true)) {
    json_error('Invalid action. Use "suspend" or "unsuspend".', 400);
}

// Check user exists
$stmt = $db->prepare("SELECT id, name, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    json_error('User not found', 404);
}

$newValue = ($action === 'suspend') ? 1 : 0;

try {
    $stmt = $db->prepare("UPDATE users SET suspended = ? WHERE id = ?");
    $stmt->execute([$newValue, $userId]);

    // Log the action
    $logAction = $action === 'suspend' ? 'user_suspended' : 'user_unsuspended';
    $logDesc   = $action === 'suspend'
        ? "Suspended user {$user['name']} (#{$userId})"
        : "Unsuspended user {$user['name']} (#{$userId})";
    log_activity($userId, (int)$_SESSION['admin']['id'], $logAction, $logDesc);

    json_ok([
        'message' => $action === 'suspend' ? 'User has been suspended.' : 'User has been reactivated.',
        'suspended' => (bool)$newValue,
    ]);
} catch (Exception $e) {
    json_error('Database error: ' . $e->getMessage(), 500);
}
