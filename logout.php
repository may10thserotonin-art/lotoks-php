<?php
require_once __DIR__ . '/includes/auth.php';

// Logout user
user_logout();

// Also call backend logout to clear the JWT cookie if the Express backend is running
try {
    $ch = curl_init(rtrim(getenv('API_BASE_URL') ?: 'http://localhost:3001/api', '/') . '/auth/user/logout');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => '',
        CURLOPT_HTTPHEADER     => ['Cookie: ' . http_build_cookie()],
        CURLOPT_TIMEOUT        => 3,
    ]);
    curl_exec($ch);
    curl_close($ch);
} catch (Throwable $e) { /* non-fatal */ }

header('Location: /login.php');
exit;
