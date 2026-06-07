<?php
/**
 * Lotoks — PHP Session Auth Helpers
 * Replaces JWT cookie auth with PHP sessions
 */

require_once __DIR__ . '/config.php';

// Load global helper functions (rate limiting, email, uploads, etc.)
// Needed here so login pages can use isRateLimited()/sendEmail() in POST handlers
require_once __DIR__ . '/functions.php';

// ── Timezone ─────────────────────────────────────────────────
date_default_timezone_set('Africa/Johannesburg');

// ── Secure Session Start ─────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400 * 7,        // 7 days
        'path'     => BASE ?: '/',
        'domain'   => '',
        'secure'   => IS_PRODUCTION,     // HTTPS-only in production
        'httponly'  => true,
        'samesite'  => 'Lax',
    ]);
    session_start();

    // Regenerate session ID periodically to prevent fixation
    if (!isset($_SESSION['_init_time'])) {
        $_SESSION['_init_time'] = time();
    } elseif (time() - $_SESSION['_init_time'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['_init_time'] = time();
    }
}

// ── Security Headers (set on every page, before any output) ──
if (!headers_sent()) {
    $csp = "default-src 'self'; "
         . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com; "
         . "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; "
         . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdnjs.cloudflare.com; "
         . "img-src 'self' data:; "
         . "connect-src 'self'; "
         . "frame-ancestors 'none'; "
         . "form-action 'self';";

    header("Content-Security-Policy: " . $csp);
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");

    if (IS_PRODUCTION) {
        header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
    }

    header("Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()");
}

// ── Error & Exception Handler (Production) ──────────────────
if (IS_PRODUCTION) {
    set_error_handler(function ($severity, $message, $file, $line) {
        error_log("[Lotoks Error] [$severity] $message in $file on line $line");
        return false;
    });

    set_exception_handler(function (Throwable $e) {
        error_log("[Lotoks Exception] " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
            require dirname(__DIR__) . '/500.php';
        }
        exit;
    });
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

/** ── User auth ─────────────────────────────────────────────── **/

function user_login(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'         => $user['id'],
        'name'       => $user['name'],
        'email'      => $user['email'],
        'country'    => $user['country'] ?? '',
        'created_at' => $user['created_at'] ?? null,
    ];
    $_SESSION['user_logged_in'] = true;
}

function user_logout(): void {
    unset($_SESSION['user'], $_SESSION['user_logged_in']);
    session_destroy();
}

function get_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_user_logged_in(): bool {
    return !empty($_SESSION['user_logged_in']) && !empty($_SESSION['user']);
}

/**
 * Redirect to login if not authenticated.
 * Passes the current URL as ?redirect= so we can return after login.
 */
function require_user_auth(): void {
    if (!is_user_logged_in()) {
        $redirect = urlencode($_SERVER['REQUEST_URI']);
        header("Location: " . BASE . "/login.php?redirect={$redirect}");
        exit;
    }
}

/** ── Admin auth ─────────────────────────────────────────────── **/

function admin_login(array $admin): void {
    session_regenerate_id(true);
    $_SESSION['admin'] = [
        'id'    => $admin['id'],
        'name'  => $admin['name'],
        'email' => $admin['email'],
        'role'  => $admin['role'],
    ];
    $_SESSION['admin_logged_in'] = true;
}

function admin_logout(): void {
    unset($_SESSION['admin'], $_SESSION['admin_logged_in']);
    session_destroy();
}

function get_admin(): ?array {
    return $_SESSION['admin'] ?? null;
}

function is_admin_logged_in(): bool {
    return !empty($_SESSION['admin_logged_in']) && !empty($_SESSION['admin']);
}

function is_super_admin(): bool {
    return is_admin_logged_in() && ($_SESSION['admin']['role'] ?? '') === 'super_admin';
}

/**
 * Redirect to admin login if not authenticated.
 */
function require_admin_auth(): void {
    if (!is_admin_logged_in()) {
        header('Location: ' . BASE . '/admin/login.php');
        exit;
    }
}

/**
 * Require super_admin role specifically.
 */
function require_super_admin(): void {
    require_admin_auth();
    if (!is_super_admin()) {
        http_response_code(403);
        require_once dirname(__DIR__) . '/includes/admin_head.php';
        echo '<div style="padding:4rem;text-align:center;color:#fff"><h2>Access Denied</h2><p>Super admin role required.</p></div>';
        require_once dirname(__DIR__) . '/includes/admin_footer.php';
        exit;
    }
}

/** ── CSRF helpers ───────────────────────────────────────────── **/

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_verify(): bool {
    $token = $_POST['_csrf'] ?? $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals(csrf_token(), $token);
}

function csrf_verify_or_fail(): void {
    if (!csrf_verify()) {
        http_response_code(419);
        json_error('CSRF token mismatch', 419);
    }
}

/** ── JSON response helpers (for API endpoints) ──────────────── **/

function json_ok(array $data = [], int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, ...$data]);
    exit;
}

function json_error(string $message, int $code = 400, array $extra = []): never {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $message, ...$extra]);
    exit;
}

function is_ajax(): bool {
    return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
}

function get_json_body(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}

/** ── Flash messages ─────────────────────────────────────────── **/

function flash(string $type, string $message): void {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flash(): array {
    $msgs = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $msgs;
}

function render_flash(): void {
    foreach (get_flash() as $f) {
        $type    = htmlspecialchars($f['type']);
        $message = htmlspecialchars($f['message']);
        echo "<div class=\"alert alert-{$type}\">{$message}</div>";
    }
}

/** ── Redirect helper ────────────────────────────────────────── **/

function redirect(string $url): never {
    if (str_starts_with($url, '/') && !str_starts_with($url, BASE)) {
        $url = BASE . $url;
    }
    header("Location: {$url}");
    exit;
}

/** ── Redirect logged-in users from public pages ─────────────── **/

/**
 * If a user is already logged in, redirect them away from public/marketing pages.
 * - Logged-in users → /dashboard.php
 * - Logged-in admins → /admin/index.php
 */
function redirect_if_logged_in(): void {
    if (is_user_logged_in()) {
        redirect('/dashboard.php');
    }
    if (is_admin_logged_in()) {
        redirect('/admin/index.php');
    }
}

/** ── Impersonation (super admin only) ───────────────────────── **/

/**
 * Start impersonating a user.
 * Saves the current admin session to a backup key,
 * then logs in as the target user.
 */
function start_impersonation(array $targetUser): void {
    if (!is_super_admin()) {
        return;
    }
    // Back up admin session
    $_SESSION['admin_backup'] = $_SESSION['admin'];
    $_SESSION['admin_logged_in_backup'] = true;
    
    // Log out admin (clear session keys)
    unset($_SESSION['admin'], $_SESSION['admin_logged_in']);
    
    // Log in as target user
    user_login($targetUser);
    
    // Set impersonation flags
    $_SESSION['impersonating'] = true;
    $_SESSION['impersonated_by'] = $_SESSION['admin_backup']['id'];
}

/**
 * Stop impersonating and restore the admin session.
 */
function stop_impersonation(): void {
    if (empty($_SESSION['impersonating']) || empty($_SESSION['admin_backup'])) {
        return;
    }
    
    $backup = $_SESSION['admin_backup'];
    
    // Clear user session
    unset($_SESSION['user'], $_SESSION['user_logged_in']);
    
    // Restore admin
    $_SESSION['admin'] = $backup;
    $_SESSION['admin_logged_in'] = true;
    
    // Clean up impersonation flags
    unset($_SESSION['admin_backup'], $_SESSION['admin_logged_in_backup'],
          $_SESSION['impersonating'], $_SESSION['impersonated_by']);
}

/**
 * Check if the current session is an impersonation.
 */
function is_impersonating(): bool {
    return !empty($_SESSION['impersonating']);
}

/**
 * Get the admin ID who initiated the impersonation.
 */
function get_impersonator_id(): ?int {
    return $_SESSION['impersonated_by'] ?? null;
}

/** ── Password helpers ───────────────────────────────────────── **/

function hash_password(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

function verify_password(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

/** ── Convenience aliases used in portal pages ───────────────── **/

function requireUserAuth(string $loginUrl = null): void {
    if ($loginUrl === null) {
        $loginUrl = BASE . '/login.php';
    } elseif (str_starts_with($loginUrl, '/') && !str_starts_with($loginUrl, BASE)) {
        // Callers pass root-relative paths like '/login.php'.
        // Make them project-relative (e.g. '/lotoks/login.php').
        $loginUrl = BASE . $loginUrl;
    }
    if (!is_user_logged_in()) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '');
        header("Location: {$loginUrl}?redirect={$redirect}");
        exit;
    }
}

function getCurrentUser(): ?array {
    return get_user();
}

function generateCsrfToken(): string {
    return csrf_token();
}

/**
 * Build a Cookie header string from current PHP session cookie.
 * Used when making internal cURL calls to the Express backend
 * that require the user's auth cookie to be forwarded.
 */
function http_build_cookie(): string {
    $parts = [];
    foreach ($_COOKIE as $name => $value) {
        $parts[] = rawurlencode($name) . '=' . rawurlencode($value);
    }
    return implode('; ', $parts);
}

/** ── Activity Logging ───────────────────────────────────────── **/

/**
 * Log an action to the activity_log table.
 */
function log_activity(?int $user_id, ?int $admin_id, string $action, string $description): void {
    global $db;
    if (!isset($db)) {
        require_once dirname(__DIR__) . '/db/connect.php';
        $db = getDb();
    }
    
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    try {
        $stmt = $db->prepare("INSERT INTO activity_log (user_id, admin_id, action, description, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $admin_id, $action, $description, $ip]);
    } catch (Exception $e) {
        // Silently fail logging rather than breaking the app
    }
}


