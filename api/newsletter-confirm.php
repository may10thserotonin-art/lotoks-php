<?php
/**
 * Lotoks — Newsletter Confirmation Endpoint
 *
 * Handles double opt-in: when a subscriber clicks the confirmation link,
 * this script marks their subscription as active.
 *
 * GET /api/newsletter-confirm.php?token=xxx
 */

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

$token = trim($_GET['token'] ?? '');

if (!$token) {
    die('Invalid confirmation link.');
}

try {
    $db = getDb();
    $stmt = $db->prepare("SELECT id, email FROM newsletter_subscribers WHERE confirm_token = ? AND status = 'pending'");
    $stmt->execute([$token]);
    $sub = $stmt->fetch();

    if (!$sub) {
        die('Invalid or expired confirmation link. You may already be confirmed.');
    }

    // Activate subscription
    $stmt = $db->prepare("UPDATE newsletter_subscribers SET status = 'active', confirm_token = NULL, confirmed_at = NOW(), updated_at = NOW() WHERE id = ?");
    $stmt->execute([$sub['id']]);

    // Log activity
    log_activity(null, null, 'newsletter_confirm', "Newsletter subscription confirmed for: {$sub['email']}");

} catch (Throwable $e) {
    error_log("[Lotoks Newsletter Confirm] " . $e->getMessage());
    die('An error occurred. Please try again.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subscription Confirmed | Lotoks</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE ?>/public/favicon.svg" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE ?>/public/logo.png" />
  <link rel="apple-touch-icon" href="<?= BASE ?>/public/logo.png" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #0B1D3A;
      color: #fff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .card { text-align: center; max-width: 450px; }
    .icon { width: 64px; height: 64px; border-radius: 50%; background: rgba(201,164,75,0.15); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
    h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
    p { color: rgba(255,255,255,0.5); font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem; }
    .btn { display: inline-block; padding: 0.75rem 2rem; background: #C9A44B; color: #0B1D3A; text-decoration: none; border-radius: 999px; font-weight: 600; }
    .btn:hover { opacity: 0.85; }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">
      <svg width="28" height="28" fill="none" stroke="#C9A44B" stroke-width="2.5" viewBox="0 0 24 24">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
    </div>
    <h1>You're subscribed!</h1>
    <p>Thank you for confirming your subscription. You'll now receive updates, opportunities, and news from Lotoks.</p>
    <a href="<?= defined('BASE') ? BASE : '' ?>/" class="btn">Back to Home</a>
  </div>
</body>
</html>
