<?php
/**
 * Lotoks — Delete Document API
 * POST /api/user/documents/delete.php
 * Body: { id: N } or POST form: id=N
 */
require_once dirname(__DIR__, 3) . '/includes/auth.php';
require_once dirname(__DIR__, 3) . '/db/connect.php';

// Must be logged in
if (!is_user_logged_in()) {
    json_error('Unauthorized', 401);
}

csrf_verify_or_fail();

$user = get_user();
$userId = (int)$user['id'];

// Get document ID from JSON body or POST
$data = get_json_body();
$docId = (int)($data['id'] ?? $_POST['id'] ?? 0);

if ($docId <= 0) {
    json_error('Document ID is required', 400);
}

$db = getDb();

// Fetch document to verify ownership
$stmt = $db->prepare("SELECT id, filepath, user_id FROM user_documents WHERE id = ?");
$stmt->execute([$docId]);
$doc = $stmt->fetch();

if (!$doc) {
    json_error('Document not found', 404);
}

// Verify the document belongs to the current user
if ((int)$doc['user_id'] !== $userId) {
    json_error('Forbidden', 403);
}

// Delete the physical file
$filePath = dirname(__DIR__, 3) . $doc['filepath'];
if (file_exists($filePath)) {
    unlink($filePath);
}

// Delete the database record
$stmt = $db->prepare("DELETE FROM user_documents WHERE id = ? AND user_id = ?");
$stmt->execute([$docId, $userId]);

json_ok(['message' => 'Document deleted successfully']);
