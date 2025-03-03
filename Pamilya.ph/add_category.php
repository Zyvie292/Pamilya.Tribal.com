<?php
include "Connection.php";  // Ensure this correctly initializes $conn

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newCategory = trim($_POST["category_name"]);

    if (!empty($newCategory)) {
        // Check if category already exists
        $checkQuery = "SELECT category FROM products WHERE category = ?";
        $checkStmt = sqlsrv_query($conn, $checkQuery, array($newCategory));

        if (sqlsrv_has_rows($checkStmt)) {
            echo "<script>alert('Category already exists!');</script>";
        } else {
            // Insert new category if it doesn't exist
            $query = "INSERT INTO products (category) VALUES (?)";
            $params = array($newCategory);
            $stmt = sqlsrv_query($conn, $query, $params);

            if ($stmt) {
                echo "<script>alert('Category added successfully!'); window.location.href='add_category.php';</script>";
            } else {
                echo "<script>alert('Error adding category');</script>";
            }
        }
    } else {
        echo "<script>alert('Category name cannot be empty');</script>";
    }
}

// Fetch categories dynamically
$fetchQuery = "SELECT DISTINCT category FROM products ORDER BY category ASC";
$fetchStmt = sqlsrv_query($conn, $fetchQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 500px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        .box { width: 100%; padding: 10px; }
        button { background-color: #28a745; color: white; padding: 10px; border: none; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Category</h2>

    <form action="" method="POST">
        <div class="form-group">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" class="box" required>
        </div>
        <button type="submit">Add Category</button>
    </form>

    <h2>Product Categories</h2>
    <select class="box" name="category">
        <?php
        while ($row = sqlsrv_fetch_array($fetchStmt, SQLSRV_FETCH_ASSOC)) {
            echo "<option value='" . htmlspecialchars($row['category']) . "'>" . htmlspecialchars($row['category']) . "</option>";
        }
        ?>
    </select>
</div>

</body>
</html>