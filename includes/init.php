<?php
/**
 * Lotoks — Application Bootstrap
 *
 * Legacy bootstrap file. All functionality has been moved into
 * includes/auth.php which is included by every page automatically.
 *
 * Kept for backwards compatibility — including it is harmless
 * since auth.php handles idempotent session/header setup.
 */
require_once __DIR__ . '/auth.php';
