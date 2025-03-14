<?php
session_start();
include 'Connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['bagCount' => 0]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch total bag count
$query = "SELECT SUM(quantity) AS bag_count FROM cart_items WHERE user_id = ?";
$params = [$user_id];
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    echo json_encode(['bagCount' => 0]); // Fallback if query fails
    exit();
}

$result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$bagCount = $result['bag_count'] ?? 0;

echo json_encode(['bagCount' => $bagCount]);
?>