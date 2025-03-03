<?php
session_start();
require 'vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Include the database connection
require 'Connection.php'; // This will bring in the $conn variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $token = bin2hex(random_bytes(20)); // Generate a secure token
    $expireTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Convert to DATETIME format

    try {
        // Prepare the SQL query
        $sql = "UPDATE users1 SET reset_token = ?, token_expiry = ? WHERE email = ?";
        $params = array($token, $expireTime, $email);

        // Execute the query
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        if (sqlsrv_rows_affected($stmt) > 0) {
            // Configure PHPMailer for SMTP
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'pamilyamarketingteam@gmail.com'; // Your Gmail
            $mail->Password = 'xzlu rpsx mmee oinz'; // Use an App Password (not your Gmail password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL encryption
            $mail->Port = 465; // Port 465 for SSL

            // Email settings
            $mail->setFrom('pamilyamarketingteam@gmail.com', 'Pamilya E-commerce');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Password Reset";
            $resetLink = "http://localhost/PAMILYA-ECOMMERCE/Pamilya.ph/reset-password.php?token=$token";
            $mail->Body = "
                <p>Click the button below to reset your password:</p>
                <p><a href='$resetLink' style='background-color: #007BFF; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Reset Password</a></p>
                <p>If the button doesn't work, copy and paste this link into your browser:</p>
                <p><a href='$resetLink'>$resetLink</a></p>
                <p>REMINDER: This button and link is valid for 1 hour. Thank you!</p>
            ";

            if ($mail->send()) {
                $_SESSION['message'] = "✅ A password reset link has been sent to your email.";
            } else {
                $_SESSION['message'] = "❌ Failed to send email. Error: " . $mail->ErrorInfo;
            }
        } else {
            $_SESSION['message'] = "❌ No account found with this email.";
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "❌ Error: " . $e->getMessage();
    }

    // Redirect to index.php
    header("Location: index.php");
    exit();
}
?>