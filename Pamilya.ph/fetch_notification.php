<?php include "Connection.php";
// Example User ID (Replace with dynamic logic in production)
$user_id = $_GET['user_id'] ?? 1;

// Fetch unread notifications
$query = "SELECT id, message, created_at FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
$params = [$user_id];
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$notifications = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $notifications[] = [
        "id" => $row['id'],
        "message" => $row['message'],
        "created_at" => $row['created_at']->format('Y-m-d H:i:s')
    ];
}

echo json_encode($notifications);

// Close connection
sqlsrv_close($conn);
?>