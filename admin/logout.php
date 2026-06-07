<?php
require_once dirname(__DIR__) . '/includes/auth.php';

if (is_admin_logged_in()) {
    $admin = get_admin();
    log_activity(null, $admin['id'], 'admin_logout', 'Admin logged out');
    admin_logout();
}

redirect('/login.php');
