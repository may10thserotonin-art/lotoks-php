<?php
/**
 * Lotoks — Central Config
 *
 * Detects whether we are running under a sub-directory (e.g. /lotoks on XAMPP)
 * or at the domain root (production).  All includes that need to build URLs
 * should: require_once __DIR__ . '/config.php';  then use BASE.'/path/to/file'
 */

if (!defined('BASE')) {
    // Auto-detect the sub-directory prefix from SCRIPT_NAME.
    // e.g. /lotoks/index.php  →  /lotoks
    //      /index.php          →  ''
    $scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    // Normalise: strip trailing slash, treat bare '/' or '.' as empty
    $scriptDir = rtrim($scriptDir, '/');
    if ($scriptDir === '.' || $scriptDir === '\\') {
        $scriptDir = '';
    }
    define('BASE', $scriptDir);
}
