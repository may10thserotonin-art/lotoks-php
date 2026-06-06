<?php
/**
 * Lotoks — PHP Session Auth Helpers
 * Replaces JWT cookie auth with PHP sessions
 */

require_once __DIR__ . '/config.php';

// Start session once
if (session_status() === PHP_SESSION_NONE) {
    session_name('lotoks_session');
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Strict',
        'cookie_secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
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
    $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
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
    header("Location: {$url}");
    exit;
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

