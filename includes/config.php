<?php
/**
 * Lotoks — Central Config
 *
 * Detects whether we are running under a sub-directory (e.g. /lotoks on XAMPP)
 * or at the domain root (production).  All includes that need to build URLs
 * should: require_once __DIR__ . '/config.php';  then use BASE.'/path/to/file'
 *
 * ── Configuration Guide ──────────────────────────────────────────────
 *
 * To enable email after hosting:
 *   1. Set define('EMAIL_ENABLED', true); below (or in .env)
 *   2. Update SMTP settings below with your provider's credentials
 *   3. Ensure PHPMailer autoloader is included in includes/functions.php
 *
 * To enable 2FA:
 *   1. Set define('TWO_FACTOR_ENABLED', true); below
 *   2. Run migration_v2.sql to add columns to admins table
 *   3. Install the PragmaRX/Google2FA library (or use the built-in approach)
 *
 * ── Environment detection ───────────────────────────────────────────
 * SITE_URL is used for canonical URLs, sitemap, and email links.
 * In production: https://www.lotoks.co.za
 * In dev: auto-detected from server
 */

if (!defined('BASE')) {
    $docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] ?? '');
    // Project root is the parent directory of 'includes'
    $projectRoot = str_replace('\\', '/', dirname(__DIR__));
    
    $base = '';
    // Strip document root from project root to get the base path (e.g., '/lotoks')
    if ($docRoot && str_starts_with(strtolower($projectRoot), strtolower($docRoot))) {
        $base = substr($projectRoot, strlen($docRoot));
    } else {
        // Fallback for weird setups: assume /lotoks or empty
        $base = '/lotoks';
    }
    
    $base = rtrim($base, '/');
    define('BASE', $base);
}

// ── Site URL (used for canonical URLs, sitemap, email links) ────────
if (!defined('SITE_URL')) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('SITE_URL', $scheme . '://' . $host . BASE);
}

// ── Application Name ────────────────────────────────────────────────
if (!defined('SITE_NAME')) {
    define('SITE_NAME', 'Lotoks');
}

// ── Email System ────────────────────────────────────────────────────
// Enable email: set EMAIL_ENABLED=true in .env for production,
// or override to true here. In dev mode, emails are logged to logs/email.log.
$emailEnabled = $_ENV['EMAIL_ENABLED'] ?? getenv('EMAIL_ENABLED');
if (!defined('EMAIL_ENABLED')) {
    define('EMAIL_ENABLED', $emailEnabled === 'true' || $emailEnabled === '1' || !empty($emailEnabled));
}

// SMTP settings — set via .env ONLY in production (never hardcode credentials).
// Defaults work for both local dev (email logging) and production (SMTP).
if (!defined('SMTP_HOST'))     { define('SMTP_HOST',     $_ENV['SMTP_HOST']     ?? getenv('SMTP_HOST')     ?? 'mail.lotoks.co.za'); }
if (!defined('SMTP_PORT'))     { define('SMTP_PORT',     $_ENV['SMTP_PORT']     ?? getenv('SMTP_PORT')     ?? 465); }
if (!defined('SMTP_USER'))     { define('SMTP_USER',     $_ENV['SMTP_USER']     ?? getenv('SMTP_USER')     ?? 'support@lotoks.co.za'); }
if (!defined('SMTP_PASS'))     { define('SMTP_PASS',     $_ENV['SMTP_PASS']     ?? getenv('SMTP_PASS')     ?? ''); }
if (!defined('SMTP_ENCRYPT'))  { define('SMTP_ENCRYPT',  $_ENV['SMTP_ENCRYPT']  ?? getenv('SMTP_ENCRYPT')  ?? 'ssl'); }
if (!defined('SMTP_FROM'))     { define('SMTP_FROM',     $_ENV['SMTP_FROM']     ?? getenv('SMTP_FROM')     ?? 'support@lotoks.co.za'); }
if (!defined('SMTP_FROM_NAME')){ define('SMTP_FROM_NAME',$_ENV['SMTP_FROM_NAME']?? getenv('SMTP_FROM_NAME')?? 'Lotoks Support'); }

// Warn if email is enabled but no SMTP password is set
if (EMAIL_ENABLED && empty(SMTP_PASS)) {
    error_log('[Lotoks Config] EMAIL_ENABLED is true but SMTP_PASS is not set. Add SMTP_PASS to .env');
}

// ── Two-Factor Authentication ───────────────────────────────────────
// Flip to true when 2FA is ready
if (!defined('TWO_FACTOR_ENABLED')) {
    define('TWO_FACTOR_ENABLED', false);
}

// ── File Upload Limits ──────────────────────────────────────────────
if (!defined('MAX_UPLOAD_SIZE')) {
    define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB
}

// ── Rate Limiting (per-IP) ──────────────────────────────────────────
if (!defined('RATE_LIMIT_ATTEMPTS')) {
    define('RATE_LIMIT_ATTEMPTS', 5); // max failed attempts per IP per window
}
if (!defined('RATE_LIMIT_WINDOW')) {
    define('RATE_LIMIT_WINDOW', 900); // 15 minutes in seconds
}

// ── Account Lockout (per-account) ──────────────────────────────────
// Separate from IP rate limiting — locks the specific account even
// if attempts come from different IP addresses.
if (!defined('ACCOUNT_LOCKOUT_ATTEMPTS')) {
    define('ACCOUNT_LOCKOUT_ATTEMPTS', 5); // consecutive failed attempts before lock
}
if (!defined('ACCOUNT_LOCKOUT_MINUTES')) {
    define('ACCOUNT_LOCKOUT_MINUTES', 30); // lock duration in minutes
}

// ── Error Reporting ─────────────────────────────────────────────────
if (!defined('IS_PRODUCTION')) {
    // Auto-detect: if host is not localhost/127.0.0.1, assume production
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    define('IS_PRODUCTION', !in_array($host, ['localhost', '127.0.0.1', '::1']));
}

if (IS_PRODUCTION) {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', dirname(__DIR__) . '/logs/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
