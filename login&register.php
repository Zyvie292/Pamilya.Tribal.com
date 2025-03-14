<?php
include "Connection.php"; // Include the database connection
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username exists
    $sql = "SELECT id, username, password FROM users1 WHERE username = ?";
    $params = [$username];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch user record
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Login successful
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id']; // Store the user ID in the session
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            header("Location: index.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: index.php?error=user_not_found");
        exit();
    } 
}

header("Location: index.php?success=" . urlencode("Your account has been created successfully!"));
exit();
?>