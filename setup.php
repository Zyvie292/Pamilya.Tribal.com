<?php
include "Connection.php"; // Use only the connection file here

// Ensure default admin account exists
$defaultUsername = 'Admin';
$defaultPassword = '@PamilyaAdmin2926'; // Replace with a strong password
$hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

// Check if the admin account exists
$sqlCheck = "SELECT COUNT(*) AS user_count FROM admin_users";
$stmtCheck = sqlsrv_query($conn, $sqlCheck);

if ($stmtCheck === false) {
    die(print_r(sqlsrv_errors(), true)); // Handle errors
}

$rowCheck = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_ASSOC);

if ($rowCheck['user_count'] == 0) {
    // Insert default admin account
    $currentTimestamp = date('Y-m-d H:i:s'); // Get current datetime
    $sqlInsert = "INSERT INTO admin_users (username, password, created_at) VALUES (?, ?, ?)";
    $paramsInsert = array($defaultUsername, $hashedPassword, $currentTimestamp);
    $stmtInsert = sqlsrv_query($conn, $sqlInsert, $paramsInsert);

    if ($stmtInsert === false) {
        die(print_r(sqlsrv_errors(), true)); // Handle errors
    }

    echo "Default admin account created successfully.";
} else {
    echo "Admin account already exists.";
}
?>