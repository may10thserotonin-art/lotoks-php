<?php
/**
 * Lotoks — Developer Mode Login API
 */
require_once dirname(__DIR__) . '/includes/auth.php';

$body = get_json_body();
$pin = $body['pin'] ?? '';

if ($pin === '091344') {
    user_login([
        'id' => 9999,
        'name' => 'Developer',
        'email' => 'dev@lotoks.com',
        'country' => 'United States',
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    json_ok(['message' => 'Dev login successful']);
} else {
    json_error('Invalid PIN', 401);
}
