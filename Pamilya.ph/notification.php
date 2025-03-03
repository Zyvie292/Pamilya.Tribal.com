<?php
session_start();

// Check if there is a notification in the session
$notification = isset($_SESSION['notification']) ? $_SESSION['notification'] : null;

// Clear the notification after displaying
unset($_SESSION['notification']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .notification-container {
            position: relative;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px 30px;
            text-align: center;
            width: 100%;
            max-width: 400px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .notification-container h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .notification-container p {
            font-size: 16px;
            margin-bottom: 20px;
            color: #555;
        }

        .notification-container button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .notification-container button:hover {
            background-color: #218838;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php if ($notification): ?>
        <div class="notification-container">
            <h2>Notification</h2>
            <p><?php echo htmlspecialchars($notification, ENT_QUOTES, 'UTF-8'); ?></p>
            <button onclick="window.location.href='dashboard.php';">OK</button>
        </div>
    <?php else: ?>
        <div class="notification-container">
            <h2>No Notifications</h2>
            <p>You have no new notifications at this time.</p>
            <button onclick="window.location.href='dashboard.php';">Back to Home</button>
        </div>
    <?php endif; ?>
</body>
</html>