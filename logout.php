<?php
session_start(); // Start the session

// Destroy all session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page with a success message
header("Location: index.php?message=You%20have%20been%20logged%20out%20successfully");
exit();