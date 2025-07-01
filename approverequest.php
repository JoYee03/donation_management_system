<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in as staff
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

include("header.php");

// Process approval/rejection
if (isset($_GET['action']) && isset($_GET['request_id'])) {
    $request_id = intval($_GET['request_id']);
    $action = $_GET['action'];
    
    // Validate action
    if (!in_array($action, ['approve', 'reject'])) {
        echo "<script>alert('Invalid action.');</script>";
    } else {
        // Check if request exists
        $check_sql = "SELECT * FROM item_requests WHERE request_id = $request_id";
        $check_result = mysqli_query($con, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            $request = mysqli_fetch_assoc($check_result);
            $item_id = $request['item_id'];
            $requested_qty = $request['requested_quantity'];
            
            if ($action == 'approve') {
                // Check item availability
                $item_sql = "SELECT quantity FROM items WHERE item_id = $item_id AND status = 'Available'";
                $item_result = mysqli_query($con, $item_sql);
                
                if (mysqli_num_rows($item_result) > 0) {
                    $item = mysqli_fetch_assoc($item_result);
                    
                    if ($requested_qty > $item['quantity']) {
                        echo "<script>alert('Cannot approve request - requested quantity exceeds available quantity.');</script>";
                    } else {
                        // Start transaction
                        mysqli_begin_transaction($con);
                        
                        try {
                            // Update request status
                            $update_sql = "UPDATE item_requests SET status = 'Approved', processed_date = NOW(), 
                                          processed_by = $_SESSION[staff_id] WHERE request_id = $request_id";
                            mysqli_query($con, $update_sql);
                            
                            // Update item quantity
                            $new_qty = $item['quantity'] - $requested_qty;
                            $item_update_sql = "UPDATE items SET quantity = $new_qty WHERE item_id = $item_id";
                            
                            // If quantity reaches zero, mark as claimed
                            if ($new_qty <= 0) {
                                $item_update_sql = "UPDATE items SET quantity = 0, status = 'Claimed' WHERE item_id = $item_id";
                            }
                            
                            mysqli_query($con, $item_update_sql);
                            
                            // Commit transaction
                            mysqli_commit($con);
                            echo "<script>alert('Request approved successfully.');</script>";
                        } catch (Exception $e) {
                            // Rollback transaction on error
                            mysqli_rollback($con);
                            echo "<script>alert('Error processing request: " . mysqli_error($con) . "');</script>";
                        }
                    }
                } else {
                    echo "<script>alert('Item no longer available.');</script>";
                }
            } else {
                // Reject request
                $update_sql = "UPDATE item_requests SET status = 'Rejected', processed_date = NOW(), 
                              processed_by = $_SESSION[staff_id] WHERE request_id = $request_id";
                
                if (mysqli_query($con, $update_sql)) {
                    echo "<script>alert('Request rejected successfully.');</script>";
                } else {
                    echo "<script>alert('Error rejecting request: " . mysqli_error($con) . "');</script>";
                }
            }
        } else {
            echo "<script>alert('Request not found.');</script>";
        }
    }
}

// Fetch pending requests with receiver info from receiver table
$sql = "SELECT r.*, i.item_name, i.image_path, i.quantity as available_qty, 
        rc.name as receiver_name, rc.email_id as receiver_email, rc.contact_no as receiver_contact,
        s.staff_name as processor_name
        FROM item_requests r
        JOIN items i ON r.item_id = i.item_id
        JOIN receiver rc ON r.receiver_id = rc.receiver_id
        LEFT JOIN staff s ON r.processed_by = s.staff_id
        WHERE r.status = 'Pending'
        ORDER BY r.request_date DESC";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approve Item Requests</title>
    <style>
        .request-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .request-image {
            max-width: 100px;
            height: auto;
            border-radius: 4px;
        }
        .request-title {
            font-weight: bold;
            font-size: 1.1rem;
        }
        .receiver-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-approved {
            background-color: #28a745;
            color: #fff;
        }
        .badge-rejected {
            background-color: #dc3545;
            color: #fff;
        }
        .action-buttons {
            margin-top: 15px;
        }
        .no-requests {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .quantity-info {
            margin: 5px 0;
        }
    </style>
</head>
<body>
<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>Approve Item Requests</h1>
                    <p>Review and approve/reject requests from receivers</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($request = mysqli_fetch_assoc($result)): ?>
                        <div class="request-card">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php if (!empty($request['image_path'])): ?>
                                        <img src="<?php echo htmlspecialchars($request['image_path']); ?>" 
                                             alt="<?php echo htmlspecialchars($request['item_name']); ?>" 
                                             class="request-image">
                                    <?php else: ?>
                                        <img src="img/no-image.png" alt="No image" class="request-image">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-10">
                                    <div class="request-title"><?php echo htmlspecialchars($request['item_name']); ?></div>
                                    <div>
                                        <span class="badge badge-pending">Pending</span>
                                        <span class="text-muted">Requested on: <?php echo date('M d, Y H:i', strtotime($request['request_date'])); ?></span>
                                    </div>
                                    
                                    <div class="quantity-info">
                                        <strong>Requested Quantity:</strong> <?php echo intval($request['requested_quantity']); ?>
                                        <span class="text-muted">(Available: <?php echo intval($request['available_qty']); ?>)</span>
                                    </div>
                                    
                                    <div class="receiver-info">
                                        <h6>Receiver Information</h6>
                                        <p>
                                            <strong>Name:</strong> <?php echo htmlspecialchars($request['receiver_name']); ?><br>
                                            <strong>Contact:</strong> <?php echo htmlspecialchars($request['receiver_contact']); ?><br>
                                            <strong>Email:</strong> <?php echo htmlspecialchars($request['receiver_email']); ?>
                                        </p>
                                    </div>
                                    
                                    <div class="action-buttons">
                                        <a href="approverequest.php?action=approve&request_id=<?php echo intval($request['request_id']); ?>" 
                                           class="btn btn-success btn-sm"
                                           onclick="return confirm('Approve this request for <?php echo htmlspecialchars($request['receiver_name']); ?>?')">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="approverequest.php?action=reject&request_id=<?php echo intval($request['request_id']); ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Reject this request from <?php echo htmlspecialchars($request['receiver_name']); ?>?')">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-requests">
                        <i class="fas fa-check-circle fa-4x mb-3"></i>
                        <h3>No pending requests</h3>
                        <p>All requests have been processed</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>