<?php include "Connection.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pamilya - eCommerce Website</title>

  <link rel="shortcut icon" href="./assets/images/logo/favicon.ico" type="image/x-icon">

  <!-- <link rel="stylesheet" href="./assets/css/style.css"> -->
  <link rel="stylesheet" href="style-prefix.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.js"></script>
</head>
<body>
</body>

  <script src="script.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</html>

<?php
include "Connection.php"; // Ensure this includes your database connection setup
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=invalid_email");
        exit();
    }

    // Check if the username or email already exists
    $sql = "SELECT COUNT(*) AS count FROM users1 WHERE username = ? OR email = ?";
    $params = [$username, $email];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row['count'] > 0) {
        header("Location: index.php?error=username_exists");
        exit();
    }

    // Validate passwords
    if ($password !== $confirm_password) {
        header("Location: index.php?error=password_mismatch");
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $sql = "INSERT INTO users1 (username, email, password, created_at) VALUES (?, ?, ?, GETDATE())";
    $params = [$username, $email, $hashedPassword];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        error_log(print_r(sqlsrv_errors(), true)); // Log the error
        header("Location: index.php?error=db_error");
        exit();
    }

    // Registration successful
    header("Location: index.php?success=" . urlencode("Your account has been created successfully!"));
    exit();
}
?>