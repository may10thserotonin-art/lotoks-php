<?php
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

if (!is_user_logged_in()) {
    json_error('Unauthorized', 401);
}

// CSRF verification
csrf_verify_or_fail();

$user = get_user();
$userId = $user['id'];
$userName = $user['name'];
$userEmail = $user['email'];

$data = get_json_body();

// Basic validation
if (empty($data['sponsorship_type'])) {
    json_error('Sponsorship type is required', 400);
}

$db = getDb();
$stmt = $db->prepare("
    INSERT INTO applications 
    (user_id, applicant_name, email, country, sponsorship_type, service_types, documents, personal_info, answers, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'submitted')
");

try {
    $stmt->execute([
        $userId,
        $userName,
        $userEmail,
        $data['country'] ?? $user['country'] ?? '',
        $data['sponsorship_type'],
        json_encode($data['service_types'] ?? []),
        json_encode($data['documents'] ?? []),
        json_encode($data['personal_info'] ?? []),
        json_encode($data['answers'] ?? [])
    ]);
    
    $appId = $db->lastInsertId();

    // Log application submission
    log_activity($userId, null, 'application_submitted', "User submitted application #{$appId} ({$data['sponsorship_type']})");

    json_ok(['application_id' => $appId]);
} catch (Exception $e) {
    json_error('Database error: ' . $e->getMessage(), 500);
}
