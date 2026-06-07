<?php
/**
 * Lotoks — Helper Functions
 *
 * Global utility functions used across the application.
 * Loaded by init.php.
 */

// ─────────────────────────────────────────────────────────────────────
//  EMAIL SENDING
// ─────────────────────────────────────────────────────────────────────

/**
 * Send an email using PHPMailer.
 *
 * @param string $to       Recipient email address
 * @param string $subject  Email subject line
 * @param string $htmlBody HTML body content (will be wrapped in template)
 * @param string $altBody  Plain-text fallback (auto-generated if empty)
 * @return array{success: bool, message: string}
 */
function sendEmail(string $to, string $subject, string $htmlBody, string $altBody = ''): array
{
    // ── Check feature flag ──────────────────────────────────────────
    if (!defined('EMAIL_ENABLED') || !EMAIL_ENABLED) {
        // Log to file instead of actually sending
        $logDir  = dirname(__DIR__) . '/logs';
        if (!is_dir($logDir)) { mkdir($logDir, 0755, true); }
        $logFile = $logDir . '/email.log';

        $entry = sprintf(
            "[%s] TO: %s | SUBJECT: %s | BODY (truncated): %s\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            mb_substr(strip_tags($htmlBody), 0, 200)
        );
        file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);

        return [
            'success' => true,
            'message' => 'Email logged (sending disabled).',
        ];
    }

    // ── Send via PHPMailer ──────────────────────────────────────────
    try {
        // Load PHPMailer (must be installed in includes/phpmailer/)
        $pmDir = __DIR__ . '/phpmailer';
        if (!file_exists($pmDir . '/src/PHPMailer.php')) {
            throw new RuntimeException('PHPMailer not found in ' . $pmDir);
        }

        require_once $pmDir . '/src/PHPMailer.php';
        require_once $pmDir . '/src/SMTP.php';
        require_once $pmDir . '/src/Exception.php';

        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = SMTP_ENCRYPT; // 'ssl' or 'tls'
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $mail->addAddress($to);
        $mail->CharSet = 'UTF-8';

        // Build HTML body with template
        $fullHtml = buildEmailTemplate($htmlBody);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $fullHtml;

        if (empty($altBody)) {
            $altBody = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>'], "\n", $htmlBody));
        }
        $mail->AltBody = $altBody;

        $mail->send();
        return ['success' => true, 'message' => 'Email sent successfully.'];

    } catch (Throwable $e) {
        $error = $e->getMessage();
        error_log("[Lotoks Email Error] $error");
        return ['success' => false, 'message' => $error];
    }
}

/**
 * Wrap HTML content in the standard email template.
 *
 * @param string $content The inner HTML for the email body
 * @return string Full HTML email with header/footer
 */
function buildEmailTemplate(string $content): string
{
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Lotoks';
    $siteUrl  = defined('SITE_URL') ? SITE_URL : 'https://www.lotoks.co.za';
    $year     = date('Y');

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif; }
        .wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background-color: #0B1D3A; padding: 30px 20px; text-align: center; }
        .header h1 { color: #C9A44B; margin: 0; font-size: 24px; }
        .body { padding: 30px 20px; color: #333333; font-size: 15px; line-height: 1.6; }
        .footer { background-color: #f8f8f8; padding: 20px; text-align: center; color: #888888; font-size: 12px; }
        .btn { display: inline-block; padding: 12px 28px; background-color: #C9A44B; color: #0B1D3A; text-decoration: none; border-radius: 4px; font-weight: bold; }
        a { color: #C9A44B; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>$siteName</h1>
        </div>
        <div class="body">
            $content
        </div>
        <div class="footer">
            <p>&copy; $year $siteName. All rights reserved.</p>
            <p><a href="$siteUrl">$siteUrl</a></p>
        </div>
    </div>
</body>
</html>
HTML;
}

// ─────────────────────────────────────────────────────────────────────
//  RATE LIMITING & ACCOUNT LOCKOUT
// ─────────────────────────────────────────────────────────────────────
//  ┌─────────────────────────────────────────────────────────────────┐
//  │ RATE LIMITING = per-IP (prevents brute-force from one address)  │
//  │ ACCOUNT LOCKOUT = per-account (prevents password guessing)      │
//  │ Both are applied independently.                                 │
//  └─────────────────────────────────────────────────────────────────┘

/**
 * Clean expired login_attempts rows (called internally).
 * Keeps the table from growing unbounded.
 */
function cleanExpiredAttempts(PDO $db, int $windowSec): void
{
    $stmt = $db->prepare("DELETE FROM login_attempts WHERE attempted_at < DATE_SUB(NOW(), INTERVAL :window SECOND)");
    $stmt->execute([':window' => $windowSec]);
}

/**
 * Check if an action is rate-limited (by IP).
 *
 * Uses the `login_attempts` table.
 *
 * @param string $actionType  e.g. 'login_user', 'login_admin'
 * @param int    $maxAttempts Max attempts allowed in the window
 * @param int    $windowSec   Window duration in seconds
 * @return bool  true if rate-limited, false if allowed
 */
function isRateLimited(string $actionType, int $maxAttempts = 5, int $windowSec = 900): bool
{
    $ip = getClientIP();

    try {
        $db = getDb();

        // Prune old entries (runs on every check to keep table lean)
        cleanExpiredAttempts($db, $windowSec);

        // Count recent failed attempts from this IP
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM login_attempts
             WHERE ip_address = :ip AND action_type = :action AND success = 0
             AND attempted_at > DATE_SUB(NOW(), INTERVAL :window SECOND)"
        );
        $stmt->execute([
            ':ip'     => $ip,
            ':action' => $actionType,
            ':window' => $windowSec,
        ]);

        return (int) $stmt->fetchColumn() >= $maxAttempts;
    } catch (Throwable $e) {
        error_log("[Lotoks Rate Limit] " . $e->getMessage());
        return false; // Fail open if DB is down
    }
}

/**
 * Record an attempt (successful or failed), with optional email
 * for per-account lockout tracking.
 *
 * @param string $actionType e.g. 'login_user', 'login_admin'
 * @param bool   $success    Whether the attempt was successful
 * @param string $email      Email used in the attempt (for account lockout)
 */
function recordAttempt(string $actionType, bool $success = false, string $email = ''): void
{
    $ip = getClientIP();

    try {
        $db = getDb();

        // ── 1. Always record in login_attempts (for IP rate limiting) ──
        $stmt = $db->prepare(
            "INSERT INTO login_attempts (ip_address, action_type, success, email, attempted_at)
             VALUES (:ip, :action, :success, :email, NOW())"
        );
        $stmt->execute([
            ':ip'      => $ip,
            ':action'  => $actionType,
            ':success' => $success ? 1 : 0,
            ':email'   => $email ?: null,
        ]);

        // ── 2. Per-account lockout tracking (only for failed attempts) ──
        if (!$success && $email !== '') {
            $table = ($actionType === 'login_admin') ? 'admins' : 'users';

            // Increment login_attempts counter, update last_failed_login
            $db->prepare(
                "UPDATE {$table}
                 SET login_attempts = login_attempts + 1,
                     last_failed_login = NOW()
                 WHERE email = ?"
            )->execute([$email]);

            // If this pushes them over the limit, lock the account
            $MAX_ATTEMPTS = defined('ACCOUNT_LOCKOUT_ATTEMPTS') ? ACCOUNT_LOCKOUT_ATTEMPTS : 5;
            $LOCK_MINUTES = defined('ACCOUNT_LOCKOUT_MINUTES')  ? ACCOUNT_LOCKOUT_MINUTES  : 30;

            $db->prepare(
                "UPDATE {$table}
                 SET locked_until = DATE_ADD(NOW(), INTERVAL {$LOCK_MINUTES} MINUTE)
                 WHERE email = ? AND login_attempts >= {$MAX_ATTEMPTS}
                 AND (locked_until IS NULL OR locked_until <= NOW())"
            )->execute([$email]);
        }
    } catch (Throwable $e) {
        error_log("[Lotoks Rate Limit] " . $e->getMessage());
    }
}

/**
 * Get remaining attempts before IP rate limit kicks in.
 *
 * @param string $actionType
 * @return int Remaining attempts (0 if rate-limited)
 */
function getRemainingAttempts(string $actionType): int
{
    $max    = defined('RATE_LIMIT_ATTEMPTS') ? RATE_LIMIT_ATTEMPTS : 5;
    $window = defined('RATE_LIMIT_WINDOW')   ? RATE_LIMIT_WINDOW   : 900;

    $ip = getClientIP();

    try {
        $db = getDb();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM login_attempts
             WHERE ip_address = :ip AND action_type = :action AND success = 0
             AND attempted_at > DATE_SUB(NOW(), INTERVAL :window SECOND)"
        );
        $stmt->execute([
            ':ip'     => $ip,
            ':action' => $actionType,
            ':window' => $window,
        ]);
        $count = (int) $stmt->fetchColumn();
        return max(0, $max - $count);
    } catch (Throwable $e) {
        return $max;
    }
}

// ─────────────────────────────────────────────────────────────────────
//  ACCOUNT LOCKOUT (per-account)
// ─────────────────────────────────────────────────────────────────────

/**
 * Check if an account is currently locked.
 *
 * @param string $email      The account email
 * @param string $actionType 'login_user' or 'login_admin'
 * @return array{locked: bool, remaining_minutes: int, message: string}
 */
function checkAccountLocked(string $email, string $actionType): array
{
    $table = ($actionType === 'login_admin') ? 'admins' : 'users';

    try {
        $db = getDb();
        $stmt = $db->prepare(
            "SELECT login_attempts, locked_until FROM {$table} WHERE email = ?"
        );
        $stmt->execute([$email]);
        $record = $stmt->fetch();

        if (!$record) {
            // No account found — not locked (let caller handle "invalid credentials")
            return ['locked' => false, 'remaining_minutes' => 0, 'message' => ''];
        }

        $lockedUntil = $record['locked_until'] ?? null;

        if ($lockedUntil !== null) {
            $now = new DateTime();
            $lockTime = new DateTime($lockedUntil);

            if ($now < $lockTime) {
                $remaining = (int) ceil(max(0, $now->diff($lockTime)->i + ($now->diff($lockTime)->h * 60)));
                return [
                    'locked'            => true,
                    'remaining_minutes' => $remaining,
                    'message'           => "Account temporarily locked. Too many failed login attempts. Please try again in {$remaining} minute(s).",
                ];
            }

            // Lock period has expired — auto-clear it
            clearAccountLock($email, $actionType);
        }

        return ['locked' => false, 'remaining_minutes' => 0, 'message' => ''];
    } catch (Throwable $e) {
        error_log("[Lotoks Account Lock] " . $e->getMessage());
        return ['locked' => false, 'remaining_minutes' => 0, 'message' => ''];
    }
}

/**
 * Clear lockout and reset login_attempts on successful login.
 *
 * @param string $email
 * @param string $actionType 'login_user' or 'login_admin'
 */
function clearAccountLock(string $email, string $actionType): void
{
    $table = ($actionType === 'login_admin') ? 'admins' : 'users';

    try {
        $db = getDb();
        $db->prepare(
            "UPDATE {$table}
             SET login_attempts = 0,
                 locked_until   = NULL,
                 last_failed_login = NULL
             WHERE email = ?"
        )->execute([$email]);
    } catch (Throwable $e) {
        error_log("[Lotoks Account Lock] " . $e->getMessage());
    }
}

// ─────────────────────────────────────────────────────────────────────
//  FILE UPLOAD HELPERS
// ─────────────────────────────────────────────────────────────────────

/**
 * Allowed MIME types for document uploads.
 */
const ALLOWED_MIME_TYPES = [
    'application/pdf',
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];

/**
 * Allowed file extensions.
 */
const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'doc', 'docx'];

/**
 * Validate an uploaded file's type.
 *
 * @param array $file An element from $_FILES
 * @return array{valid: bool, error: string}
 */
function validateUploadedFile(array $file): array
{
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server size limit.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form size limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        ];
        return ['valid' => false, 'error' => $messages[$file['error']] ?? 'Unknown upload error.'];
    }

    // Check file size
    $maxSize = defined('MAX_UPLOAD_SIZE') ? MAX_UPLOAD_SIZE : 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return ['valid' => false, 'error' => 'File exceeds the maximum allowed size (' . ($maxSize / 1024 / 1024) . ' MB).'];
    }

    // Check MIME type
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, ALLOWED_MIME_TYPES, true)) {
        return ['valid' => false, 'error' => 'File type "' . $mime . '" is not allowed.'];
    }

    // Check extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
        return ['valid' => false, 'error' => 'File extension "' . $ext . '" is not allowed.'];
    }

    return ['valid' => true, 'error' => ''];
}

/**
 * Get a secure file path inside the uploads directory.
 * Uploads are organized as: uploads/{type}/{YYYY-MM}/{random_name}.{ext}
 *
 * @param array  $file An element from $_FILES
 * @param string $type Subdirectory (e.g. 'documents', 'avatars')
 * @return array{path: string, url: string} Internal path + web URL
 */
function getUploadPath(array $file, string $type = 'documents'): array
{
    $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $ym      = date('Y-m');
    $hash    = bin2hex(random_bytes(16));
    $subDir  = $type . '/' . $ym;

    $fullDir = dirname(__DIR__) . '/uploads/' . $subDir;
    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0755, true);
    }

    $filename = $hash . '.' . $ext;
    $internal = $fullDir . '/' . $filename;
    $webUrl   = BASE . '/uploads/' . $subDir . '/' . $filename;

    return ['path' => $internal, 'url' => $webUrl];
}

// ─────────────────────────────────────────────────────────────────────
//  MISC HELPERS
// ─────────────────────────────────────────────────────────────────────

/**
 * Get a human-readable file size string.
 */
function formatFileSize(int $bytes): string
{
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    }
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    }
    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' bytes';
}

/**
 * Get the client IP address, respecting proxies.
 */
function getClientIP(): string
{
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    }
    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

/**
 * Generate a cryptographically secure random token.
 */
function generateToken(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

// ─────────────────────────────────────────────────────────────────────
//  COUNTRY DROPDOWN
// ─────────────────────────────────────────────────────────────────────

/**
 * Get the list of all countries as an associative array (code => name).
 * Used by countryDropdown() and for JS injection.
 */
function countryList(): array
{
    return include __DIR__ . '/countries.php';
}

/**
 * Generate a <select> dropdown of all world countries.
 *
 * @param string $name     The 'name' attribute for the <select>.
 * @param string $selected Currently-selected country (name or code).
 * @param array  $attrs    Optional attributes: id, class, required (bool), placeholder.
 * @return string          HTML string of the <select> element.
 *
 * Usage:
 *   <?= countryDropdown('country', $user['country'] ?? '', ['id' => 'country', 'class' => 'form-input', 'required' => true]) ?>
 */
function countryDropdown(string $name = 'country', string $selected = '', array $attrs = []): string
{
    $countries = countryList();
    $id        = !empty($attrs['id']) ? $attrs['id'] : $name;
    $class     = !empty($attrs['class']) ? $attrs['class'] : 'form-input form-select';
    $required  = !empty($attrs['required']) ? ' required' : '';
    $placeholder = $attrs['placeholder'] ?? 'Select Country';

    $html = '<select name="' . htmlspecialchars($name) . '"'
          . ' id="' . htmlspecialchars($id) . '"'
          . ' class="' . htmlspecialchars($class) . '"'
          . $required . '>';

    $html .= '<option value="">' . htmlspecialchars($placeholder) . '</option>';

    foreach ($countries as $code => $countryName) {
        $isSelected = ($selected === $countryName || $selected === $code) ? ' selected' : '';
        $html .= '<option value="' . htmlspecialchars($countryName) . '"' . $isSelected . '>'
               . htmlspecialchars($countryName) . '</option>';
    }

    $html .= '</select>';
    return $html;
}
