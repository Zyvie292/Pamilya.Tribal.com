<?php
session_start();
header('Content-Type: application/json');
require '../Connection.php'; // Use SQLSRV connection

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['notif_id'])) {
    echo json_encode(["success" => false, "message" => "Notification ID missing"]);
    exit;
}

$notif_id = intval($data['notif_id']);

// Delete notification
$query = "DELETE FROM notifications WHERE notif_id = ?";
$params = [$notif_id];
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete notification: " . print_r(sqlsrv_errors(), true)]);
}

sqlsrv_close($conn);
?>
