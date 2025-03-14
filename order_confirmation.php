<?php
session_start();
require_once 'Connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate `order_id`
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    die("Invalid order ID.");
}

// Fetch order details
$sqlFetchOrder = "
    SELECT total_price, shippingAddress, payment_method, order_date 
    FROM orders 
    WHERE id = ? AND user_id = ?";
$paramsFetchOrder = array($order_id, $user_id);
$stmtFetchOrder = sqlsrv_query($conn, $sqlFetchOrder, $paramsFetchOrder);

if ($stmtFetchOrder === false || !sqlsrv_has_rows($stmtFetchOrder)) {
    die("Order not found.");
}

$order = sqlsrv_fetch_array($stmtFetchOrder, SQLSRV_FETCH_ASSOC);

// Fetch the user's name and email
$sqlFetchUser = "SELECT username, email FROM users1 WHERE id = ?";
$stmtFetchUser = sqlsrv_query($conn, $sqlFetchUser, array($user_id));

if ($stmtFetchUser === false || !sqlsrv_has_rows($stmtFetchUser)) {
    die("Error fetching user details.");
}

$user1 = sqlsrv_fetch_array($stmtFetchUser, SQLSRV_FETCH_ASSOC);
$username = $user1['username'] ?? 'Customer';
$email = $user1['email'] ?? '';

// Fetch order items with product size
$sqlFetchOrderItems = "
    SELECT product_name, product_size, quantity, price 
    FROM order_items 
    WHERE order_id = ?";
$stmtFetchOrderItems = sqlsrv_query($conn, $sqlFetchOrderItems, array($order_id));

if ($stmtFetchOrderItems === false) {
    die(print_r(sqlsrv_errors(), true));
}

$orderItems = [];
while ($item = sqlsrv_fetch_array($stmtFetchOrderItems, SQLSRV_FETCH_ASSOC)) {
    $orderItems[] = $item;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: "Poppins", sans-serif; background-color: #ffeaf4; color: #333; }
        .container { max-width: 800px; margin: 50px auto; padding: 30px; background: #ffffff; border-radius: 15px; border: 2px solid #ffc2da; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        h1, h2 { color: rgb(0, 0, 0); text-align: center; }
        ul { list-style: none; padding: 0; margin: 20px 0; }
        ul li { margin: 10px 0; padding: 10px; background: #ffe0ec; border-radius: 10px; font-size: 1rem; color: #555; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); display: flex; justify-content: space-between; align-items: center; }
        .container a { display: inline-block; margin-top: 20px; padding: 10px 20px; color: #fff; background: #ff5c93; text-decoration: none; border-radius: 8px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Confirmation</h1>
        <p>🛍️ Thank you for your order, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
        <div class="order-summary">
            <h2>Order Summary:</h2>
            <ul>
                <?php foreach ($orderItems as $item): ?>
                    <li>
                        <span><?php echo htmlspecialchars($item['product_name']); ?> (x<?php echo $item['quantity']; ?>) <?php echo !empty($item['product_size']) ? '| Size: ' . htmlspecialchars($item['product_size']) : ''; ?>:</span>
                        ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Sub Total:</strong> ₱<?php echo number_format($order['total_price'], 2); ?></p>
            <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['order_date']->format('Y-m-d H:i:s'); ?></p>
            <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        </div>
        <div class="address-email">
            <h2>Shipping Address:</h2>
            <p><?php echo htmlspecialchars($order['shippingAddress']); ?></p>
            <p>We will send you an email with payment details shortly including the shipping amount.</p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p>Thank you for supporting local sellers!</p>
            <p>Warm regards, <br> Pamilya Team</p>
        </div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <footer>
        <p>&copy; 2024 Your E-commerce Store. All rights reserved.</p>
    </footer>
</body>
</html>
