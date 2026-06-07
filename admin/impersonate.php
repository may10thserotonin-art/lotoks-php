<?php
/**
 * admin/impersonate.php
 * Super admin impersonation — login as any user.
 * GET ?user_id=X      → start impersonation
 * GET ?action=stop    → stop impersonation, restore admin session
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

require_admin_auth();

$admin   = get_admin();
$isSuper = is_super_admin();
$db      = getDb();
$action  = $_GET['action'] ?? '';
$userId  = (int)($_GET['user_id'] ?? 0);

// ── Stop impersonation ────────────────────────────────────────
if ($action === 'stop') {
    if (!is_impersonating()) {
        flash('error', 'Not currently impersonating.');
        redirect('/admin/index.php');
    }
    stop_impersonation();
    flash('success', 'Returned to your admin account.');
    redirect('/admin/index.php');
}

// ── Start impersonation (super_admin only) ────────────────────
if (!$isSuper) {
    flash('error', 'Only super admins can impersonate users.');
    redirect('/admin/users.php');
}

if (!$userId) {
    flash('error', 'Invalid user ID.');
    redirect('/admin/users.php');
}

// Fetch user
$stmt = $db->prepare("SELECT id, email, name FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    flash('error', 'User not found.');
    redirect('/admin/users.php');
}

start_impersonation($user);
log_activity(null, $admin['id'], 'impersonation', "Super admin started impersonating user #{$userId} ({$user['name']})");

flash('success', "You are now logged in as {$user['name']}.");
redirect('/dashboard.php');
