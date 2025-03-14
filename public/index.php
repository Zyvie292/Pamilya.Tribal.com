<?php 
session_start();
require_once __DIR__ . '/../Connection.php';
require_once __DIR__ . '/../vendor/autoload.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pamilya - eCommerce Website</title>

  <link rel="shortcut icon" href="/assets/images/logo/Pamilya.ico" type="image/x-icon">

  <!-- <link rel="stylesheet" href="./assets/css/style.css"> -->
  <link rel="stylesheet" href="/assets/css/style-prefix.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js" integrity="sha512-/HL24m2nmyI2+ccX+dSHphAHqLw60Oj5sK8jf59VWtFWZi9vx7jzoxbZmcBeeTeCUc7z1mTs3LfyXGuBU32t+w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script type="module" src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@latest/dist/ionicons/ionicons.js"></script>
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



<!--- HEADER-->
<header>

    <div class="header-top">
<!-- Link in homepage{social Media} and small container -->
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

        <div class="header-alert-news">
          <p>
            <b>Pamilya</b>
            Shop
          </p>
        </div>

        <div class="header-top-actions">

          <select name="language">

            <option value="en-US">English</option>

          </select>

        </div>

      </div>
</div>

    <div class="header-main">

      <div class="container">

        <a href="index.php" class="header-logo">
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
        
        <div class="login-dropdown-container">
        <button class="dropdown-button"><ion-icon class="fa-solid fa-user" style="color: #fb0488; margin-top: 5px; margin-left: -5px;"></ion-icon><b>Buyer</b></button>
    </div>

    <!-- Overlay -->
    <div class="overlay-login"></div>
    
    <!-- Dropdown Panel -->
    <div class="dropdown-panel-login">
        <h3>Register & Login</h3>
        <a href="#" id="open-register">Register</a>
        <a href="#" id="open-login">Log In</a>
        <button class="dropdown-button close">Close</button>
    </div>

      

<!-- Login Overlay -->
<div id="overlay-loginform" class="overlay-loginform">
    <div class="login-container">
        <span class="close-login" id="close-btn-login">&times;</span>
        <form method="POST" action="login&register.php">
            <h2>Login</h2>
            <div class="input-group">
                <label for="login-username">Username</label>
                <input type="text" id="admin-username" name="username" required>
            </div>
            <div class="input-group">
                <label for="login-password">Password</label>
                <input type="password" id="admin-password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <br>
            <p class="register-link">Don't have an account? <a href="#" id="open-register">Register</a> here</p>
            <a href="#" id="open-password">Forgot Password?</a> 
           
        </form>
    </div>
</div>
<div id="overlay-forgot-password" class="overlay-forgot-password">
    <div class="password-container">
        <span class="close-btn-forgotpass" id="close-btn-Forgot-Password">&times;</span>
        <form method="POST" action="send-reset-link.php">
            <h2>Reset Password</h2>
            <p>Enter your email to receive a password reset link.</p>
            <input type="email" name="email" required>
            <button class="btn-forgotpass" type="submit">Send Reset Link</button>
        </form>
    </div>
</div>

<?php
if (isset($_SESSION['message'])) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; margin-bottom: 0px; border-radius: 5px; item-align:center; top:0; z-index:3000; position: fixed; left: 37%; transition: opacity 0.5s ease, top 0.5s ease; '>
        " . $_SESSION['message'] . "
    </div>";
    unset($_SESSION['message']); // Clear after displaying
} 
?>

<!-- Forgot Password Overlay (Moved Outside Login Form) -->


<!-- Register Overlay -->
<div id="overlay-registerform" class="overlay-registerform">
    <div class="register-container">
        <span class="close-btn-register" id="close-btn-register">&times;</span>
        <form method="POST" action="process_registration.php">
    <h2>Register</h2>
    <div class="input-group">
        <label for="register-username">Username</label>
        <input type="text" id="register-username" name="username" required>
    </div>
    <div class="input-group">
        <label for="register-email">Email</label>
        <input type="email" id="register-email" name="email" required>
    </div>
    <div class="input-group">
        <label for="register-password">Password</label>
        <input type="password" id="register-password" name="password" required>
    </div>
    <div class="input-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn">Register</button>
    <p class="login-link">Already have an account? <a href="#" id="open-login">Log In</a></p>
</form>
    </div>
</div>


<!-- alert box -->
<?php if (isset($_GET['success'])): ?>
    <div class="alert-box alert-success">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert-box alert-error">
        <?php
        switch ($_GET['error']) {
            case 'password_mismatch':
                echo "Passwords do not match. Please try again.";
                break;
            case 'username_exists':
                echo "Username already exists. Please choose a different username.";
                break;
            case 'user_not_found':
                echo "Username not found. Please register or try again.";
                break;
            case 'invalid_password':
                echo "Invalid password. Please try again.";
                break;
            default:
                echo "An unexpected error occurred. Please try again.";
        }
        ?>
    </div>
<?php endif; ?>


<!-- logout alert -->
<?php if (isset($_GET['message'])): ?>
    <div id="alert-box" class="alert-box alert-success">
        <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
<?php endif; ?>


</div>

</div>




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
  <script src="/assets/js/script.js"></script>

  <!--
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>