<?php
include "Connection.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Validate input
    if ($quantity <= 0) {
        header("Location: addtocart.php?product_id=$product_id&error=invalid_quantity");
        exit();
    }

    // Add the product to the cart (e.g., in the session or database)
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = [
            'product_id' => $product_id,
            'quantity' => $quantity
        ];
    }

    header("Location: cart.php?success=added");
    exit();
}
?>