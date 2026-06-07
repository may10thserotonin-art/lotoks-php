<?php
/**
 * Notifications API
 * 
 * GET   ?action=get_unread_count  — returns count of unread notifications
 * POST  ?action=mark_all_read     — marks all user's notifications as read
 * POST  ?action=mark_read         — marks a single notification as read (body: {id})
 */
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../db/connect.php';
requireUserAuth('/login.php');

$user   = getCurrentUser();
$userId = (int)($user['id'] ?? 0);
$db     = getDb();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {

    case 'get_unread_count':
        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM activity_log WHERE user_id = ? AND read_at IS NULL");
        $stmt->execute([$userId]);
        $row = $stmt->fetch();
        echo json_encode(['success' => true, 'count' => (int)($row['cnt'] ?? 0)]);
        exit;

    case 'mark_all_read':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        $stmt = $db->prepare("UPDATE activity_log SET read_at = NOW() WHERE user_id = ? AND read_at IS NULL");
        $stmt->execute([$userId]);
        echo json_encode(['success' => true, 'updated' => $stmt->rowCount()]);
        exit;

    case 'mark_read':
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $notifId = (int)($input['id'] ?? 0);
        if (!$notifId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing notification id']);
            exit;
        }
        $stmt = $db->prepare("UPDATE activity_log SET read_at = NOW() WHERE id = ? AND user_id = ?");
        $stmt->execute([$notifId, $userId]);
        echo json_encode(['success' => true, 'updated' => $stmt->rowCount()]);
        exit;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
        exit;
}
