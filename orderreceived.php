<?php
session_start();
require_once 'Connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Ensure the order ID is provided
if (!isset($_POST['order_id'])) {
    header("Location: cart.php");
    exit();
}
$order_id = intval($_POST['order_id']);

// Check if the order status is "Shipped" before updating it to "Received"
$sqlCheckOrderStatus = "SELECT status FROM orders WHERE id = ? AND user_id = ?";
$stmtCheckOrderStatus = sqlsrv_query($conn, $sqlCheckOrderStatus, array($order_id, $user_id));

if ($stmtCheckOrderStatus === false) {
    die(print_r(sqlsrv_errors(), true));
}

$orderStatus = sqlsrv_fetch_array($stmtCheckOrderStatus, SQLSRV_FETCH_ASSOC)['status'];

if ($orderStatus !== 'Shipped') {
    // If the order is not shipped, do not update the status.
    header("Location: cart.php");
    exit();
}

// Update the order status to "Received"
$sqlUpdateOrderStatus = "
    UPDATE orders
    SET status = 'Received'
    WHERE id = ? AND user_id = ?";
$params = array($order_id, $user_id);
$stmtUpdateOrderStatus = sqlsrv_query($conn, $sqlUpdateOrderStatus, $params);

if ($stmtUpdateOrderStatus === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Redirect back to the orders page (or cart page)
header("Location: cart.php");
exit();
?>