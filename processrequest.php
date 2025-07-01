<?php
session_start();
require_once("databaseconnection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Staff authorization
if (!isset($_SESSION['staff_id'])) {
    error_log("Unauthorized access attempt from IP: " . $_SERVER['REMOTE_ADDR']);
    $_SESSION['error_message'] = "Unauthorized access";
    header("Location: staff.php");
    exit();
}

// Validate request method
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    $_SESSION['error_message'] = "Invalid request method";
    header("Location: staffrequest.php");
    exit();
}

// Process request based on method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle rejections
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        error_log("CSRF token mismatch in POST");
        $_SESSION['error_message'] = "Invalid CSRF token";
        header("Location: staffrequest.php");
        exit();
    }
    
    $action = 'reject';
    $request_id = (int)$_POST['id'];
    $request_type = $_POST['type'];
    $rejection_reason = trim($_POST['rejection_reason'] ?? '');
} else {
    // Handle approvals
    if (!isset($_GET['csrf']) || $_GET['csrf'] !== $_SESSION['csrf_token']) {
        error_log("CSRF token mismatch in GET");
        $_SESSION['error_message'] = "Invalid CSRF token";
        header("Location: staffrequest.php");
        exit();
    }
    
    $action = strtolower($_GET['action']);
    $request_id = (int)$_GET['id'];
    $request_type = $_GET['type'];
    $rejection_reason = '';
}

// Validate action and request type
if (!in_array($action, ['approve', 'reject']) || !in_array($request_type, ['existing', 'custom'])) {
    error_log("Invalid parameters - Action: $action, Type: $request_type");
    $_SESSION['error_message'] = "Invalid parameters";
    header("Location: staffrequest.php");
    exit();
}

// Validate rejection reason
if ($action === 'reject' && empty($rejection_reason)) {
    $_SESSION['error_message'] = "Please provide a rejection reason";
    header("Location: staffrequest.php");
    exit();
}

// Verify database connection
if ($con->connect_error) {
    error_log("Database connection failed: " . $con->connect_error);
    $_SESSION['error_message'] = "Database connection error";
    header("Location: staffrequest.php");
    exit();
}

// Start transaction
$con->begin_transaction();
error_log("Starting transaction for $request_type request $request_id (Action: $action)");

try {
    $staff_id = (int)$_SESSION['staff_id'];
    $status = ($action === 'approve') ? 'Approved' : 'Rejected';
    
    if ($request_type == 'existing') {
        error_log("Processing existing item request");
        
        // Get request details with item info
        $request_sql = "SELECT r.*, i.quantity as item_quantity, i.item_name, i.description, i.image_path 
                       FROM item_requests r
                       JOIN items i ON r.item_id = i.item_id
                       WHERE r.request_id = ? FOR UPDATE";
        
        $stmt = $con->prepare($request_sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        
        $stmt->bind_param("i", $request_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $request = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $stmt = null;
        
        if (!$request) {
            throw new Exception("Request not found");
        }

        if ($action === 'approve') {
            // Verify sufficient quantity
            if ($request['item_quantity'] < $request['quantity_requested']) {
                throw new Exception("Not enough stock available");
            }
            
            // Update request status
            $update_sql = "UPDATE item_requests SET 
                          status = ?,
                          processed_by = ?,
                          processed_date = NOW()
                          WHERE request_id = ?";
            
            $stmt = $con->prepare($update_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $stmt->bind_param("sii", $status, $staff_id, $request_id);
            if (!$stmt->execute()) {
                throw new Exception("Request update failed: " . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                throw new Exception("No rows updated - request may have already been processed");
            }
            $stmt->close();
            $stmt = null;
            
            // Update item inventory
            $new_quantity = $request['item_quantity'] - $request['quantity_requested'];
            $item_status = ($new_quantity > 0) ? 'Available' : 'Donated';
            
            $update_item = "UPDATE items SET
                           quantity = ?,
                           status = ?
                           WHERE item_id = ?";
            
            $stmt = $con->prepare($update_item);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $stmt->bind_param("isi", $new_quantity, $item_status, $request['item_id']);
            if (!$stmt->execute()) {
                throw new Exception("Item update failed: " . $stmt->error);
            }
            $stmt->close();
            $stmt = null;
        } else {
            // Handle rejection
            $update_sql = "UPDATE item_requests SET 
                          status = ?,
                          processed_by = ?,
                          processed_date = NOW(),
                          rejection_reason = ?
                          WHERE request_id = ?";
            
            $stmt = $con->prepare($update_sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $con->error);
            }
            
            $stmt->bind_param("sisi", $status, $staff_id, $rejection_reason, $request_id);
            if (!$stmt->execute()) {
                throw new Exception("Request update failed: " . $stmt->error);
            }
            
            if ($stmt->affected_rows === 0) {
                throw new Exception("No rows updated - request may have already been processed");
            }
            $stmt->close();
            $stmt = null;
        }
    } else {
        // Handle custom requests
        error_log("Processing custom item request");
        
        // First get the request details
        $get_request_sql = "SELECT * FROM custom_item_requests WHERE request_id = ? FOR UPDATE";
        $stmt = $con->prepare($get_request_sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        
        $stmt->bind_param("i", $request_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        $request = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $stmt = null;
        
        if (!$request) {
            throw new Exception("Custom request not found");
        }

        // Update request status and visibility
        $update_sql = "UPDATE custom_item_requests SET 
                  status = ?,
                  processed_by = ?,
                  processed_date = NOW(),
                  is_visible_to_donors = ?" .
                  ($action === 'reject' ? ", rejection_reason = ?" : "") .
                  " WHERE request_id = ?";
        
        $stmt = $con->prepare($update_sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $con->error);
        }
        
        if ($action === 'approve') {
            $is_visible = 1; // Make visible to donors
            $stmt->bind_param("siii", $status, $staff_id, $is_visible, $request_id);
        } else {
            $is_visible = 0; // Keep hidden if rejected
            $stmt->bind_param("siisi", $status, $staff_id, $is_visible, $rejection_reason, $request_id);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Request update failed: " . $stmt->error);
        }
        
        if ($stmt->affected_rows === 0) {
            throw new Exception("No rows updated - request may have already been processed");
        }
        $stmt->close();
        $stmt = null;
    }
    
    $con->commit();
    error_log("Transaction committed successfully for request $request_id");
    $_SESSION['flash_message'] = "Request #$request_id " . ($action === 'approve' ? "approved" : "rejected") . " successfully";
    
} catch (Exception $e) {
    $con->rollback();
    error_log("Transaction failed: " . $e->getMessage());
    $_SESSION['error_message'] = "Error processing request: " . $e->getMessage();
}

// Ensure connection is closed
if (isset($stmt) && is_object($stmt)) {
    try {
        $stmt->close();
    } catch (Exception $e) {
        error_log("Warning: Exception while closing statement: " . $e->getMessage());
    }
}

if (isset($con)) {
    try {
        $con->close();
    } catch (Exception $e) {
        error_log("Warning: Exception while closing connection: " . $e->getMessage());
    }
}
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    if (isset($_SESSION['flash_message'])) {
        echo json_encode(['success' => true, 'message' => $_SESSION['flash_message']]);
    } else if (isset($_SESSION['error_message'])) {
        echo json_encode(['success' => false, 'error' => $_SESSION['error_message']]);
    }
    exit;
}
header("Location: staffrequest.php");
exit();
?>