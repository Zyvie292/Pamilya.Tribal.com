<?php
include "Connection.php";
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("You must be logged in to view your cart.");
}

// Fetch the user's cart items
$sqlCart = "SELECT c.quantity, p.name, p.price, (c.quantity * p.price) AS total_price
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = ?";
$params = [$user_id];
$stmtCart = sqlsrv_query($conn, $sqlCart, $params);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
</head>
<body>
    <h1>Your Cart</h1>
    <?php if (sqlsrv_has_rows($stmtCart)): ?>
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
                <?php while ($row = sqlsrv_fetch_array($stmtCart, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td>₱<?php echo number_format($row['total_price'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</body>
</html>