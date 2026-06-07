<?php
/**
 * admin/api/view-user.php
 * AJAX endpoint — returns user details with their applications and documents.
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    json_error('Invalid user ID', 400);
}

$db = getDb();

// Fetch user
$stmt = $db->prepare("SELECT id, name, email, country, verified, suspended, created_at FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    json_error('User not found', 404);
}

// Fetch user applications
$appStmt = $db->prepare("SELECT id, sponsorship_type, status, created_at, updated_at FROM applications WHERE user_id = ? ORDER BY created_at DESC");
$appStmt->execute([$id]);
$apps = $appStmt->fetchAll();

// Fetch user documents
$docStmt = $db->prepare("SELECT id, name, filename, filepath, filesize, mime_type, category, created_at FROM user_documents WHERE user_id = ? ORDER BY created_at DESC");
$docStmt->execute([$id]);
$docs = $docStmt->fetchAll();

// Fetch activity log for this user
$logStmt = $db->prepare("SELECT action, description, created_at FROM activity_log WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
$logStmt->execute([$id]);
$logs = $logStmt->fetchAll();

json_ok([
    'user' => [
        'id'        => (int)$user['id'],
        'name'      => $user['name'],
        'email'     => $user['email'],
        'country'   => $user['country'] ?: '',
        'verified'  => (bool)$user['verified'],
        'suspended' => (bool)($user['suspended'] ?? false),
        'created_at' => $user['created_at'],
    ],
    'applications' => array_map(function ($a) {
        return [
            'id'               => (int)$a['id'],
            'sponsorship_type' => $a['sponsorship_type'],
            'status'           => $a['status'],
            'created_at'       => $a['created_at'],
            'updated_at'       => $a['updated_at'],
        ];
    }, $apps),
    'documents' => array_map(function ($d) {
        return [
            'id'        => (int)$d['id'],
            'name'      => $d['name'],
            'filepath'  => $d['filepath'],
            'filesize'  => (int)$d['filesize'],
            'category'  => $d['category'],
            'created_at' => $d['created_at'],
        ];
    }, $docs),
    'activity' => array_map(function ($l) {
        return [
            'action'      => $l['action'],
            'description' => $l['description'],
            'created_at'  => $l['created_at'],
        ];
    }, $logs),
]);
