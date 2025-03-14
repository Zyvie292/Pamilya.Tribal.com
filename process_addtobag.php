<?php
require_once 'Connection.php';
session_start();

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = htmlspecialchars($_POST['product_id']);
    $quantity = (int)$_POST['quantity'];
    $product_size = isset($_POST['size']) ? htmlspecialchars($_POST['size']) : null;

    // Validate quantity
    if ($quantity < 1) {
        header("Location: addtobag.php?product_id=$product_id&error=invalid_quantity");
        exit();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: Index.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    // Fetch product category to determine if size is required
    $sqlFetch = "SELECT category FROM products WHERE id = ?";
    $stmtFetch = sqlsrv_query($conn, $sqlFetch, array($product_id));

    if ($stmtFetch && $product = sqlsrv_fetch_array($stmtFetch, SQLSRV_FETCH_ASSOC)) {
        $category = strtolower($product['category']);

        // Define categories that require sizes
        $sizeCategories = ['dress', 'jacket/kimono', 'barong', 'blouse', 'pants', 'filipiniana', 'polo/shirts', 'skirt',
            'doll', 'chaleko', 'bag', 'abaya', 'blazer'];

        if (in_array($category, $sizeCategories) && empty($product_size)) {
            header("Location: addtobag.php?product_id=$product_id&error=missing_size");
            exit();
        }

        // Check if product already exists in the cart with the same size
        $sqlCheck = "SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND product_size = ?";
        $stmtCheck = sqlsrv_query($conn, $sqlCheck, array($user_id, $product_id, $product_size));

        if ($stmtCheck === false) {
            die("Error in SELECT query: " . print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_has_rows($stmtCheck)) {
            // Update the quantity
            $sqlUpdate = "UPDATE cart SET quantity = quantity + ?, updated_at = GETDATE() WHERE user_id = ? AND product_id = ? AND product_size = ?";
            $stmtUpdate = sqlsrv_query($conn, $sqlUpdate, array($quantity, $user_id, $product_id, $product_size));

            if ($stmtUpdate === false) {
                die("Error in UPDATE query: " . print_r(sqlsrv_errors(), true));
            }
        } else {
            // Insert a new row with product_size
            $sqlInsert = "INSERT INTO cart (user_id, product_id, quantity, product_size, created_at, updated_at) VALUES (?, ?, ?, ?, GETDATE(), GETDATE())";
            $stmtInsert = sqlsrv_query($conn, $sqlInsert, array($user_id, $product_id, $quantity, $product_size));

            if ($stmtInsert === false) {
                die("Error in INSERT query: " . print_r(sqlsrv_errors(), true));
            }
        }

        header("Location: cart.php?status=success");
        exit();
    } else {
        header("Location: addtobag.php?product_id=$product_id&error=invalid_product");
        exit();
    }
} else {
    header("Location: dashboard.php?status=error");
    exit();
}
?>