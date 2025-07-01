<?php
// Start session properly
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// Check if user is logged in as receiver
if (!isset($_SESSION['receiver_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Validate request
if (!isset($_POST['request_id']) || !is_numeric($_POST['request_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

// Database connection
include("databaseconnection.php");

$request_id = (int)$_POST['request_id'];
$receiver_id = (int)$_SESSION['receiver_id'];

// Use prepared statements for security
$get_sql = "SELECT item_id, quantity_requested FROM item_requests 
           WHERE request_id = ? 
           AND receiver_id = ?
           AND status = 'Pending'";
$stmt = mysqli_prepare($con, $get_sql);
mysqli_stmt_bind_param($stmt, "ii", $request_id, $receiver_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'No matching request found']);
    exit();
}

$request = mysqli_fetch_assoc($result);
$item_id = (int)$request['item_id'];
$quantity = (int)$request['quantity_requested'];
mysqli_stmt_close($stmt);

// Start transaction for atomic operation
mysqli_begin_transaction($con);

try {
    // 1. Restore the item quantity with prepared statement
    $update_sql = "UPDATE items SET 
                  quantity = quantity + ?,
                  status = CASE 
                      WHEN quantity + ? > 0 THEN 'Available'
                      ELSE 'Reserved'
                  END
                  WHERE item_id = ?";
    $stmt = mysqli_prepare($con, $update_sql);
    mysqli_stmt_bind_param($stmt, "iii", $quantity, $quantity, $item_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to restore item quantity: ' . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);

    // 2. Update status to 'Cancelled' (consistent spelling)
    $cancel_sql = "UPDATE item_requests SET 
                  status = 'Cancelled',
                  cancelled_at = NOW()
                  WHERE request_id = ?
                  AND receiver_id = ?";
    $stmt = mysqli_prepare($con, $cancel_sql);
    mysqli_stmt_bind_param($stmt, "ii", $request_id, $receiver_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to cancel request: ' . mysqli_error($con));
    }
    mysqli_stmt_close($stmt);

    // Commit if both operations succeeded
    mysqli_commit($con);
    echo json_encode([
        'success' => true,
        'message' => 'Request cancelled successfully'
    ]);
    
} catch (Exception $e) {
    // Rollback on any error
    mysqli_rollback($con);
    error_log("Cancellation error: " . $e->getMessage());
    echo json_encode([
        'success' => false, 
        'message' => 'System error occurred. Please try again.'
    ]);
}
?>