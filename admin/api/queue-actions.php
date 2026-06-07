<?php
/**
 * admin/api/queue-actions.php
 * Handles application status updates and deletion.
 * Called via form POST from the application detail modal (admin.js).
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

// CSRF verification
csrf_verify_or_fail();

$admin   = get_admin();
$adminId = $admin['id'];

$id     = (int)($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';
$notes  = trim($_POST['admin_notes'] ?? '');

if (!$id || !in_array($action, ['approved', 'rejected', 'more_info', 'delete'], true)) {
    flash('error', 'Invalid request.');
    redirect('/admin/applications.php');
}

$db = getDb();

// Fetch current application
$stmt = $db->prepare("SELECT * FROM applications WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch();

if (!$app) {
    flash('error', 'Application not found.');
    redirect('/admin/applications.php');
}

$userName = htmlspecialchars($app['applicant_name']);
$appType  = htmlspecialchars($app['sponsorship_type']);

if ($action === 'delete') {
    // Delete the application permanently
    $del = $db->prepare("DELETE FROM applications WHERE id = ?");
    $del->execute([$id]);

    log_activity(
        null,
        $adminId,
        'application_deleted',
        "Admin deleted application #{$id} ({$userName} - {$appType})"
    );

    flash('success', "Application #{$id} ({$userName}) has been deleted.");
    redirect('/admin/applications.php');
}

// Status update actions
$validActions = [
    'approved'   => 'approved',
    'rejected'   => 'rejected',
    'more_info'  => 'more_info',
];

$newStatus = $validActions[$action];
$oldStatus = $app['status'];

// Update application status and admin notes
$update = $db->prepare("UPDATE applications SET status = ?, admin_notes = ?, reviewed_by = ?, reviewed_at = NOW(), updated_at = NOW() WHERE id = ?");
$update->execute([$newStatus, $notes, $adminId, $id]);

// Log the action
$actionLabel = [
    'approved'  => 'Application Approved',
    'rejected'  => 'Application Rejected',
    'more_info' => 'More Info Requested',
];

$desc = match ($action) {
    'approved'  => "Admin approved application #{$id} ({$userName} - {$appType})",
    'rejected'  => "Admin rejected application #{$id} ({$userName} - {$appType})",
    'more_info' => "Admin requested more information for application #{$id} ({$userName} - {$appType})",
    default => "Admin updated application #{$id} status to {$newStatus}",
};

log_activity(null, $adminId, $actionLabel[$action] ?? 'application_updated', $desc);

// ── Send notification email to the applicant ─────────────────────
$applicantEmail = $app['email'];
if ($applicantEmail) {
    $statusLabels = [
        'approved'  => 'Approved',
        'rejected'  => 'Not Approved',
        'more_info' => 'More Information Needed',
    ];
    $actionSubject = $statusLabels[$action] ?? ucfirst($action);

    $subject = "Application {$actionSubject} — Lotoks";

    // Build a clear, human-friendly message
    $messageLines = [
        'approved'  => "Great news! Your application for <strong>" . htmlspecialchars($app['sponsorship_type']) . "</strong> has been reviewed and <strong>approved</strong>.",
        'rejected'  => "Thank you for your interest. After careful review, your application for <strong>" . htmlspecialchars($app['sponsorship_type']) . "</strong> has been <strong>not approved</strong> at this time.",
        'more_info' => "We need some additional information to continue processing your application for <strong>" . htmlspecialchars($app['sponsorship_type']) . "</strong>. Please log in to your dashboard to review what's needed.",
    ];
    $message = $messageLines[$action] ?? "Your application status has been updated to: <strong>" . ucfirst($action) . "</strong>.";

    // Append admin notes if present
    if ($notes !== '') {
        $message .= "<br><br><strong>Admin notes:</strong><br>" . nl2br(htmlspecialchars($notes));
    }

    $bodyHTML = (require dirname(__DIR__, 2) . '/includes/emails/notification.php')(
        $subject,
        $message,
        SITE_URL . BASE . '/dashboard.php',
        'View Dashboard'
    );

    sendEmail($applicantEmail, $subject, $bodyHTML);
}

flash(
    'success',
    "Application #{$id} ({$userName}) status changed to: <strong>" . str_replace('_', ' ', $newStatus) . '</strong>.'
);

// Redirect back to the referer (applications.php) or fallback
$redirect = $_SERVER['HTTP_REFERER'] ?? BASE . '/admin/applications.php';
header("Location: {$redirect}");
exit;
