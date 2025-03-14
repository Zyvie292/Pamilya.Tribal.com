<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/images/logo/Pamilya.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/admin-style.css">
    <title>Admin Login - PAMILYA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Alert Box Styling */
        .alert-box {
            position: absolute;
            top: -100px; /* Initially hidden */
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 400px;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            border-radius: 8px;
            z-index: 50; /* Ensure it stays above everything */
            opacity: 0;
            transition: top 0.5s ease-in-out, opacity 0.5s ease-in-out;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-pink-100 flex items-center justify-center min-h-screen relative">
    
    <!-- Alert Box for Errors & Messages -->
    <?php if (isset($_GET['error']) || isset($_GET['logout_message'])): ?>
        <div class="alert-box 
            <?php echo isset($_GET['error']) ? 'bg-red-500 text-white' : 'bg-green-500 text-white'; ?>">
            <?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error']) : htmlspecialchars($_GET['logout_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Admin Login Container -->
    <div class="w-full max-w-md p-8 bg-white shadow-lg rounded-2xl text-center relative z-10">
        <div class="flex flex-col items-center mb-6">
            <img src="./assets/images/logo/logo.png" class="w-16 h-16 mb-2" alt="PAMILYA Logo">
            <h1 class="text-2xl font-bold text-pink-600">PAMILYA</h1>
            <h2 class="text-sm text-pink-500">Galing Pinoy! Galing Pinoy!</h2>
        </div>
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Admin Login</h2>
        <form method="POST" action="administration.php" class="space-y-4">
            <div>
                <label for="login-username" class="block text-left text-gray-600 text-sm">Username</label>
                <input type="text" id="login-username" name="username" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:outline-none">
            </div>
            <div>
                <label for="login-password" class="block text-left text-gray-600 text-sm">Password</label>
                <input type="password" id="login-password" name="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-400 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-pink-500 text-white py-2 rounded-lg hover:bg-pink-600 transition">Login</button>
        </form>
    </div>

    <!-- Login Success Message -->
    <?php if (isset($_GET['login_message'])): ?>
        <div class="alert-box bg-blue-500 text-white">
            <?php echo htmlspecialchars($_GET['login_message']); ?>
        </div>
    <?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.querySelector('.alert-box');

    if (alertBox) {
        // Make the alert slide down smoothly
        setTimeout(() => {
            alertBox.style.top = "20px"; 
            alertBox.style.opacity = "1";
        }, 100); // Small delay for smooth animation

        // Hide after 3 seconds
        setTimeout(() => {
            alertBox.style.top = "-50px"; 
            alertBox.style.opacity = "0";
        }, 3000);
    }
});
</script>

<!-- <script src="./assets/admin.js"></script> -->

</body>
</html>