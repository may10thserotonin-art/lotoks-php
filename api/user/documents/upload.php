<?php
require_once dirname(__DIR__, 3) . '/includes/auth.php';
require_once dirname(__DIR__, 3) . '/db/connect.php';

// Must be logged in
if (!is_user_logged_in()) {
    json_error('Unauthorized', 401);
}

csrf_verify_or_fail();

$user = get_user();
$userId = $user['id'];

// Check file
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    json_error('No file uploaded or upload error', 400);
}

$file = $_FILES['file'];
$category = $_POST['category'] ?? 'general';

// Ensure upload directory exists
$uploadDir = dirname(__DIR__, 3) . '/uploads/documents';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
if (!$ext) {
    $ext = 'bin'; // fallback
}
// Clean filename: user_id + timestamp + random + ext
$newFilename = sprintf('%d_%s_%s.%s', $userId, time(), bin2hex(random_bytes(4)), $ext);
$destination = $uploadDir . '/' . $newFilename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    json_error('Failed to save uploaded file', 500);
}

// Save to database
$db = getDb();
$stmt = $db->prepare("
    INSERT INTO user_documents (user_id, name, filename, filepath, filesize, mime_type, category)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$filepath = '/uploads/documents/' . $newFilename;

try {
    $stmt->execute([
        $userId,
        $file['name'],
        $newFilename,
        $filepath,
        $file['size'],
        $file['type'] ?? 'application/octet-stream',
        $category
    ]);
    
    $docId = $db->lastInsertId();

    // Log document upload
    log_activity($userId, null, 'document_uploaded', "User uploaded document: {$file['name']} ({$category})");

    json_ok([
        'document' => [
            'id' => $docId,
            'filename' => $filepath,
            'name' => $file['name']
        ]
    ]);
} catch (Exception $e) {
    // If DB insert fails, we should technically delete the file, but for now just error out
    json_error('Database error: ' . $e->getMessage(), 500);
}
