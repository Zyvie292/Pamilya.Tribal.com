<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Require PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Redirect to login if the user is not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: administration.php?error=" . urlencode("Please log in to access the admin dashboard"));
    exit();
}

// Get the admin username from the session
$adminUsername = isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : null;

// Include the database connection
include "Connection.php";

// Initialize orders array and message
$orders = [];
$message = "";

// Fetch orders from the database
try {
    $query = "
        SELECT 
            o.id, 
            u.username AS Customer, 
            u.email AS CustomerEmail,
            o.total_price, 
            o.order_date, 
            o.status, 
            o.payment_method, 
            o.shippingAddress,
            o.shippingfee,
            o.notified
        FROM orders o
        JOIN users1 u ON o.user_id = u.id
        ORDER BY o.order_date DESC";

    $stmt = sqlsrv_query($conn, $query);

    if ($stmt === false) {
        die("Error in query execution: " . print_r(sqlsrv_errors(), true));
    }

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $orders[] = $row;
    }

    if (empty($orders)) {
        $message = "No orders found.";
    }
} catch (Exception $e) {
    $message = "Error fetching orders: " . $e->getMessage();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addShippingFee'])) {
        // Add shipping fee and notify the buyer
        $orderID = intval($_POST['orderID']);
        $shippingFee = floatval($_POST['shippingFee']);
        $customerEmail = htmlspecialchars($_POST['customerEmail']);
        $totalPrice = floatval($_POST['totalPrice']);
        $username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : "Customer";
        $newTotal = $totalPrice + $shippingFee;

        // Update the order with the shipping fee in the database
        $updateQuery = "
            UPDATE orders
            SET shippingfee = ?, total_price = ?, notified = 1
            WHERE id = ?";
        $params = [$shippingFee, $newTotal, $orderID];

        if (sqlsrv_query($conn, $updateQuery, $params)) {
            // Send email to the buyer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pamilyamarketingteam@gmail.com';
                $mail->Password = 'xzlu rpsx mmee oinz'; // Use environment variables for security
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                // Recipients
                $mail->setFrom('pamilyamarketingteam@gmail.com', 'Pamilya E-commerce');
                $mail->addAddress($customerEmail);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Updated Order Details -- Shipping Fee Added';
                $mail->Body = "
                    <p>Dear {$username},</p>
                    <p>We have updated your order to include the shipping fee.</p>
                    <p><strong>Order ID:</strong> {$orderID}</p>
                    <p><strong>New Total Price:</strong> ₱" . number_format($newTotal, 2) . "</p>
                    <p>Thank you for your understanding! Your order will be processed once payment is confirmed.</p>
                    <p>If you have any concerns or questions, don't hesitate to reach out to us.</p>
                    <p>Best regards,<br>Pamilya Team</p>
                ";

                $mail->send();
                $message = "Shipping fee added and email sent successfully to {$customerEmail}.";
            } catch (Exception $e) {
                $message = "Shipping fee added, but email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "Failed to update the shipping fee in the database.";
        }
    }

    if (isset($_POST['updateStatus'])) {
        // Update the status of the order
        $orderID = intval($_POST['orderID']);
        $newStatus = htmlspecialchars($_POST['status']);
    
        // Update the status in the database
        $statusQuery = "UPDATE orders SET status = ? WHERE id = ?";
        $params = [$newStatus, $orderID];
    
        if (sqlsrv_query($conn, $statusQuery, $params)) {
            $message = "Order #{$orderID} status updated to {$newStatus}.";
    
            // If the status is "Payment Received", insert into successfulpayment table
            if ($newStatus === "Payment Received") {
                // Retrieve order details
                $orderQuery = "SELECT user_id, total_price, order_date, payment_method, shippingAddress FROM orders WHERE id = ?";
                $stmt = sqlsrv_query($conn, $orderQuery, [$orderID]);
                $order = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
                if ($order) {
                    // Insert into successfulpayment table
                    $insertQuery = "INSERT INTO successfulpayment (order_id, customer_name, total_price, order_date, payment_method, shipping_address, created_at) 
                                    VALUES (?, ?, ?, ?, ?, ?, GETDATE())";
                    $params = [
                        $orderID, 
                        $order['user_id'], 
                        $order['total_price'], 
                        $order['order_date']->format('Y-m-d H:i:s'), 
                        $order['payment_method'], 
                        $order['shippingAddress']
                    ];
                    $insertStmt = sqlsrv_query($conn, $insertQuery, $params);
    
                    if ($insertStmt) {
                        $message .= " Order also added to successfulpayment table.";
                    } else {
                        $message .= " Failed to add order to successfulpayment table.";
                    }
                }
            }
        } else {
            $message = "Failed to update the status for Order #{$orderID}.";
        }
    }
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
<header class="admin-header">
    <div class="header-container">
      <a href="admin_dashboard.php" class="admin-logo">
        <img src="./assets/images/logo/adminlogo.svg">
      </a>
      <h4 class="menu">View Orders</h4>
      <button class="menu-toggle">☰</button>
      <nav class="admin-nav">
        <ul>
         <li><a href="admin_dashboard.php">Home</a></li>
          <li><a href="manage_products.php">Manage Products</a></li>
          <li><a href="manage_users.php">Manage Users</a></li>
          <li><a href="view_orders.php">View Orders</a></li>
          <li><a href="logout.php">Logout</a></li>
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

  <main class="admin-main">
  <div class="container">
            <h2>All Orders</h2>
            <?php if ($message): ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Shipping Fee</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $index => $order): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($order['Customer']); ?></td>
                                <td><?php echo htmlspecialchars($order['CustomerEmail']); ?></td>
                                <td>&#8369;<?php echo number_format($order['total_price'], 2); ?></td>
                                <td><?php echo htmlspecialchars($order['order_date']->format('Y-m-d H:i:s')); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td>&#8369;<?php echo number_format($order['shippingfee'], 2); ?></td>
                                <td>
                                <form method="POST" action="view_orders.php">
                                <input type="hidden" name="username" value="<?= htmlspecialchars($order['Customer']) ?>">
                                <input type="hidden" name="orderID" value="<?= htmlspecialchars($order['id']) ?>">
                                <input type="hidden" name="customerEmail" value="<?= htmlspecialchars($order['CustomerEmail']) ?>">
                                <input type="hidden" name="totalPrice" value="<?= htmlspecialchars($order['total_price']) ?>">
                                <input type="number" name="shippingFee" placeholder="Enter Shipping Fee" required>
                                <button type="submit" name="addShippingFee">Add Shipping Fee</button>
                                </form>
                                                                <?php if ($order['notified']): ?>
                                        <form method="POST">
                                            <input type="hidden" name="orderID" value="<?php echo $order['id']; ?>">
                                            <select name="status" required>
                                                <option value="Payment Received">Payment Received</option>
                                                <option value="Shipped">Shipped</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                            <button type="submit" name="updateStatus">Update Status</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>