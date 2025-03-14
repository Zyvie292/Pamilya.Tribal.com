<?php
session_start();
require_once 'Connection.php';

if (isset($_POST['cart_id'])) {
    $cart_id = htmlspecialchars($_POST['cart_id']);
    $sqlDelete = "DELETE FROM cart WHERE id = ?";
    $stmtDelete = sqlsrv_query($conn, $sqlDelete, array($cart_id));
}

header("Location: cart.php");
exit();
?>