<?php
/**
 * api/user/settings.php
 * User Settings AJAX endpoint.
 *
 * Actions:
 *   action=update        — Update profile (name, country)
 *   action=notifications — Save notification preferences
 *   action=password      — Change password
 */
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../db/connect.php';

// Must be logged in
if (!is_user_logged_in()) {
    json_error('Authentication required.', 401);
}

$user   = getCurrentUser();
$userId = (int)($user['id'] ?? 0);
$db     = getDb();
$action = $_POST['action'] ?? '';

try {
    switch ($action) {

        // ── Update Profile ────────────────────────────────────
        case 'update':
            $name    = trim($_POST['name'] ?? '');
            $country = trim($_POST['country'] ?? '');

            if (empty($name)) {
                json_error('Name is required.');
            }

            $stmt = $db->prepare("UPDATE users SET name = ?, country = ? WHERE id = ?");
            $stmt->execute([$name, $country, $userId]);

            // Update session
            $_SESSION['user']['name']    = $name;
            $_SESSION['user']['country'] = $country;

            log_activity($userId, null, 'profile_updated', "User updated their profile");
            json_ok(['message' => 'Profile updated successfully.']);
            break;

        // ── Save Notification Preferences ─────────────────────
        case 'notifications':
            $fields = ['email_notifications', 'sms_notifications', 'application_updates', 'marketing_emails'];

            // Build param values
            $vals = [];
            foreach ($fields as $f) {
                $vals[] = !empty($_POST[$f]) ? 1 : 0;
            }

            // Build column lists
            $cols   = implode(', ', $fields);
            $placeholders = implode(', ', array_fill(0, count($fields), '?'));

            // Build SET clause for the UPDATE part (MariaDB-safe, no VALUES())
            $updates = implode(', ', array_map(fn($f) => "$f = ?", $fields));

            $stmt = $db->prepare(
                "INSERT INTO user_settings (user_id, $cols) VALUES (?, $placeholders)
                 ON DUPLICATE KEY UPDATE $updates"
            );
            // Params: [userId, val1, val2, val3, val4, val1, val2, val3, val4]
            //          \__ INSERT VALUES ___/  \__ UPDATE SET ___________/
            $stmt->execute(array_merge([$userId], array_slice($vals, 0, count($fields)), array_slice($vals, 0, count($fields))));

            log_activity($userId, null, 'notifications_updated', "User updated notification preferences");
            json_ok(['message' => 'Preferences saved successfully.']);
            break;

        // ── Change Password ───────────────────────────────────
        case 'password':
            $current     = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            if (empty($current) || empty($newPassword)) {
                json_error('All password fields are required.');
            }
            if (strlen($newPassword) < 8) {
                json_error('New password must be at least 8 characters.');
            }

            // Verify current password
            $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $row = $stmt->fetch();

            if (!$row || !verify_password($current, $row['password_hash'])) {
                json_error('Current password is incorrect.');
            }

            $hash = hash_password($newPassword);
            $stmt = $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$hash, $userId]);

            log_activity($userId, null, 'password_changed', "User changed their password");
            json_ok(['message' => 'Password changed successfully.']);
            break;

        default:
            json_error('Invalid action.');
    }
} catch (Exception $e) {
    json_error('Server error: ' . $e->getMessage(), 500);
}
