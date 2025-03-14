<?php include "Connection.php";
    $user_id = 1;

    // Fetch unread notifications count and details
    $notif_query = "SELECT id, message, created_at FROM notifications WHERE user_id = ? AND is_read = 0";
    $params = [$user_id];
    $stmt = sqlsrv_query($conn, $notif_query, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    
    $notifications = [];
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $notifications[] = [
            "id" => $row['id'],
            "message" => $row['message'],
            "created_at" => $row['created_at']->format('Y-m-d H:i:s')
        ];
    }
    
    // Fetch cart items count
    $cart_query = "SELECT SUM(quantity) AS bag_count FROM cart_items WHERE user_id = ?";
    $stmt = sqlsrv_query($conn, $cart_query, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $cart_result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $bag_count = $cart_result['bag_count'] ?? 0;
    
    // Return JSON response
    $response = [
        "notifications" => $notifications,
        "unreadCount" => count($notifications),
        "bagItems" => $bag_count
    ];
    echo json_encode($response);
    
    // Close the connection
    sqlsrv_close($conn);
    ?>