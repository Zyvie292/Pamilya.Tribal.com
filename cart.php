<?php
session_start();
require_once 'Connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch cart items
$sqlFetchCart = "
    SELECT c.id AS cart_id, p.name AS product_name, p.image_path, p.price, c.quantity 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?";
$stmtFetchCart = sqlsrv_query($conn, $sqlFetchCart, array($user_id));

if ($stmtFetchCart === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Fetch orders
$sqlFetchOrders = "
    SELECT id, total_price, shippingfee, order_date, status 
    FROM orders
    WHERE user_id = ?
    ORDER BY order_date DESC";
$stmtFetchOrders = sqlsrv_query($conn, $sqlFetchOrders, array($user_id));

if ($stmtFetchOrders === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart and Orders</title>
    <style>
        /* Same styles as before */
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 15px;

        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: DeepPink;
            color: black;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table img {
            max-width: 80px;
            border-radius: 8px;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
        .buttons {
            text-align: right;
            margin-top: 20px;
        }
        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
        }
        .buttons .checkout {
            background-color: #28a745;
            color: #fff;
        }
        .buttons .checkout:hover {
            background-color: #218838;
        }
        .buttons .clear {
            background-color: #dc3545;
            color: #fff;
        }
        .buttons .clear:hover {
            background-color: #c82333;
        }
        .buttons .received {
            background-color: #007bff;
            color: #fff;
        }
        .buttons .received:hover {
            background-color: #0056b3;
        }
        @media screen and (max-width: 480px) {
    h1, h2 {
        font-size: 13px;
    }

    .buttons {
        flex-direction: column;
        align-items: center;
    }

    .buttons button {
        width: 100%;
        padding: 10px;
        font-size: 14px;
    }

    table {
        width: 100%;
    }

    table th, table td {
        font-size: 12px;
        padding: 6px;
    }

    table img {
        max-width: 50px;
    }
}
    </style>
</head>
<body>
    <div class="container">
        <h1>My Cart</h1>
        <?php if (sqlsrv_has_rows($stmtFetchCart)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandTotal = 0;
                    while ($cartItem = sqlsrv_fetch_array($stmtFetchCart, SQLSRV_FETCH_ASSOC)):
                        $totalPrice = $cartItem['price'] * $cartItem['quantity'];
                        $grandTotal += $totalPrice;
                    ?>
                        <tr>
                            <td>
                                <img src="<?php echo htmlspecialchars($cartItem['image_path'] ?: 'placeholder.jpg'); ?>" 
                                     alt="<?php echo htmlspecialchars($cartItem['product_name']); ?>">
                            </td>
                            <td><?php echo htmlspecialchars($cartItem['product_name']); ?></td>
                            <td>₱<?php echo number_format($cartItem['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($cartItem['quantity']); ?></td>
                            <td>₱<?php echo number_format($totalPrice, 2); ?></td>
                            <td>
                                <form method="POST" action="removefromcart.php" style="display: inline;">
                                    <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($cartItem['cart_id']); ?>">
                                    <button type="submit" style="background-color: #dc3545; color: #fff; border: none; padding: 8px 12px; border-radius: 5px; cursor: pointer;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p class="total">Grand Total: ₱<?php echo number_format($grandTotal, 2); ?></p>
            <div class="buttons">
                <form method="POST" action="checkout.php" style="display: inline;">
                    <button type="submit" class="checkout">Checkout</button>
                </form>
                <form method="POST" action="clearcart.php" style="display: inline;">
                    <button type="submit" class="clear">Clear Cart</button>
                </form>
            </div>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px;">Your cart is empty!</p>
        <?php endif; ?>

        <h2>My Orders</h2>
        <?php if (sqlsrv_has_rows($stmtFetchOrders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total Price</th>
                        <th>Shipping Fee</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = sqlsrv_fetch_array($stmtFetchOrders, SQLSRV_FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['id']); ?></td>
                            <td>₱<?php echo number_format($order['total_price'], 2); ?></td>
                            <td>₱<?php echo number_format($order['shippingfee'], 2); ?></td>
                            <td><?php echo $order['order_date']->format('Y-m-d'); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>
                                <?php if ($order['status'] === 'Shipped'): ?>
                                    <form method="POST" action="orderreceived.php" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['id']); ?>">
                                        <button type="submit" class="received">Order Received</button>
                                    </form>
                                <?php else: ?>
                                    <p>N/A</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="text-align: center; font-size: 18px;">No orders found!</p>
        <?php endif; ?>

        <div class="buttons">
            <button type="button" onclick="window.history.back();" class="back">
                Back
            </button>
        </div>
    </div>
</body>
</html>