<?php
require_once __DIR__ . '/includes/auth.php';

// Logout user (destroys PHP session)
user_logout();

redirect('/login.php');
