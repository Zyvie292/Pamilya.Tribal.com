<?php
session_start();
require "Connection.php";

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $token = $_POST["token"] ?? null;
        $newPassword = $_POST["new_password"] ?? null;

        if (empty($token) || empty($newPassword)) {
            throw new Exception("❌ Token and new password are required.");
        }

        // Debug: Show received token
        echo "Received Token: " . htmlspecialchars($token) . "<br>";

        // Fetch user by token
        $sql = "SELECT email, token_expiry FROM users1 WHERE reset_token = ?";
        $params = array($token);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            throw new Exception("Database error: " . print_r(sqlsrv_errors(), true));
        }

        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        if (!$user) {
            throw new Exception("❌ No matching token found in database.");
        }

        // Debug: Check token expiry
        $expiry = $user['token_expiry'];
        echo "Token Expiry in DB: " . $expiry->format('Y-m-d H:i:s') . "<br>";

        if ($expiry < new DateTime()) {
            throw new Exception("❌ Token has expired.");
        }

        // Hash new password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Update password and clear token
        $updateSql = "UPDATE users1 SET password = ?, reset_token = NULL, token_expiry = NULL WHERE email = ?";
        $updateParams = array($hashedPassword, $user["email"]);
        $updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

        if ($updateStmt === false) {
            throw new Exception("Failed to update password: " . print_r(sqlsrv_errors(), true));
        }

        $_SESSION['message'] = "✅ Password updated successfully!";
    }
} catch (Exception $e) {
    $_SESSION['message'] = "❌ Error: " . $e->getMessage();
}

// Free resources
if (isset($stmt) && is_resource($stmt)) {
    sqlsrv_free_stmt($stmt);
}
if (isset($updateStmt) && is_resource($updateStmt)) {
    sqlsrv_free_stmt($updateStmt);
}

header("Location: index.php");
exit();
?>