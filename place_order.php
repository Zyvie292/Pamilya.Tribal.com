<?php
session_start();
require_once 'Connection.php';

// Include Composer's autoloader and PHPMailer
require 'vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';

// Fetch cart items with product size
$sqlFetchCart = "
    SELECT c.id AS cart_id, p.name AS product_name, p.price, c.product_size, c.quantity 
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?";
$stmtFetchCart = sqlsrv_query($conn, $sqlFetchCart, array($user_id));

if ($stmtFetchCart === false) {
    die("Error fetching cart items: " . print_r(sqlsrv_errors(), true));
}

$grandTotal = 0;
$cartItems = [];
while ($cartItem = sqlsrv_fetch_array($stmtFetchCart, SQLSRV_FETCH_ASSOC)) {
    $totalPrice = $cartItem['price'] * $cartItem['quantity'];
    $grandTotal += $totalPrice;
    $cartItems[] = $cartItem;
}

// Check if the cart is empty
if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}

// Fetch the user's email
$sqlFetchUserEmail = "SELECT username, email FROM users1 WHERE id = ?";
$stmtFetchEmail = sqlsrv_query($conn, $sqlFetchUserEmail, array($user_id));

if ($stmtFetchEmail === false) {
    die("Error fetching user email: " . print_r(sqlsrv_errors(), true));
}

$emailData = sqlsrv_fetch_array($stmtFetchEmail, SQLSRV_FETCH_ASSOC);
if (!$emailData) {
    die("No email found for the user.");
}

$username = htmlspecialchars($emailData['username']) ?? 'Customer';
$userEmail = filter_var($emailData['email'], FILTER_SANITIZE_EMAIL);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = isset($_POST['address']) ? trim(htmlspecialchars($_POST['address'])) : '';
    $paymentMethod = isset($_POST['payment_method']) ? trim(htmlspecialchars($_POST['payment_method'])) : '';

    if (empty($address)) {
        $error = "Address is required.";
    } elseif (empty($paymentMethod)) {
        $error = "Please select a payment method.";
    } else {
        sqlsrv_begin_transaction($conn);

        try {
            // Insert order
            $sqlInsertOrder = "
                INSERT INTO orders (user_id, total_price, shippingAddress, payment_method, order_date)
                OUTPUT INSERTED.id
                VALUES (?, ?, ?, ?, GETDATE())";
            $paramsOrder = array($user_id, $grandTotal, $address, $paymentMethod);
            $stmtInsertOrder = sqlsrv_query($conn, $sqlInsertOrder, $paramsOrder);

            if ($stmtInsertOrder === false) {
                throw new Exception("Error inserting order: " . print_r(sqlsrv_errors(), true));
            }

            $orderData = sqlsrv_fetch_array($stmtInsertOrder, SQLSRV_FETCH_ASSOC);
            $orderId = $orderData['id'] ?? null;

            if (!$orderId) {
                throw new Exception("Failed to retrieve the inserted order ID.");
            }

            // Insert order items
            $sqlInsertOrderItem = "INSERT INTO order_items (order_id, product_name, quantity, price, product_size) VALUES (?, ?, ?, ?, ?)";
            foreach ($cartItems as $item) {
                $paramsItem = array($orderId, $item['product_name'], $item['quantity'], $item['price'], $item['product_size']); // Ensure correct column name
                $stmtInsertOrderItem = sqlsrv_query($conn, $sqlInsertOrderItem, $paramsItem);

                if ($stmtInsertOrderItem === false) {
                    throw new Exception("Error inserting order item: " . print_r(sqlsrv_errors(), true));
                }
            }

            // Clear cart after successful order
            $sqlClearCart = "DELETE FROM cart WHERE user_id = ?";
            $stmtClearCart = sqlsrv_query($conn, $sqlClearCart, array($user_id));
            if ($stmtClearCart === false) {
                throw new Exception("Error clearing cart: " . print_r(sqlsrv_errors(), true));
            }

            sqlsrv_commit($conn);

            // Send order confirmation email
            $subject = "Thank You for Your Order!";
            $message = "<p>Dear $username,</p>";
            $message .= "<p>Thank you for shopping with us at <strong>Pamilya</strong>! We're excited to process your order.</p>";
            $message .= "<p><strong>Order Summary:</strong></p>";
            $message .= "<ul>";

            foreach ($cartItems as $item) {
                $message .= "<li><strong>{$item['product_name']}</strong> (Size: {$item['product_size']}) (x{$item['quantity']}): ₱" . number_format($item['price'] * $item['quantity'], 2) . "</li>";
            }

            $message .= "</ul>";
            $message .= "<p><strong>Sub-Total:</strong> ₱" . number_format($grandTotal, 2) . "</p>";
            $message .= "<p><strong>Shipping Address:</strong> $address</p>";
            $message .= "<p><strong>Payment Method:</strong> $paymentMethod</p>";
            $message .= "<p>We will send you an email with payment details shortly, including the shipping amount.</p>";
            $message.="<p>Thank you for supporting local sellers!</p>";
            $message.="<p>Warm regards,
                        Pamilya Team</p>";

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'pamilyamarketingteam@gmail.com';
                $mail->Password = 'xzlu rpsx mmee oinz';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;
                $mail->setFrom('pamilyamarketingteam@gmail.com', 'Pamilya E-commerce');
                $mail->addAddress($userEmail);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;
                $mail->send();
                
                $_SESSION['notification'] = "Order confirmed and email sent successfully!";
            } catch (Exception $e) {
                $_SESSION['notification'] = "Order confirmed, but email failed to send. Error: " . $e->getMessage();
            }

            // Insert system-generated notification
            $notificationMessage = "Your order #$orderId has been successfully placed. Please check your email now.";
            $sqlInsertNotification = "
                INSERT INTO notifications (user_id, message, created_at, is_read) 
                VALUES (?, ?, GETDATE(), 0)
            ";
            $stmtInsertNotification = sqlsrv_query($conn, $sqlInsertNotification, array($user_id, $notificationMessage));

            if ($stmtInsertNotification === false) {
                throw new Exception("Error inserting notification: " . print_r(sqlsrv_errors(), true));
            }

            header("Location: order_confirmation.php?order_id=$orderId");
            exit();
        } catch (Exception $e) {
            sqlsrv_rollback($conn);
            $error = "Error processing your order: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }
        textarea, input[type="radio"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }
        button {
            background-color:rgb(233, 0, 144);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color:rgb(255, 125, 223);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Place Your Order</h1>
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="address">Shipping Address:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="payment_method">Payment Method:</label>
            <div>
                <input type="radio" id="gcash" name="payment_method" value="GCash" required>
                <label for="gcash">GCash</label>
                <input type="radio" id="bank_transfer" name="payment_method" value="Bank Transfer" required>
                <label for="bank_transfer">Bank Transfer</label>
            </div>
            <button type="submit">Confirm Order</button>
        </form>
    </div>
</body>
</html>
