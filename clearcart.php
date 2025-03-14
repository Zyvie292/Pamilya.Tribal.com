<?php
session_start();
require_once 'Connection.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sqlClear = "DELETE FROM cart WHERE user_id = ?";
    $stmtClear = sqlsrv_query($conn, $sqlClear, array($user_id));
}

header("Location: cart.php");
exit();
?>
