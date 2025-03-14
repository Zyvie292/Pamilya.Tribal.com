<?php
include "Connection.php";
session_start();

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['id'])) {
    $id = $data['id'];

    $sqlUpdate = "UPDATE notifications SET is_read = 1 WHERE id = ?";
    $stmt = sqlsrv_query($conn, $sqlUpdate, [$id]);

    if ($stmt) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Failed to update notification"]);
    }
}
?>