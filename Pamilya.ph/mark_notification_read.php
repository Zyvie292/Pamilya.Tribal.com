<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Validate input
if (!isset($_POST['notif_id']) || !isset($_POST['is_read'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

$notifId = intval($_POST['notif_id']);
$isRead = filter_var($_POST['is_read'], FILTER_VALIDATE_BOOLEAN);
$userId = $_SESSION['user_id'];

// Database connection
require_once('../Connection.php');

// Update the `is_read` status
$sql = "UPDATE notifications SET is_read = ? WHERE notif_id = ? AND user_id = ?";
$stmt = sqlsrv_query($conn, $sql, [$isRead ? 1 : 0, $notifId, $userId]);

if ($stmt === false) {
    error_log('SQL Error: ' . print_r(sqlsrv_errors(), true));
    echo json_encode(['success' => false, 'message' => 'Database error']);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Notification updated successfully']);
?>