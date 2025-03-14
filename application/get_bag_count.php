<?php
// Start session to get user data
session_start();

// Include database connection
require_once 'Connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

try {
    // Prepare the SQL query to count items in the user's cart
    $stmt = $db->prepare("SELECT COUNT(*) AS bag_count FROM cart WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'success' => true,
            'count' => (int) $result['bag_count'],
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Unable to retrieve bag count',
        ]);
    }
} catch (PDOException $e) {
    // Handle database errors
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
    ]);
}