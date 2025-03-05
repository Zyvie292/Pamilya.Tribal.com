<?php
include "Connection.php";
session_start();

// Ensure default admin account exists
$defaultUsername = 'Admin';
$defaultPassword = '@PamilyaAdmin2926'; // Replace with a strong password
$hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

// Check if the admin table has at least one record
$sqlCheck = "SELECT COUNT(*) AS user_count FROM admin_users";
$stmtCheck = sqlsrv_query($conn, $sqlCheck);

if ($stmtCheck === false) {
    die(print_r(sqlsrv_errors(), true));
}

$rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);

if ($rowCheck['user_count'] == 0) {
    // Insert default admin account
    $sqlInsert = "INSERT INTO admin_users (username, password, created_at) VALUES (?, ?, GETDATE())";
    $paramsInsert = array($defaultUsername, $hashedPassword);
    $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);

    if ($stmtInsert === false) {
        die(print_r(sqlsrv_errors(), true));
    }
}

// Login logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin_users WHERE username = ?";
    $params = array($username);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Verify the password
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            // Redirect to dashboard with success message
            header("Location: admin_dashboard.php?success=" . urlencode("Welcome, $username!"));
            exit;
        } else {
            // Incorrect password
            header("Location: Admin.php?error=invalid_password");
            exit();
        }
    } else {
        // Username not found
        header("Location: Admin.php?error=user_not_found");
        exit();
    }
}

// Logout logic (optional)
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: Admin.php?message=" . urlencode("You have been logged out successfully."));
    exit();
}
?>