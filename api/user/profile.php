<?php
/**
 * api/user/profile.php
 * User Profile API — update name, country, or password.
 * 
 * GET    → returns current profile info
 * POST   → updates profile fields (name, country)
 * POST   → with action=password changes password
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

if (!is_user_logged_in()) {
    json_error('Unauthorized', 401);
}

$user = get_user();
$userId = (int)$user['id'];
$db = getDb();
$method = $_SERVER['REQUEST_METHOD'];

/** ─── GET: Return current profile ─────────────────────────── */
if ($method === 'GET') {
    $stmt = $db->prepare("SELECT id, name, email, country, created_at FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $profile = $stmt->fetch();
    if (!$profile) {
        json_error('User not found', 404);
    }
    json_ok(['profile' => $profile]);
}

/** ─── POST: Update profile ────────────────────────────────── */
if ($method === 'POST') {
    csrf_verify_or_fail();
    $action = $_POST['action'] ?? 'update';

    // ── Update name / country ────────────────────────────────
    if ($action === 'update') {
        $name    = trim($_POST['name'] ?? '');
        $country = trim($_POST['country'] ?? '');

        if (empty($name)) {
            json_error('Name is required', 400);
        }

        try {
            $stmt = $db->prepare("UPDATE users SET name = ?, country = ? WHERE id = ?");
            $stmt->execute([$name, $country, $userId]);

            // Update session data too
            $user['name']    = $name;
            $user['country'] = $country;
            $_SESSION['user'] = $user;

            log_activity($userId, null, 'profile_updated', "User updated profile (name, country)");

            json_ok(['message' => 'Profile updated successfully']);
        } catch (Exception $e) {
            json_error('Database error: ' . $e->getMessage(), 500);
        }
    }

    // ── Change password ──────────────────────────────────────
    if ($action === 'password') {
        $current    = $_POST['current_password'] ?? '';
        $new        = $_POST['new_password'] ?? '';
        $confirm    = $_POST['confirm_password'] ?? '';

        if (empty($current) || empty($new) || empty($confirm)) {
            json_error('All password fields are required', 400);
        }

        if ($new !== $confirm) {
            json_error('New passwords do not match', 400);
        }

        if (strlen($new) < 8) {
            json_error('Password must be at least 8 characters', 400);
        }
        if (!preg_match('@[A-Z]@', $new)) {
            json_error('Password must contain an uppercase letter', 400);
        }
        if (!preg_match('@[a-z]@', $new)) {
            json_error('Password must contain a lowercase letter', 400);
        }
        if (!preg_match('@[0-9]@', $new)) {
            json_error('Password must contain a number', 400);
        }

        // Verify current password
        $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();

        if (!$row || !password_verify($current, $row['password_hash'])) {
            json_error('Current password is incorrect', 403);
        }

        // Update password
        $newHash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 10]);
        try {
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$newHash, $userId]);

            log_activity($userId, null, 'password_changed', "User changed password");

            json_ok(['message' => 'Password changed successfully']);
        } catch (Exception $e) {
            json_error('Database error: ' . $e->getMessage(), 500);
        }
    }

    json_error('Unknown action', 400);
}

json_error('Method not allowed', 405);
