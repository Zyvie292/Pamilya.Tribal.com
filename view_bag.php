<?php
session_start();
include 'Connection.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Your cart is empty.";
    exit;
}

echo "<h2>Your Cart</h2>";
$total = 0;

// Loop through each item in the cart
foreach ($_SESSION['cart'] as $product_id => $quantity) {
    // Retrieve product details from the database
    $sql = "SELECT * FROM Products WHERE ProductID = ?";
    $params = array($product_id);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    // Calculate subtotal for each product
    $price = $row['Price'];
    $subtotal = $price * $quantity;
    $total += $subtotal;

    // Display product details, price, quantity, and subtotal
    echo "<div class='cart-item'>";
    echo "<h3>" . htmlspecialchars($row['ProductName']) . "</h3>";
    echo "<p>Price: $" . number_format($price, 2) . "</p>";
    echo "<p>Quantity: " . htmlspecialchars($quantity) . "</p>";
    echo "<p>Subtotal: $" . number_format($subtotal, 2) . "</p>";
    echo "<form method='POST' action='remove_from_cart.php'>";
    echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
    echo "<input type='submit' value='Remove from Cart'>";
    echo "</form>";
    echo "</div>";
}

echo "<h3>Total: $" . number_format($total, 2) . "</h3>";
?>
<form method="POST" action="checkout.php">
    <label for="customer_name">Your Name:</label>
    <input type="text" name="customer_name" id="customer_name" required>
    <input type="submit" value="Checkout">
</form>