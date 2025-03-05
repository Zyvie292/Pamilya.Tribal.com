<?php
include "Connection.php";
session_start();

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: login&register.php?error=" . urlencode("Please log in as admin"));
    exit();
}

$targetDir = "uploads/";

// Ensure the directory exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];

        $imagePath = null;
        $imagePath2 = null;

        // Handle first image upload
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
            $uniqueName = uniqid() . '-' . basename($_FILES['product_image']['name']);
            $imagePath = $targetDir . $uniqueName;
            move_uploaded_file($_FILES['product_image']['tmp_name'], $imagePath);
        }

        // Handle second image upload
        if (isset($_FILES['product_image_2']) && $_FILES['product_image_2']['error'] == 0) {
            $uniqueName2 = uniqid() . '-' . basename($_FILES['product_image_2']['name']);
            $imagePath2 = $targetDir . $uniqueName2;
            move_uploaded_file($_FILES['product_image_2']['tmp_name'], $imagePath2);
        }

        // Insert into database
        $sql = "INSERT INTO products (name, description, price, stock, category, image_path, image_path_2, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE())";
        $params = array($name, $description, $price, $stock, $category, $imagePath, $imagePath2);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    if (isset($_POST['update_product'])) {
        $id = $_POST['id'];
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $category = $_POST['category'];

        $query = "UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?";
        $params = [$name, $description, $price, $stock, $category];

        // Image handling for first image
        if (!empty($_FILES['product_image']['name'])) {
            $uniqueName = uniqid() . '-' . basename($_FILES['product_image']['name']);
            $target = $targetDir . $uniqueName;
            move_uploaded_file($_FILES['product_image']['tmp_name'], $target);
            
            $query .= ", image_path = ?";
            array_push($params, $target);
        }

        // Image handling for second image
        if (!empty($_FILES['product_image_2']['name'])) {
            $uniqueName2 = uniqid() . '-' . basename($_FILES['product_image_2']['name']);
            $target2 = $targetDir . $uniqueName2;
            move_uploaded_file($_FILES['product_image_2']['tmp_name'], $target2);
            
            $query .= ", image_path_2 = ?";
            array_push($params, $target2);
        }

        // Complete query
        $query .= " WHERE id = ?";
        array_push($params, $id);

        // Execute query
        $stmt = sqlsrv_query($conn, $query, $params);

        if ($stmt) {
            echo "<script>alert('Product updated successfully!'); window.location.href='manage_products.php';</script>";
        } else {
            echo "Error updating product: ";
            die(print_r(sqlsrv_errors(), true));
        }
    }

    if (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM products WHERE id = ?";
        $params = array($id);
        sqlsrv_query($conn, $sql, $params);
    }
}

// Fetch all products
$sqlFetch = "SELECT * FROM products";
$stmtFetch = sqlsrv_query($conn, $sqlFetch);

if ($stmtFetch === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/images/logo/Pamilya.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/admin-style.css">
    <title>Admin Login - PAMILYA</title>

    <link rel="shortcut icon" href="./assets/images/logo/Pamilya.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/css/admin-style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.js"></script>
</head>
<body>
<header class="admin-header">
    <div class="header-container">
      <a href="admin_dashboard.php" class="admin-logo">
        <img src="./assets/images/logo/adminlogo.svg">
      </a>
      <h4 class="menu">Manage Products</h4>
      <button class="menu-toggle">☰</button>
      <nav class="admin-nav">
        <ul>
          <li><a href="admin_dashboard.php">Home</a></li>
          <li><a href="add_category.php">Add Category</a></li>
          <li><a href="manage_users.php">Manage Users</a></li>
          <li><a href="view_orders.php">View Orders</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </nav>
    </div>
    <script>
   document.addEventListener("DOMContentLoaded", function () {
    console.log("JavaScript Loaded"); // Debugging message

    const menuToggle = document.querySelector(".menu-toggle");
    const adminNav = document.querySelector(".admin-nav");

    if (!menuToggle || !adminNav) {
        console.log("Menu elements not found!");
        return; // Stop script if elements are missing
    }

    menuToggle.addEventListener("click", function (event) {
        console.log("Menu Toggle Clicked");
        adminNav.classList.toggle("active");
        event.stopPropagation(); // Prevent immediate closing
    });

    // Close menu when clicking outside
    document.addEventListener("click", function (event) {
        if (!adminNav.contains(event.target) && !menuToggle.contains(event.target)) {
            adminNav.classList.remove("active");
        }
    });
});

function decodeHtmlEntities(text) {
    let textarea = document.createElement("textarea");
    textarea.innerHTML = text;
    return textarea.value;
}

  </script>
  </header>
  
    <main class="admin-main">
        <div class="container">
    <h2>ADD PRODUCT</h2>
    <form method="POST" enctype="multipart/form-data">
        <input class = "box" type="text" name="name" placeholder="Product Name" required>
        <textarea class = "box" name="description" placeholder="Product Description"></textarea>
        <input class = "box" type="number" step="0.01" name="price" placeholder="Price" required>
        <input class = "box" type="number" name="stock" placeholder="Stock">
        
        <!-- Category Select Dropdown -->
        <select class = "box" name="category" required>
                            <option class = "box" value="Abaya">Abaya</option>
                            <option class = "box" value="Bag">Bag</option>
                            <option class = "box" value="Barong">Barong</option>
                            <option class = "box" value="Belt">Belt</option>
                            <option class = "box" value="Bow Tie">Bow Tie</option>
                            <option class = "box" value="Blazer">Blazer</option>
                            <option class = "box" value="Blouse">Blouse</option>
                            <option class = "box" value="Bracelet">Bracelet</option>
                            <option class = "box" value="Brass">Brass</option>
                            <option class = "box" value="Chaleko">Chaleko</option>
                            <option class = "box" value="Choker">Choker</option>
                            <option class = "box" value="Dress">Dress</option>
                            <option class = "box" value="Doll">Doll</option>
                            <option class = "box" value="Earrings">Earrings</option>
                            <option class = "box" value="Filipiniana">Filipiniana</option>
                            <option class = "box" value="Hat">Hat</option>
                            <option class = "box" value="Head Dress">Head Dress</option>
                            <option class = "box" value="Jacket/Kimono">Jacket/Kimono</option>
                            <option class = "box" value="Key Chain">Key Chain</option>
                            <option class = "box" value="Luggage Tag">Luggage Tag</option>
                            <option class = "box" value="Malong">Malong</option>
                            <option class = "box" value="Musical Instrument">Musical Instrument</option>
                            <option class = "box" value="Name Plate">Name Plate</option>
                            <option class = "box" value="Necklace">Necklace</option>
                            <option class = "box" value="Necktie">Necktie</option>
                            <option class = "box" value="Pants">Pants</option>
                            <option class = "box" value="Polo/Shirts">Polo/Shirts</option>
                            <option class = "box" value="Purse">Purse</option>
                            <option class = "box" value="Ref Magnet">Ref Magnet</option>
                            <option class = "box" value="Ring">Ring</option>
                            <option class = "box" value="Scarf">Scarf</option>
                            <option class = "box" value="Scarf">Skirt</option>
                            <option class = "box" value="Table Runner">Table Runner</option>
                            <option class = "box" value="Wallets">Wallets</option>
        </select>

        <input class="box" type="file" name="product_image" accept="image/*" required>
        <input class="box" type="file" name="product_image_2" accept="image/*">
        <button class = "add-button" type="submit" name="add_product">Add Product</button>
    </form>

    <div class="table-container">
  <h2>Product List</h2>
  <table border="1">
    <thead>
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Category</th> <!-- New Category Column -->
        <th>Price</th>
        <th>Stock</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
</div>
            <?php while ($product = sqlsrv_fetch_array($stmtFetch, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td>                                        
                    <?php if ($product['image_path']): ?>
                        <img src="<?php echo $product['image_path']; ?>" width="100">
                    <?php else: ?>
                        No Image
                    <?php endif; ?>

                    <?php if ($product['image_path_2']): ?>
                        <img src="<?php echo $product['image_path_2']; ?>" width="100">
                    <?php else: ?>
                        No Second Image
                    <?php endif; ?>
                    </td>
                    <td><?php echo html_entity_decode($product['name']); ?></td>
                    <td><?php echo html_entity_decode($product['description']); ?></td>
                    <td><?php echo html_entity_decode($product['category']); ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td><?php echo $product['created_at']->format('Y-m-d H:i:s'); ?></td>
                    <td><?php echo $product['updated_at']->format('Y-m-d H:i:s'); ?></td>
                    <td>
                        <!-- Update Form -->
                        <form method="POST" enctype="multipart/form-data" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                            <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                            <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
                            <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

                            <!-- Category Select Dropdown for editing -->
                            <select name="category" required>
                            <option class = "box" value="Abaya">Abaya</option>
                            <option class = "box" value="Bag">Bag</option>
                            <option class = "box" value="Barong">Barong</option>
                            <option class = "box" value="Belt">Belt</option>
                            <option class = "box" value="Bow Tie">Bow Tie</option>
                            <option class = "box" value="Blazer">Blazer</option>
                            <option class = "box" value="Blouse">Blouse</option>
                            <option class = "box" value="Bracelet">Bracelet</option>
                            <option class = "box" value="Brass">Brass</option>
                            <option class = "box" value="Chaleko">Chaleko</option>
                            <option class = "box" value="Choker">Choker</option>
                            <option class = "box" value="Dress">Dress</option>
                            <option class = "box" value="Doll">Doll</option>
                            <option class = "box" value="Earrings">Earrings</option>
                            <option class = "box" value="Filipiniana">Filipiniana</option>
                            <option class = "box" value="Hat">Hat</option>
                            <option class = "box" value="Head Dress">Head Dress</option>
                            <option class = "box" value="Jacket/Kimono">Jacket/Kimono</option>
                            <option class = "box" value="Key Chain">Key Chain</option>
                            <option class = "box" value="Luggage Tag">Luggage Tag</option>
                            <option class = "box" value="Malong">Malong</option>
                            <option class = "box" value="Musical Instrument">Musical Instrument</option>
                            <option class = "box" value="Name Plate">Name Plate</option>
                            <option class = "box" value="Necklace">Necklace</option>
                            <option class = "box" value="Necktie">Necktie</option>
                            <option class = "box" value="Pants">Pants</option>
                            <option class = "box" value="Polo/Shirts">Polo/Shirts</option>
                            <option class = "box" value="Purse">Purse</option>
                            <option class = "box" value="Ref Magnet">Ref Magnet</option>
                            <option class = "box" value="Ring">Ring</option>
                            <option class = "box" value="Scarf">Scarf</option>
                            <option class = "box" value="Scarf">Skirt</option>
                            <option class = "box" value="Table Runner">Table Runner</option>
                            <option class = "box" value="Wallets">Wallets</option>
                            </select>

                            <input type="file" name="product_image" accept="image/*">
                            <button type="submit" name="update_product">Update</button>
                        </form>
                        <!-- Delete Form -->
                        <form method="POST" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    </main>
</body>
</html>