<?php
/**
 * admin/api/view-application.php
 * AJAX endpoint — returns full application details as JSON.
 * Called by admin.js's viewApplication(id) function.
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    json_error('Invalid application ID', 400);
}

$db = getDb();

// Fetch application with user info
$stmt = $db->prepare("
    SELECT a.*, u.name as applicant_name, u.email, u.country,
           (SELECT COUNT(*) FROM user_documents WHERE user_id = a.user_id) as document_count
    FROM applications a
    LEFT JOIN users u ON a.user_id = u.id
    WHERE a.id = ?
");
$stmt->execute([$id]);
$app = $stmt->fetch();

if (!$app) {
    json_error('Application not found', 404);
}

// Decode JSON fields
$personalInfo = json_decode($app['personal_info'], true) ?: [];
$answers      = json_decode($app['answers'], true) ?: [];
$serviceTypes = json_decode($app['service_types'], true) ?: [];
$documents    = json_decode($app['documents'], true) ?: [];

// Fetch actual uploaded documents for this user
$docStmt = $db->prepare("SELECT id, name, filename, filepath, filesize, mime_type, category, verified FROM user_documents WHERE user_id = ? ORDER BY created_at DESC");
$docStmt->execute([$app['user_id']]);
$uploadedDocs = $docStmt->fetchAll();

// Format documents
$docList = [];
foreach ($uploadedDocs as $d) {
    $docList[] = [
        'id'       => (int)$d['id'],
        'name'     => $d['name'],
        'filename' => $d['filename'],
        'filepath' => $d['filepath'],
        'filesize' => (int)$d['filesize'],
        'mime'     => $d['mime_type'],
        'category' => $d['category'] ?? '',
        'verified' => (int)($d['verified'] ?? 0),
    ];
}

json_ok([
    'application' => [
        'id'               => (int)$app['id'],
        'user_id'          => (int)$app['user_id'],
        'applicant_name'   => $app['applicant_name'] ?: $app['name'],
        'email'            => $app['email'],
        'country'          => $app['country'] ?: '',
        'sponsorship_type' => $app['sponsorship_type'],
        'service_types'    => $serviceTypes,
        'status'           => $app['status'],
        'admin_notes'      => $app['admin_notes'] ?? '',
        'reviewed_by'      => $app['reviewed_by'] ? (int)$app['reviewed_by'] : null,
        'reviewed_at'      => $app['reviewed_at'] ?: null,
        'personal_info'    => $personalInfo,
        'answers'          => $answers,
        'requirements'     => $app['requirements'] ?? '[]',
        'documents'        => $docList,
        'document_count'   => (int)$app['document_count'],
        'created_at'       => $app['created_at'],
        'updated_at'       => $app['updated_at'],
    ]
]);
