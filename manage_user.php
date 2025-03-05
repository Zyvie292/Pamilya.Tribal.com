<?php include "Connection.php";?>
<!DOCTYPE html>
<hrml lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pamilya</title>

    <link rel="shortcut icon" href="./assets/images/logo/Pamilya.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.js"></script>
</head>
    
</head>
<body>
<header class="admin-header">
        <div class="header-container">
            <a href="admin_dashboard.php" class="admin-logo">
                <img src="./assets/images/logo/adminlogo.svg">
            </a>
            <h3 class="menu">Manage User</h3>
            <nav class="admin-nav">
                <ul>
                    <li><a href="admin_dashboard.php">Home</a></li>
                    <li><a href="manage_users.php">Manage Users</a></li>
                    <li><a href="view_orders.php">View Orders</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

</body></hrml>