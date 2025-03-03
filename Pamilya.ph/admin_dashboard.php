<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: administration.php?error=" . urlencode("Please log in to access the admin dashboard"));
    exit();
}

// Get the admin username from the session
$adminUsername = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : null;

// Include the database connection
include "Connection.php";

// Initialize variables
$totalProducts = 0;
$totalUsers = 0;
$totalPendingOrders = 0;
$recentActivities = [];

// Fetch data from the database
try {
    // Get total products
    $query = "SELECT COUNT(*) AS TotalProducts FROM Products";
    $stmt = sqlsrv_query($conn, $query);
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $totalProducts = $row['TotalProducts'];
    }

    // Get total users
    $query = "SELECT COUNT(*) AS TotalUsers FROM Users";
    $stmt = sqlsrv_query($conn, $query);
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $totalUsers = $row['TotalUsers'];
    }

    // Get total pending orders
    $query = "SELECT COUNT(*) AS PendingOrders FROM Orders WHERE Status = 'Pending'";
    $stmt = sqlsrv_query($conn, $query);
    if ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $totalPendingOrders = $row['PendingOrders'];
    }

    // Get recent activities (Top 5)
    $query = "SELECT TOP 5 ActivityDescription, ActivityDate FROM Activities ORDER BY ActivityDate DESC";
    $stmt = sqlsrv_query($conn, $query);
    while ($stmt && $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $recentActivities[] = $row;
    }
} catch (Exception $e) {
    echo "Error fetching data: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - Pamilya</title>

  <link rel="shortcut icon" href="./assets/images/logo/Pamilya.ico" type="image/x-icon">
  <link rel="stylesheet" href="./assets/css/admin-style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.js"></script>
</head>

<body>
  <!-- Admin Header -->
  <header class="admin-header">
    <div class="header-container">
      <a href="admin_dashboard.php" class="admin-logo">
        <img src="./assets/images/logo/adminlogo.svg">
      </a>
      <h4 class="menu">Admin Dashboard</h4>
      <button class="menu-toggle">☰</button>
      <nav class="admin-nav">
        <ul>
          <li><a href="manage_products.php">Manage Products</a></li>
          <li><a href="manage_users.php">Manage Users</a></li>
          <li><a href="view_orders.php">View Orders</a></li>
          <li><a href="admin-logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
    <script>
   document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript Loaded"); // Debugging message

    const menuToggle = document.querySelector(".menu-toggle");
    const adminNav = document.querySelector(".admin-nav");

    if (!menuToggle || !adminNav) {
        console.log("Menu elements not found!");
        return; // Stop script if elements are missing
    }

    menuToggle.addEventListener("click", function (event) {
        console.log("Menu Toggle Clicked");
        adminNav.classList.toggle("active");
        event.stopPropagation(); // Prevent immediate closing
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (event) {
        if (!adminNav.contains(event.target) && !menuToggle.contains(event.target)) {
            adminNav.classList.remove("active");
        }
    });
});
  </script>
  </header>


  <!-- Admin Main Content -->
  <main class="admin-main">
    <div class="container">
      <section class="dashboard-welcome">
        <h2>Welcome, <?php echo $adminUsername; ?>!</h2>
        <p>Here's a summary of your administrative activities:</p>
      </section>

      <section class="dashboard-cards">
        <div class="card">
          <ion-icon name="cart-outline" size="large"></ion-icon>
          <h3>Products</h3>
          <p><?php echo $totalProducts; ?> Total Products</p>
        </div>
        <div class="card">
          <ion-icon name="people-outline" size="large"></ion-icon>
          <h3>Users</h3>
          <p><?php echo $totalUsers; ?> Registered Users</p>
        </div>
        <div class="card">
          <ion-icon name="bag-outline" size="large"></ion-icon>
          <h3>Orders</h3>
          <p><?php echo $totalPendingOrders; ?> Pending Orders</p>
        </div>
      </section>

      <section class="dashboard-tables">
        <h2>Recent Activities</h2>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Activity</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($recentActivities)): ?>
              <?php foreach ($recentActivities as $index => $activity): ?>
                <tr>
                  <td><?php echo $index + 1; ?></td>
                  <td><?php echo htmlspecialchars($activity['ActivityDescription']); ?></td>
                  <td><?php echo htmlspecialchars($activity['ActivityDate']->format('Y-m-d H:i:s')); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="3">No recent activities found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </div>
  </main>

  <!-- Admin Footer -->
  <footer class="admin-footer">
    <div class="footer-container">
      <p>&copy; 2024 Pamilya. All Rights Reserved.</p>
    </div>
  </footer>
  <script src="./assets/js/script.js"></script>
</body>

</html>