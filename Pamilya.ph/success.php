<?php
session_start();
if (!isset($_GET['order_id'])) {
    header("Location: Index.php");
    exit();
}

$order_id = htmlspecialchars($_GET['order_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            margin-top: 50px;
        }
        .message {
            font-size: 1.5em;
            color: #28a745;
        }
        .order-id {
            font-size: 1.2em;
            color: #333;
        }
        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thank You for Your Order!</h1>
        <p class="message">Your order has been placed successfully.</p>
        <p class="order-id">Order ID: <?php echo $order_id; ?></p>
        <a href="orders.php" class="btn">View Your Orders</a>
    </div>
</body>
</html>