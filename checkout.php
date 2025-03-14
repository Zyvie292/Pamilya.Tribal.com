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
    SELECT c.id AS cart_id, p.name AS product_name, p.price, c.quantity 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?";
$stmtFetchCart = sqlsrv_query($conn, $sqlFetchCart, array($user_id));

if ($stmtFetchCart === false) {
    die(print_r(sqlsrv_errors(), true));
}

$grandTotal = 0;
$cartItems = [];
while ($cartItem = sqlsrv_fetch_array($stmtFetchCart, SQLSRV_FETCH_ASSOC)) {
    $totalPrice = $cartItem['price'] * $cartItem['quantity'];
    $grandTotal += $totalPrice;
    $cartItems[] = $cartItem;
}

// Check if the cart is empty
if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
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
        .buttons .place-order {
            background-color: #28a745;
            color: #fff;
        }
        .buttons .place-order:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td>₱<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p class="total">Grand Total: ₱<?php echo number_format($grandTotal, 2); ?></p>
        <div class="buttons">
            <form method="POST" action="place_order.php">
                <button type="submit" class="place-order">Place Order</button>
            </form>
        </div>
    </div>
</body>
</html>
