<?php
/**
 * Lotoks — Newsletter Subscription API
 *
 * POST-only endpoint for subscribing to the newsletter.
 * Uses double opt-in: stores subscriber with pending status,
 * then sends a confirmation email.
 *
 * POST /api/newsletter-subscribe.php
 *   email: string (required)
 *
 * Returns JSON: { success: bool, message: string }
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

// Verify CSRF for same-origin requests
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // AJAX requests from the site will include the CSRF token in the POST body
    if (empty($_POST['csrf_token']) || !csrf_verify($_POST['csrf_token'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh the page.']);
        exit;
    }
} else {
    csrf_verify_or_fail();
}

$email = strtolower(trim($_POST['email'] ?? ''));

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $db = getDb();

    // Check if already subscribed
    $stmt = $db->prepare("SELECT status FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    $existing = $stmt->fetch();

    if ($existing) {
        if ($existing['status'] === 'active') {
            echo json_encode(['success' => true, 'message' => 'You are already subscribed!']);
            exit;
        }
        if ($existing['status'] === 'pending') {
            // Re-send confirmation
            $token = bin2hex(random_bytes(32));
            $db->prepare("UPDATE newsletter_subscribers SET confirm_token = ?, updated_at = NOW() WHERE email = ?")
               ->execute([$token, $email]);

            $confirmLink = SITE_URL . BASE . '/api/newsletter-confirm.php?token=' . urlencode($token);
            $subject     = 'Confirm Your Subscription — Lotoks';
            $bodyHTML    = (require __DIR__ . '/../includes/emails/newsletter-confirm.php')($confirmLink);
            sendEmail($email, $subject, $bodyHTML);

            echo json_encode(['success' => true, 'message' => 'Confirmation email re-sent. Please check your inbox.']);
            exit;
        }
        // Unsubscribed — re-subscribe
        $token = bin2hex(random_bytes(32));
        $db->prepare("UPDATE newsletter_subscribers SET status = 'pending', confirm_token = ?, updated_at = NOW() WHERE email = ?")
           ->execute([$token, $email]);

        $confirmLink = SITE_URL . BASE . '/api/newsletter-confirm.php?token=' . urlencode($token);
        $subject     = 'Confirm Your Subscription — Lotoks';
        $bodyHTML    = (require __DIR__ . '/../includes/emails/newsletter-confirm.php')($confirmLink);
        sendEmail($email, $subject, $bodyHTML);

        echo json_encode(['success' => true, 'message' => 'Confirmation email sent. Please check your inbox.']);
        exit;
    }

    // New subscriber
    $token = bin2hex(random_bytes(32));
    $stmt = $db->prepare("INSERT INTO newsletter_subscribers (email, status, confirm_token, created_at, updated_at) VALUES (?, 'pending', ?, NOW(), NOW())");
    $stmt->execute([$email, $token]);

    // Send confirmation email
    $confirmLink = SITE_URL . BASE . '/api/newsletter-confirm.php?token=' . urlencode($token);
    $subject     = 'Confirm Your Subscription — Lotoks';
    $bodyHTML    = (require __DIR__ . '/../includes/emails/newsletter-confirm.php')($confirmLink);
    sendEmail($email, $subject, $bodyHTML);

    echo json_encode(['success' => true, 'message' => 'Confirmation email sent. Please check your inbox.']);

} catch (Throwable $e) {
    error_log("[Lotoks Newsletter] " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again later.']);
}
