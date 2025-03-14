<?php
// Start the session
session_start();

// Include your database connection file
require_once 'Connection.php';

// Check if product_id is passed via GET
if (isset($_GET['product_id'])) {
    $product_id = htmlspecialchars($_GET['product_id']);

    // Fetch product details from the database
    $sqlFetch = "SELECT * FROM products WHERE id = ?";
    $stmtFetch = sqlsrv_query($conn, $sqlFetch, array($product_id));

    if ($stmtFetch && $product = sqlsrv_fetch_array($stmtFetch, SQLSRV_FETCH_ASSOC)) {
        // Get Image Paths
        $image1 = !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'placeholder.jpg';
        $image2 = !empty($product['image_path_2']) ? htmlspecialchars($product['image_path_2']) : 'placeholder.jpg';

        // Predefined standard sizes
        $standardSizes = ['S', 'M', 'L', 'XL', 'XXL'];

        // Fetch available sizes for this product's category
        $category = strtolower($product['category']); // Convert to lowercase for case-insensitive comparison
        
        $sizeCategories = ['dress', 'jacket/kimono', 'barong','blouse','pants','filipiniana','polo/shirts','skirt',
    'doll','chaleko','bag','abaya','blazer']; // Categories that require sizes
        $noSizeCategories = ['necklace', 'head dress', 'brass', 'earrings','choker','bracelet','key chain','luggage tag','malong',
        'purse','ref magnet','ring','Scarf','table runner','wallets','choker','bow tie','belt']; // Categories without sizes

        $sizes = [];
        if (in_array($category, $sizeCategories)) {
            $sqlSizes = "SELECT DISTINCT product_size FROM products WHERE category = ?";
            $stmtSizes = sqlsrv_query($conn, $sqlSizes, array($category));

            while ($row = sqlsrv_fetch_array($stmtSizes, SQLSRV_FETCH_ASSOC)) {
                $sizes[] = strtoupper($row['product_size']); // Convert to uppercase for consistency
            }

            // Ensure only valid standard sizes appear in the dropdown
            $sizes = array_intersect($standardSizes, $sizes);
            if (empty($sizes)) {
                $sizes = $standardSizes; // Default sizes if none are stored in DB
            }
        }

        // Display the product details and quantity selection form
        echo "<div class='product-details'>";
        echo "    <h1>ADD TO BAG</h1>";
        echo "    <h2>" . htmlspecialchars($product['name']) . "</h2>";
        echo "    <p>Category: " . htmlspecialchars($product['category']) . "</p>";
        echo "    <p>" . htmlspecialchars($product['description']) . "</p>";
        echo "    <p>Price: ₱" . number_format($product['price'], 2) . "</p>";

        // Image Display with Main Image and Small Image Below (Left Aligned)
        echo "    <div class='image-container'>";
        echo "        <img class='main-image' src='$image1' alt='Main Image'>";
        echo "        <div class='small-image-container'>";
        echo "            <img class='small-image' src='$image2' alt='Small Image'>";
        echo "        </div>";
        echo "    </div>";

        // Add to Bag Form
        echo "    <form method='POST' action='process_addtobag.php'>";
        echo "        <input type='hidden' name='product_id' value='" . htmlspecialchars($product['id']) . "'>";
        
        // Size Selection Dropdown (Enabled only for certain categories)
        if (in_array($category, $sizeCategories)) {
            echo "        <label for='size'>Size:</label>";
            echo "        <select name='size' id='size' required>";
            echo "            <option value='' disabled selected>Select a size</option>";
            foreach ($sizes as $size) {
                echo "            <option value='" . htmlspecialchars($size) . "'>" . htmlspecialchars($size) . "</option>";
            }
            echo "        </select>";
        } elseif (in_array($category, $noSizeCategories)) {
            echo "        <label for='size'>Size:</label>";
            echo "        <select name='size' id='size' disabled>";
            echo "            <option value='' selected>No size required</option>";
            echo "        </select>";
        }

        // Quantity Input
        echo "        <label for='quantity'>Quantity:</label>";
        echo "        <input type='number' name='quantity' id='quantity' value='1' min='1' required>";
        
        echo "        <button type='submit'>Add to Bag</button>";
        echo "    </form>";
        echo "</div>";
    } else {
        echo "<p>Product not found.</p>";
    }
} else {
    echo "<p>No product selected.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <style>
        /* General styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #ff69b4; /* Light pink */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .product-details {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow: hidden;
        }

        h2 {
            color: #444;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        p {
            font-size: 1rem;
            margin: 10px 0;
            color: #666;
        }

        /* Image Container */
        .image-container {
            text-align: center;
        }

        .main-image {
            max-width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .small-image-container {
    display: flex;
    justify-content: flex-start; /* Aligns small image to the left */
    width: 100%;
    padding-left: 10px; /* Optional: Adjust left spacing */
    }

    .small-image {
        max-width: 30%;
        height: auto;
        border-radius: 5px;
    }


        form {
            margin-top: 20px;
        }

        label {
            display: block;
            font-size: 1rem;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        input[type="number"]:focus {
            border-color: #ff69b4;
            outline: none;
            box-shadow: 0 0 4px rgba(255, 105, 180, 0.5);
        }

        button {
            width: 100%;
            padding: 12px 20px;
            font-size: 1rem;
            color: #fff;
            background-color: #ff69b4; /* Light pink */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ff4081; /* Darker pink */
        }
    </style>
</head>
</html>