<?php
/**
 * admin/api/document-verify.php
 * Tags a document with verification status and category.
 * POST params: id, verified (1|0|-1), category
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

csrf_verify_or_fail();

$db      = getDb();
$docId   = (int)($_POST['id'] ?? 0);
$verified = $_POST['verified'] ?? null;
$category = trim($_POST['category'] ?? '');

if (!$docId) {
    json_error('Document ID is required.', 400);
}

// Validate verified value
if ($verified !== null) {
    $verified = (int)$verified;
    if (!in_array($verified, [-1, 0, 1], true)) {
        $verified = 0;
    }
}

// Build update
$fields = [];
$params = [];
if ($verified !== null) {
    $fields[] = 'verified = ?';
    $params[] = $verified;
}
if ($category !== '') {
    $fields[] = 'category = ?';
    $params[] = $category;
}

if (empty($fields)) {
    json_error('Nothing to update.', 400);
}

$params[] = $docId;
$stmt = $db->prepare("UPDATE user_documents SET " . implode(', ', $fields) . " WHERE id = ?");
$stmt->execute($params);

log_activity(null, get_admin()['id'], 'document_verified', "Admin tagged document #{$docId}: verified={$verified}, category={$category}");

json_ok(['message' => 'Document updated.']);
