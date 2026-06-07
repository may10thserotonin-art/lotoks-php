<?php
/**
 * admin/api/save-requirements.php
 * Saves per-application requirements (as JSON) for a given application.
 * POST params: application_id, requirements (JSON array of {label, status})
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

csrf_verify_or_fail();

$db                  = getDb();
$application_id      = (int)($_POST['application_id'] ?? 0);
$requirements_json   = $_POST['requirements'] ?? '[]';

if (!$application_id) {
    json_error('Application ID is required.', 400);
}

// Validate JSON
$decoded = json_decode($requirements_json, true);
if ($decoded === null) {
    json_error('Invalid JSON for requirements.', 400);
}

$stmt = $db->prepare("UPDATE applications SET requirements = ? WHERE id = ?");
$stmt->execute([$requirements_json, $application_id]);

log_activity(null, get_admin()['id'], 'requirements_updated', "Admin updated requirements for application #{$application_id}");

json_ok(['message' => 'Requirements saved.']);
