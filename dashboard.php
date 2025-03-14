<?php
include "Connection.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
    $error_message = urlencode("Please log in to access the dashboard");
    header("Location: login&register.php?error={$error_message}");
    exit();
}

// Enhance session security (optional; best done at login)
session_regenerate_id(true);

// Get the username and user ID from the session
$username = htmlspecialchars($_SESSION['username']);
$user_id = $_SESSION['user_id'];

// Fetch all products
$sqlFetchProducts = "SELECT * FROM products";
$stmtFetchProducts = sqlsrv_query($conn, $sqlFetchProducts);

if ($stmtFetchProducts === false) {
    error_log("SQL Fetch Error: " . print_r(sqlsrv_errors(), true));
    die("A database error occurred. Please try again later.");
}

$products = [];
while ($row = sqlsrv_fetch_array($stmtFetchProducts, SQLSRV_FETCH_ASSOC)) {
    $products[] = $row;
}

// Fetch notifications



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pamilya - eCommerce Website</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="./assets/images/logo/Pamilya.ico" type="image/x-icon">

  <!-- Stylesheets -->
  <link rel="stylesheet" href="./assets/css/style-prefix.css">
  
  <!-- Google Fonts -->
  <link
  rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOMU8sYt5e3Z0i5rRXb5V6z5e5r5v5r5v5r5v5r5"
    crossorigin="anonymous"   />

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Ionicons -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>

<body>
<div class="overlay" data-overlay></div>

<!-- - MODAL-->
<div class="modal" data-modal>

  <div class="modal-close-overlay" data-modal-overlay></div>

  <div class="modal-content">

    <button class="modal-close-btn" data-modal-close>
      <ion-icon name="close-outline"></ion-icon>
    </button>

    <div class="newsletter-img">
      <img src="./assets/images/indexpic.jpg" alt="subscribe newsletter" width="800" height="600">
    </div>


    </div>

  </div>

</div>


  <!--
    - HEADER
  -->

  <header>

    <div class="header-top">

      <div class="container">

        <ul class="header-social-container">

          <li>
            <a href="https://www.facebook.com/pamilya.com.ph" class="social-link">
              <ion-icon name="logo-facebook"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-twitter"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-instagram"></ion-icon>
            </a>
          </li>

          <li>
            <a href="#" class="social-link">
              <ion-icon name="logo-linkedin"></ion-icon>
            </a>
          </li>
        </ul>

        <?php if ($username): ?>
        <div id="alert-box2" class="alert-box-dashboard ">
            Welcome, <?php echo $username; ?>!
        </div>
        <?php endif; ?>

        <div class="header-top-actions">

          <select name="language">

            <option value="en-US">English</option>

          </select>

        </div>

      </div>

    </div>

    <div class="header-main">

      <div class="container">
    
      <a href="dashboard.php" class="header-logo">
        <img src="./assets/images/logo/logo.png"><h1>PAMILYA</h1>
        <h2>Galing Pinoy! Galing Pinoy!</h2>
        </a>

        <div class="header-search-container">
    <form method="GET" action="">
        <input type="search" name="search" class="search-field" placeholder="Enter your product name..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
        <button type="submit" class="search-btn">
            <ion-icon name="search-outline"></ion-icon>
        </button>
        </form>
    </div>
        
       
  <!-- Notification, Bag, logout Button -->
<nav class="nav-icons">
  <div class="notification" id="notificationButton">
    <img src="./assets/images/icons/notification.svg" alt="Notification Icon" width="30" height="30">
    <span id="notifCount" class="count">0</span>
  <div class="notification-dropdown" id="notificationDropdown">
    <h6>Notifications</h6>
    <ul id="notificationList">
      <li>Loading notifications...</li>
    </ul>
  </div>
  </div>
  <div class="bagicon">
    <a href="cart.php">
      <img src="./assets/images/icons/online-shopping.svg" alt="Shopping Bag Icon" width="30" height="30">
    </a>
  </div>
  <div class="logout">
    <a href="logout.php">Logout</a>
  </div>
</nav>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationList = document.getElementById('notificationList');
    const notifCount = document.getElementById('notifCount');

    // Fetch notifications (async/await version)
    async function fetchNotifications() {
        try {
            const response = await fetch('application/get_notification.php');
            const data = await response.json();

            if (data.success) {
                notifCount.textContent = data.unreadCount > 0 ? data.unreadCount : '';

                notificationList.innerHTML = '';
                if (data.notifications.length > 0) {
                    data.notifications.forEach(notif => {
                        const li = document.createElement('li');
                        li.textContent = notif.message;
                        li.className = notif.is_read ? '' : 'unread';

                        // Add delete button
                        const deleteBtn = document.createElement('button');
                        deleteBtn.textContent = 'Delete';
                        deleteBtn.className = 'delete-btn';
                        deleteBtn.onclick = () => deleteNotification(notif.notif_id, li); // Fix the ID reference

                        li.appendChild(deleteBtn);
                        notificationList.appendChild(li);
                    });
                } else {
                    notificationList.innerHTML = '<li>No new notifications</li>';
                }
            } else {
                console.error('Error fetching notifications:', data.message);
                notificationList.innerHTML = '<li>Error loading notifications</li>';
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
            notificationList.innerHTML = '<li>Error loading notifications</li>';
        }
    }

    // Function to delete a notification (async/await version)
    async function deleteNotification(notificationId, listItem) {
        console.log("Deleting notification ID:", notificationId);
        if (!notificationId) {
            console.error("Notification ID is invalid");
            return;
        }

        try {
            const response = await fetch('application/delete_notification.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ notif_id: notificationId }) // Ensure correct key
            });

            const data = await response.json();
            if (data.success) {
                listItem.remove(); // Remove from UI
                fetchNotifications(); // Refresh notifications
            } else {
                console.error('Error deleting notification:', data.message);
            }
        } catch (error) {
            console.error('Error deleting notification:', error);
        }
    }

    // Toggle dropdown visibility
    notificationButton.addEventListener('click', event => {
        event.stopPropagation();
        notificationDropdown.style.display = notificationDropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', event => {
        if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
            notificationDropdown.style.display = 'none';
        }
    });

    // Fetch notifications on load
    fetchNotifications();
});
</script>

 
 <!--add to bag -->
    <!-- JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const bagCountElement = document.getElementById('bagCount');

        // Fetch bag count
        function fetchBagCount() {
            fetch('api/get_bag_count.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update bag count
                        if (data.count > 0) {
                            bagCountElement.textContent = data.count;
                            bagCountElement.style.display = 'inline-block';
                        } else {
                            bagCountElement.style.display = 'none';
                        }
                    } else {
                        console.error('Error fetching bag count:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching bag count:', error);
                });
        }

        // Fetch bag count on page load
        fetchBagCount();

        // Optional: Refresh bag count periodically (e.g., every 30 seconds)
        setInterval(fetchBagCount, 30000);
    });
</script>
<!--End of add to bag-->
    

   
    

      </div>

    </div>

  </header>


<!--- MAIN-->
<main>

<!-- Big Image in home -->
<div class="banner">

      <div class="container">

        <div class="slider-container has-scrollbar">

          <div class="slider-item">

            <img src="./assets/images/banner4.jpg" alt="women's latest fashion sale" class="banner-img">


          </div>

          <div class="slider-item">

            <img src="./assets/images/banner-2.png" alt="modern sunglasses" class="banner-img">

            <div class="banner-content">

          </div>

          <!-- <div class="slider-item">

            <img src="./assets/images/banner-3.jpg" alt="new fashion summer sale" class="banner-img">

            <div class="banner-content">

              <p class="banner-subtitle">Sale Offer</p>

              <h2 class="banner-title">New fashion summer sale</h2>

              <p class="banner-text">
                starting at ₱ <b>29</b>.99
              </p>

              <a href="#" class="banner-btn">Shop now</a>

            </div> -->

          </div>

        </div>

      </div>

    </div>

  

<!-- Product from Admin show -->
        <!-- sidebar -->
        <nav class="horizontal-menu">
  <ul class="menu">
    <li class="menu-item">
      <button class="menu-btn">Accessories</button>
      <ul class="submenu">
        <li><a href="?category=Bracelet">Bracelet</a></li>
        <li><a href="?category=Necklace">Necklace</a></li>
        <li><a href="?category=Choker">Choker</a></li>
        <li><a href="?category=Head Dress">Head Dress</a></li>
        <li><a href="?category=Earrings">Earrings</a></li>
      </ul>
    </li>
    <li class="menu-item">
      <button class="menu-btn">Apparel</button>
      <ul class="submenu">
        <li><a href="?category=Abaya">Abaya</a></li>
        <li><a href="?category=Barong">Barong</a></li>
        <li><a href="?category=Belt">Belt</a></li>
        <li><a href="?category=Bow Tie">Bow Tie</a></li>
        <li><a href="?category=Blazer">Blazer</a></li>
        <li><a href="?category=Blouse">Blouse</a></li>
        <li><a href="?category=Chaleko">Chaleko</a></li>
        <li><a href="?category=Dress">Dress</a></li>
        <li><a href="?category=Filipiniana">Filipiniana</a></li>
        <li><a href="?category=Hat">Hat</a></li>
        <li><a href="?category=Jacket/Kimono">Jacket/Kimono</a></li>
        <li><a href="?category=Malong">Malong</a></li>
        <li><a href="?category=Necktie">Necktie</a></li>
        <li><a href="?category=Polo/Shirts">Polo/Shirts</a></li>
        <li><a href="?category=Scarf">Scarf</a></li>
        <li><a href="?category=Skirt">Skirt</a></li>
      </ul>
    </li>
    <li class="menu-item">
      <button class="menu-btn">Souvenir</button>
      <ul class="submenu">
        <li><a href="?category=Brass">Brass</a></li>
        <li><a href="?category=Case">Case</a></li>
        <li><a href="?category=Name Plate">Name Plate</a></li>
        <li><a href="?category=Doll">Doll</a></li>
        <li><a href="?category=Key Chain">Key Chain</a></li>
        <li><a href="?category=Luggage Tag">Luggage Tag</a></li>
        <li><a href="?category=Musical Instrument">Musical Instrument</a></li>
        <li><a href="?category=Ref Magnet">Ref Magnet</a></li>
        <li><a href="?category=Table Runner">Table Runner</a></li>
      </ul>
    </li>
    <li class="menu-item">
      <button class="menu-btn">Bags and Wallets</button>
      <ul class="submenu">
        <li><a href="?category=Bag">Bag</a></li>
        <li><a href="?category=Wallets">Wallets</a></li>
        <li><a href="?category=Purse">Purse</a></li>
      </ul>
    </li>
  </ul>
</nav>

<div class="container-product">
    <div class="container-product-inside">
        <h1>Our Products</h1>

        <?php
        // Get search query and selected category from URL
        $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : null;
        $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;

        if ($selectedCategory) {
            echo "<p>Showing: " . htmlspecialchars($selectedCategory) . "</p>";
        } elseif ($searchQuery) {
            echo "<p>Search Results for: '" . htmlspecialchars($searchQuery) . "'</p>";
        } else {
            echo "<p>All Products</p>";
        }

        // Prepare SQL Query
        $sqlFetch = "SELECT * FROM products WHERE 1=1"; // Base query
        $params = [];

        if ($selectedCategory) {
            $sqlFetch .= " AND category = ?";
            $params[] = $selectedCategory;
        }

        if ($searchQuery) {
            $sqlFetch .= " AND name LIKE ?";
            $params[] = "%" . $searchQuery . "%"; // Use wildcards for partial matches
        }

        // Execute Query
        $stmtFetch = sqlsrv_query($conn, $sqlFetch, $params);

        if ($stmtFetch && sqlsrv_has_rows($stmtFetch)) {
            echo "<div class='product-grid'>";
            while ($product = sqlsrv_fetch_array($stmtFetch, SQLSRV_FETCH_ASSOC)) {
                echo "<div class='product-card'>";
                echo "<div class='product-card-content'>";

                // Get Image Paths
                $image1 = !empty($product['image_path']) ? htmlspecialchars($product['image_path']) : 'placeholder.jpg';
                $image2 = !empty($product['image_path_2']) ? htmlspecialchars($product['image_path_2']) : 'placeholder.jpg';

                // Image Slider for Product
                echo "<div class='product-slider'>
                        <img class='slide' src='$image1' alt='Product Image 1'>
                        <img class='slide' src='$image2' alt='Product Image 2'>
                      </div>";

                echo "<p class='product-name'>" . html_entity_decode($product['name']) . "</p>";
                echo "<p class='category'>" . html_entity_decode($product['category']) . "</p>";
                echo "<p class='price'>₱" . number_format($product['price'], 2) . "</p>";

                // Add to Cart Button
                echo "<form method='GET' action='addtobag.php'>
                        <input type='hidden' name='product_id' value='" . $product['id'] . "'>
                        <button type='submit' class='btn-add-to-cart'>Add to Bag</button>
                    </form>";
                echo "</div>"; // End of product-card-content
                echo "</div>"; // End product-card
            }
            echo "</div>"; // End product-grid
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
  const sliders = document.querySelectorAll(".product-slider");

  sliders.forEach(slider => {
      let images = slider.querySelectorAll(".slide");
      let index = 0;

      setInterval(() => {
          images.forEach(img => img.style.display = "none");
          images[index].style.display = "block";
          index = (index + 1) % images.length;
      }, 2000); // Change image every 2 seconds
  });
});
</script>

</header>





  <!--- FOOTER-->
  <footer>

    <div class="footer-category">

      <div class="container">

        <h2 class="footer-category-title">Product directory</h2>

        <div class="footer-category-box">

          <h3 class="category-box-title">All Products:</h3>

          <a href="#" class="footer-category-link">Abaya</a>
          <a href="#" class="footer-category-link">Bag</a>
          <a href="#" class="footer-category-link">Barong</a>
          <a href="#" class="footer-category-link">Belt</a>
          <a href="#" class="footer-category-link">Blazer</a>
          <a href="#" class="footer-category-link">Blouse</a>
          <a href="#" class="footer-category-link">Bracelet</a>
          <a href="#" class="footer-category-link">Brass</a>
          <a href="#" class="footer-category-link">Chaleko</a>
          <a href="#" class="footer-category-link">Choker</a>
          <a href="#" class="footer-category-link">Dress</a>
          <a href="#" class="footer-category-link">Doll</a>
          <a href="#" class="footer-category-link">Earrings</a>
          <a href="#" class="footer-category-link">Filipiniana</a>
          <a href="#" class="footer-category-link">Hat</a>
          <a href="#" class="footer-category-link">Head Dress</a>
          <a href="#" class="footer-category-link">Jacket/Kimono</a>
          <a href="#" class="footer-category-link">Key Chain</a>
          <a href="#" class="footer-category-link">Luggage Tag</a>
          <a href="#" class="footer-category-link">Malong</a>
          <a href="#" class="footer-category-link">Musical Instrument</a>
          <a href="#" class="footer-category-link">Name Plate</a>
          <a href="#" class="footer-category-link">Necklace</a>
          <a href="#" class="footer-category-link">Necktie</a>
          <a href="#" class="footer-category-link">Pants</a>
          <a href="#" class="footer-category-link">Polo/Shirts</a>
          <a href="#" class="footer-category-link">Purse</a>
          <a href="#" class="footer-category-link">Ref Magnet</a>
  	      <a href="#" class="footer-category-link">Ring</a>
          <a href="#" class="footer-category-link">Scarf</a>
          <a href="#" class="footer-category-link">Skirt</a>
          <a href="#" class="footer-category-link">Table Runner</a>
          <a href="#" class="footer-category-link">Wallets</a>
          
        </div>
<!-- insert here if ever another-->
      </div>
    </div>

    <div class="footer-nav">

      <div class="container">

        <ul class="footer-nav-list">

          <li class="footer-nav-item">
            <h2 class="nav-title">Popular Categories</h2>
          </li>

          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Tnalak Souvenir</a>
          </li>


          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">T'nalak bag</a>
          </li>

          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Tnalak Accessories</a>
          </li>

          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Tnalak Wears</a>
          </li>

        </ul>

        <ul class="footer-nav-list">
        
          <li class="footer-nav-item">
            <h2 class="nav-title">Products</h2>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Prices drop</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">New products</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Best sales</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Contact us</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Sitemap</a>
          </li>
        
        </ul>

        <ul class="footer-nav-list">
        
          <li class="footer-nav-item">
            <h2 class="nav-title">Our Company</h2>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Delivery</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Legal Notice</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Terms and conditions</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">About us</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Secure payment</a>
          </li>
        
        </ul>

        <ul class="footer-nav-list">
        
          <li class="footer-nav-item">
            <h2 class="nav-title">Services</h2>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Prices drop</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">New products</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Best sales</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Contact us</a>
          </li>
        
          <li class="footer-nav-item">
            <a href="#" class="footer-nav-link">Sitemap</a>
          </li>
        
        </ul>

        <ul class="footer-nav-list">

          <li class="footer-nav-item">
            <h2 class="nav-title">Contact</h2>
          </li>

          <li class="footer-nav-item flex">
            <div class="icon-box">
              <ion-icon name="location-outline"></ion-icon>
            </div>

            <address class="content">
              Lake Sebu, South Cotabato
              Region XII, Philippines
            </address>
          </li>

          <li class="footer-nav-item flex">
            <div class="icon-box">
              <ion-icon name="call-outline"></ion-icon>
            </div>

            <a href="tel:+63 935 7453 937" class="footer-nav-link">+63 935 7453 937</a>
          </li>

          <li class="footer-nav-item flex">
            <div class="icon-box">
              <ion-icon name="mail-outline"></ion-icon>
            </div>

            <a href="https://mail.google.com/mail/u/0/#inbox/FMfcgzQZSjlBpGjFsPncMMWbDPzMCJfj?compose=new" class="footer-nav-link">pamilyamarketingteam@gmail.com</a>
          </li>

        </ul>

        <ul class="footer-nav-list">

          <li class="footer-nav-item">
            <h2 class="nav-title">Follow Us</h2>
          </li>

          <li>
            <ul class="social-link">

              <li class="footer-nav-item">
                <a href="https://www.facebook.com/pamilya.com.ph" class="footer-nav-link">
                  <ion-icon name="logo-facebook"></ion-icon>
                </a>
              </li>

              <li class="footer-nav-item">
                <a href="#" class="footer-nav-link">
                  <ion-icon name="logo-twitter"></ion-icon>
                </a>
              </li>

              <li class="footer-nav-item">
                <a href="#" class="footer-nav-link">
                  <ion-icon name="logo-linkedin"></ion-icon>
                </a>
              </li>

              <li class="footer-nav-item">
                <a href="#" class="footer-nav-link">
                  <ion-icon name="logo-instagram"></ion-icon>
                </a>
              </li>

            </ul>
          </li>

        </ul>

      </div>

    </div>

    <div class="footer-bottom">

      <div class="container">

        <img src="./assets/images/payment.png" alt="payment method" class="payment-img">

        <p class="copyright">
          Copyright &copy; <a href="#">Pamilya</a> all rights reserved.
        </p>

      </div>

    </div>

  </footer>



  <!--
    - custom js link
  -->
  <script src="./assets/js/script.js"></script>

  <!--
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>