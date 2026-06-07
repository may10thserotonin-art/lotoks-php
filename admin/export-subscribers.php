<?php
/**
 * Lotoks — Export Newsletter Subscribers (CSV)
 *
 * Downloads a CSV file of subscribers filtered by status.
 * Requires admin authentication.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

require_admin_auth();

$statusFilter = $_GET['status'] ?? 'all';
$allowedFilters = ['all', 'active', 'pending', 'unsubscribed'];
if (!in_array($statusFilter, $allowedFilters)) {
    $statusFilter = 'all';
}

$db = getDb();
$sql = "SELECT email, status, confirmed_at, created_at FROM newsletter_subscribers";
$params = [];
if ($statusFilter !== 'all') {
    $sql .= " WHERE status = :status";
    $params[':status'] = $statusFilter;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$subscribers = $stmt->fetchAll();

// ── Output CSV ──────────────────────────────────────────────────────
$filename = 'lotoks-newsletter-' . $statusFilter . '-' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Email', 'Status', 'Confirmed Date', 'Subscribed Date']);

foreach ($subscribers as $sub) {
    fputcsv($output, [
        $sub['email'],
        $sub['status'],
        $sub['confirmed_at'] ? date('Y-m-d H:i:s', strtotime($sub['confirmed_at'])) : '',
        date('Y-m-d H:i:s', strtotime($sub['created_at'])),
    ]);
}

fclose($output);
exit;
