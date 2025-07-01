<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Include files
require_once("header.php");
require_once("databaseconnection.php");

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Input validation functions
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateStatus($status) {
    $allowed = ['Available', 'Pending', 'Rejected', 'Donated', 'Deleted'];
    $status = ucfirst(strtolower(trim($status)));
    return in_array($status, $allowed) ? $status : false;
}

// Handle status changes and deletions
if (isset($_GET['st']) && isset($_GET['item_id']) && isset($_GET['csrf'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_GET['csrf'])) {
        die("CSRF token validation failed");
    }

    $status = validateStatus($_GET['st']);
    $item_id = (int)$_GET['item_id'];

    if ($status && $item_id > 0) {
        // Check if the user has permission to modify this item
        $check_stmt = $con->prepare("SELECT donor_id FROM items WHERE item_id=?");
        $check_stmt->bind_param("i", $item_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            $_SESSION['error_message'] = "Item not found";
            header("Location: viewitemdonor.php");
            exit();
        }
        
        $item_data = $check_result->fetch_assoc();
        $check_stmt->close();
        
        // Verify donor can only modify their own items (unless admin)
        if (isset($_SESSION['donor_id']) && $item_data['donor_id'] != $_SESSION['donor_id']) {
            $_SESSION['error_message'] = "You can only modify your own items";
            header("Location: viewitemdonor.php");
            exit();
        }

        // Update the status
        $stmt = $con->prepare("UPDATE items SET status=? WHERE item_id=?");
        if (!$stmt) {
            error_log("Prepare failed: " . $con->error);
            $_SESSION['error_message'] = "Database error occurred";
            header("Location: viewitemdonor.php");
            exit();
        }
        
        $stmt->bind_param("si", $status, $item_id);
        
        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            if ($affected > 0) {
                logAction("Item status changed to $status", $item_id);
                $_SESSION['flash_message'] = "Item status updated successfully";
            } else {
                $_SESSION['error_message'] = "No changes made to the item";
            }
        } else {
            error_log("Update failed: " . $stmt->error);
            $_SESSION['error_message'] = "Update failed: " . $stmt->error;
        }
        $stmt->close();
        header("Location: viewitemdonor.php");
        exit();
    }
}

// Handle permanent deletion
if (isset($_GET['delid']) && isset($_GET['csrf'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_GET['csrf'])) {
        die("CSRF token validation failed");
    }

    $item_id = (int)$_GET['delid'];
    
    if ($item_id > 0) {
        // Check if the user has permission to delete this item
        $check_stmt = $con->prepare("SELECT donor_id FROM items WHERE item_id=?");
        $check_stmt->bind_param("i", $item_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            $_SESSION['error_message'] = "Item not found";
            header("Location: viewitemdonor.php");
            exit();
        }
        
        $item_data = $check_result->fetch_assoc();
        $check_stmt->close();
        
        // Verify donor can only delete their own items (unless admin)
        if (isset($_SESSION['donor_id']) && $item_data['donor_id'] != $_SESSION['donor_id']) {
            $_SESSION['error_message'] = "You can only delete your own items";
            header("Location: viewitemdonor.php");
            exit();
        }

        // Delete the item
        $stmt = $con->prepare("DELETE FROM items WHERE item_id=?");
        if (!$stmt) {
            error_log("Prepare failed: " . $con->error);
            $_SESSION['error_message'] = "Database error occurred";
            header("Location: viewitemdonor.php");
            exit();
        }
        
        $stmt->bind_param("i", $item_id);
        
        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            if ($affected > 0) {
                logAction("Item permanently deleted", $item_id);
                $_SESSION['flash_message'] = "Item deleted successfully";
            } else {
                $_SESSION['error_message'] = "Item not found or already deleted";
            }
        } else {
            error_log("Delete failed: " . $stmt->error);
            $_SESSION['error_message'] = "Delete failed: " . $stmt->error;
        }
        $stmt->close();
        header("Location: viewitemdonor.php");
        exit();
    }
}

// Logging function
function logAction($action, $item_id = null) {
    $user = $_SESSION['donor_id'] ?? $_SESSION['staff_id'] ?? 'Unknown';
    $log = date("Y-m-d H:i:s") . " - $user performed $action";
    $log .= $item_id ? " on item $item_id" : "";
    $log .= " (IP: {$_SERVER['REMOTE_ADDR']})\n";
    file_put_contents('logs/actions.log', $log, FILE_APPEND);
}

// Fetch items with pagination
$current_page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$per_page = 25;
$offset = ($current_page - 1) * $per_page;

$sql = "SELECT SQL_CALC_FOUND_ROWS items.*, donor.name as donor_name, donor.email_id, donor.contact_no, 
        recipient.name as recipient_name, recipient.contact_no as recipient_contact
        FROM items 
        LEFT JOIN donor ON donor.donor_id=items.donor_id 
        LEFT JOIN item_requests ON items.item_id=item_requests.item_id AND item_requests.status='Approved'
        LEFT JOIN donor as recipient ON item_requests.receiver_id=recipient.donor_id
        WHERE items.status != 'Deleted'";

if (isset($_SESSION['donor_id'])) {
    $donor_id = (int)$_SESSION['donor_id'];
    $sql .= " AND items.donor_id=?";
}

$sql .= " ORDER BY items.date_added DESC LIMIT ?, ?";

$stmt = $con->prepare($sql);
if (isset($_SESSION['donor_id'])) {
    $stmt->bind_param("iii", $donor_id, $offset, $per_page);
} else {
    $stmt->bind_param("ii", $offset, $per_page);
}
$stmt->execute();
$result = $stmt->get_result();

// Get total count
$total_result = $con->query("SELECT FOUND_ROWS()");
$total_rows = $total_result->fetch_row()[0];
$total_pages = ceil($total_rows / $per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Item Donations</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-label {
            display: inline-block; 
            min-width: 80px;
            padding: 3px 6px;
            border-radius: 3px;
            text-align: center;
            font-weight: bold;
        }
        .status-available { background-color: #28a745; color: white; }
        .status-rejected { background-color: #dc3545; color: white; }
        .status-pending { background-color: #ffc107; color: black; }
        .status-donated { background-color: #17a2b8; color: white; }
        .status-deleted { background-color: #6c757d; color: white; }
        .pagination { justify-content: center; }
        .img-thumbnail { max-width: 80px; max-height: 80px; object-fit: cover; }
        .panel { margin-bottom: 30px; }
        .receiver-info { margin-top: 5px; font-size: 0.9em; }
    </style>
</head>
<body>
<?php include("header.php"); ?>

<div id="about" class="section">
    <div class="container">
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= sanitizeInput($_SESSION['flash_message']) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= sanitizeInput($_SESSION['error_message']) ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <center><h2 class="title">View Item Donations</h2></center>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="numbers" class="section">
    <div class="container">
        <!-- Main Items Table -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>All Donated Items</h4>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Donor</th>
                                <th>Item Details</th>
                                <th>Qty</th>
                                <th>Condition</th>
                                <th>Location</th>
                                <th>Date Added</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <strong><?= sanitizeInput($row['donor_name']) ?></strong><br>
                                        <small><?= sanitizeInput($row['email_id']) ?></small><br>
                                        <small><?= sanitizeInput($row['contact_no']) ?></small>
                                    </td>
                                    <td>
                                        <strong><?= sanitizeInput($row['item_name']) ?></strong><br>
                                        <small><?= sanitizeInput($row['description']) ?></small>
                                    </td>
                                    <td><?= (int)$row['quantity'] ?></td>
                                    <td><?= sanitizeInput($row['item_condition']) ?></td>
                                    <td>
                                        <small><?= sanitizeInput($row['address']) ?></small><br>
                                        <small><?= sanitizeInput($row['city']) ?></small>
                                    </td>
                                    <td><?= date("d-M-Y", strtotime($row['date_added'])) ?></td>
                                    <td>
                                        <?php if (!empty($row['image_path'])): ?>
                                            <img src="<?= sanitizeInput($row['image_path']) ?>" 
                                                 alt="<?= sanitizeInput($row['item_name']) ?>" 
                                                 class="img-thumbnail"
                                                 onerror="this.src='img/no-image.png'">
                                        <?php else: ?>
                                            <span class="text-muted">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-label status-<?= strtolower($row['status']) ?>">
                                            <?= sanitizeInput($row['status']) ?>
                                        </span>
                                        <?php if ($row['status'] == 'Donated' && !empty($row['recipient_name'])): ?>
                                            <div class="mt-1">
                                                <small>Recipient: <?= sanitizeInput($row['recipient_name']) ?></small><br>
                                                <small>Contact: <?= sanitizeInput($row['recipient_contact']) ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($_SESSION['donor_id']) || isset($_SESSION['staff_id'])): ?>
                                            <a href="viewitemdonor.php?delid=<?= $row['item_id'] ?>&csrf=<?= $_SESSION['csrf_token'] ?>" 
                                               class="btn btn-danger btn-sm mb-1" 
                                               onclick="return confirm('Delete this item permanently?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            
                                        <?php endif; ?>
                                        
                                        <?php if ($row['status'] == 'Pending' && isset($_SESSION['staff_id'])): ?>
                                            <a href="viewitemdonor.php?item_id=<?= $row['item_id'] ?>&st=Available&csrf=<?= $_SESSION['csrf_token'] ?>" 
                                               class="btn btn-success btn-sm mb-1" 
                                               onclick="return confirm('Approve this item?')">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="viewitemdonor.php?item_id=<?= $row['item_id'] ?>&st=Rejected&csrf=<?= $_SESSION['csrf_token'] ?>" 
                                               class="btn btn-warning btn-sm mb-1" 
                                               onclick="return confirm('Reject this item?')">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($row['status'] == 'Available' && isset($_SESSION['staff_id'])): ?>
                                            <a href="viewitemdonor.php?item_id=<?= $row['item_id'] ?>&st=Donated&csrf=<?= $_SESSION['csrf_token'] ?>" 
                                               class="btn btn-primary btn-sm" 
                                               onclick="return confirm('Mark as donated?')">
                                                <i class="fas fa-handshake"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?= $current_page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $current_page - 1 ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $current_page == $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $current_page + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Only initialize if not already a DataTable
    if (!$.fn.DataTable.isDataTable('#datatable')) {
        $('#datatable').DataTable({
            searching: true,
            ordering: true,
            paging: false,
            info: false,
            responsive: true,
            columnDefs: [
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 8 },
                { orderable: false, targets: [7, 8] }
            ]
        });
    }
});
</script>
</body>
</html>
<?php
    $stmt->close();
    $con->close();
?>