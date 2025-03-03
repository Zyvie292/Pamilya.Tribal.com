<?php
session_start();
header('Content-Type: application/json');
require '../Connection.php'; // Use SQLSRV connection

// Fetch notifications
$query = "SELECT notif_id, message, is_read FROM notifications ORDER BY created_at DESC";
$result = sqlsrv_query($conn, $query);

if (!$result) {
    echo json_encode(["success" => false, "message" => "Database query failed: " . print_r(sqlsrv_errors(), true)]);
    exit;
}

$notifications = [];
$unreadCount = 0;

while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $notifications[] = [
        "notif_id" => $row["notif_id"],
        "message" => $row["message"],
        "is_read" => (bool)$row["is_read"]
    ];
    if (!$row["is_read"]) {
        $unreadCount++;
    }
}

// Send JSON response
echo json_encode(["success" => true, "notifications" => $notifications, "unreadCount" => $unreadCount]);

sqlsrv_close($conn);
?>