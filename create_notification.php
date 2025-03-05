<?php include "Connection.php";

// Get user ID and message from POST data
$user_id = $_POST['user_id'];
$message = $_POST['message'];

if (!empty($user_id) && !empty($message)) {
    // Insert notification into the database
    $query = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
    $params = [$user_id, $message];
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    echo json_encode(["success" => true, "message" => "Notification created successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
}

// Close connection
sqlsrv_close($conn);

?>