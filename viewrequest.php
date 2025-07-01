<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

// Check if user is logged in as receiver
if (!isset($_SESSION['receiver_id'])) {
    header("Location: receiveraccount.php");
    exit();
}

// Database connection
include("databaseconnection.php");

$receiver_id = $_SESSION['receiver_id'];
?>

<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>My Item Requests</h1>
                    <p>View the status of your requested items</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                // Fetch all requests for this receiver with item details
                $requests_sql = "SELECT ir.*, i.item_name, i.image_path, c.category_name, d.name AS donor_name
                                FROM item_requests ir
                                JOIN items i ON ir.item_id = i.item_id
                                JOIN item_categories c ON i.category_id = c.category_id
                                LEFT JOIN donor d ON i.donor_id = d.donor_id
                                WHERE ir.receiver_id = '$receiver_id'
                                ORDER BY ir.request_date DESC";
                $requests_result = mysqli_query($con, $requests_sql);

                if (!$requests_result) {
                    die("Database error: " . mysqli_error($con));
                }

                if (mysqli_num_rows($requests_result) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Donor</th>
                                    <th>Request Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($request = mysqli_fetch_assoc($requests_result)): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($request['image_path'])): ?>
                                                <img src="<?php echo htmlspecialchars($request['image_path'] ?? ''); ?>" 
                                                    alt="<?php echo htmlspecialchars($request['item_name'] ?? 'Item image'); ?>" 
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="img/no-image-icon.png" alt="No image" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($request['item_name'] ?? 'Unknown item'); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($request['category_name'] ?? 'Uncategorized'); ?></td>
                                        <td><?php echo htmlspecialchars($request['quantity_requested'] ?? '0'); ?></td>
                                        <td><?php echo htmlspecialchars($request['donor_name'] ?? 'Anonymous donor'); ?></td>
                                        <td>
                                            <?php 
                                            $request_date = $request['request_date'] ?? null;
                                            echo $request_date ? date('M j, Y g:i A', strtotime($request_date)) : 'Unknown date';
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            $status = $request['status'] ?? 'Pending';
                                            $status_class = 'badge-warning';
                                            switch(strtolower($status)) {
                                                case 'approved':
                                                    $status_class = 'badge-success';
                                                    break;
                                                case 'rejected':
                                                    $status_class = 'badge-danger';
                                                    break;
                                                default:
                                                    $status_class = 'badge-warning';
                                            }
                                            ?>
                                            <span class="badge <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (($request['status'] ?? '') === 'Pending'): ?>
                                                <button class="btn btn-sm btn-outline-danger cancel-request" 
                                                        data-request-id="<?php echo htmlspecialchars($request['request_id'] ?? ''); ?>">
                                                    Cancel Request
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        You haven't made any item requests yet. <a href="listeditems.php">Browse available items</a> to make a request.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for cancel request functionality -->
<script>
$(document).ready(function() {
    $('.cancel-request').click(function() {
        if (confirm('Are you sure you want to permanently delete this request?')) {
            const requestId = $(this).data('request-id');
            const row = $(this).closest('tr');
            
            $.ajax({
                url: 'cancelrequest.php',
                type: 'POST',
                data: { request_id: requestId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Fade out and remove the row
                        row.fadeOut(300, function() {
                            $(this).remove();
                            
                            // If no requests left, show message and reload after 1 second
                            if ($('.cancel-request').length === 0) {
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            }
                        });
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseText);
                }
            });
        }
    });
});
</script>

<?php include("footer.php"); ?>