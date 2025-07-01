<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

// Check if user is logged in as receiver
if (!isset($_SESSION['receiver_id'])) {
    header("Location: login.php");
    exit();
}

// Process item request
if (isset($_POST['request_item'])) {
    $item_id = intval($_POST['item_id']);
    $requested_quantity = intval($_POST['quantity']);
    $receiver_id = $_SESSION['receiver_id'];
    
    // Validate quantity
    if ($requested_quantity < 1) {
        echo "<script>alert('Quantity must be at least 1.');</script>";
    } else {
        // Check item availability
        $sql = "SELECT quantity, status FROM items WHERE item_id = $item_id";
        $result = mysqli_query($con, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $item = mysqli_fetch_assoc($result);
            
            if ($item['status'] != 'Available') {
                echo "<script>alert('This item is not available for request.');</script>";
            } elseif ($requested_quantity > $item['quantity']) {
                echo "<script>alert('Requested quantity exceeds available quantity.');</script>";
            } else {
                // Insert request
                $insert_sql = "INSERT INTO item_requests (item_id, receiver_id, requested_quantity, status, request_date) 
                              VALUES ($item_id, $receiver_id, $requested_quantity, 'Pending', NOW())";
                if (mysqli_query($con, $insert_sql)) {
                    echo "<script>alert('Request submitted successfully. Staff will review your request.');</script>";
                } else {
                    echo "<script>alert('Error submitting request: " . mysqli_error($con) . "');</script>";
                }
            }
        } else {
            echo "<script>alert('Item not found.');</script>";
        }
    }
}

// Fetch available items with pagination
$current_page = max(1, isset($_GET['page']) ? intval($_GET['page']) : 1);
$per_page = 10;
$offset = ($current_page - 1) * $per_page;

// Count total available items
$count_sql = "SELECT COUNT(*) as total FROM items WHERE status = 'Available'";
$count_result = mysqli_query($con, $count_sql);
$total_items = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_items / $per_page);

// Fetch items with categories
$sql = "SELECT i.*, ic.category_name 
        FROM items i
        JOIN item_categories ic ON i.category_id = ic.category_id
        WHERE i.status = 'Available'
        ORDER BY i.date_added DESC
        LIMIT $offset, $per_page";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Donated Items</title>
    <style>
        .item-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .item-image {
            max-width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }
        .item-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .item-category {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        .item-condition {
            font-weight: bold;
        }
        .condition-new { color: #28a745; }
        .condition-like-new { color: #5cb85c; }
        .condition-good { color: #5bc0de; }
        .condition-fair { color: #f0ad4e; }
        .condition-poor { color: #d9534f; }
        .quantity-input {
            width: 80px;
            display: inline-block;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .no-items {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .location-info {
            color: #666;
            font-size: 0.9rem;
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
                    <h1>Available Donated Items</h1>
                    <p>Browse and request items you need</p>
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
                    <div class="row">
                        <?php while ($item = mysqli_fetch_assoc($result)): ?>
                            <div class="col-md-6">
                                <div class="item-card">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <?php if (!empty($item['image_path'])): ?>
                                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['item_name']); ?>" 
                                                     class="item-image">
                                            <?php else: ?>
                                                <img src="img/no-image.png" alt="No image" class="item-image">
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="item-title"><?php echo htmlspecialchars($item['item_name']); ?></div>
                                            <div class="item-category"><?php echo htmlspecialchars($item['category_name']); ?></div>
                                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                                            
                                            <div class="mb-2">
                                                <span class="item-condition condition-<?php echo strtolower(str_replace(' ', '-', $item['item_condition'])); ?>">
                                                    <?php echo htmlspecialchars($item['item_condition']); ?>
                                                </span>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <strong>Available Quantity:</strong> <?php echo intval($item['quantity']); ?>
                                            </div>
                                            
                                            <div class="location-info mb-3">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?php echo htmlspecialchars($item['address'] . ', ' . $item['city']); ?>
                                            </div>
                                            
                                            <form method="post" onsubmit="return validateRequest(<?php echo intval($item['quantity']); ?>, this)">
                                                <input type="hidden" name="item_id" value="<?php echo intval($item['item_id']); ?>">
                                                <div class="form-group">
                                                    <label for="quantity-<?php echo intval($item['item_id']); ?>">Request Quantity:</label>
                                                    <input type="number" name="quantity" id="quantity-<?php echo intval($item['item_id']); ?>" 
                                                           class="form-control quantity-input" min="1" max="<?php echo intval($item['quantity']); ?>" 
                                                           value="1" required>
                                                </div>
                                                <button type="submit" name="request_item" class="btn btn-primary">
                                                    <i class="fas fa-hand-holding-heart"></i> Request Item
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <li class="page-item <?php echo $current_page == 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $current_page - 1; ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $current_page + 1; ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="no-items">
                        <i class="fas fa-box-open fa-4x mb-3"></i>
                        <h3>No items available at the moment</h3>
                        <p>Check back later for new donations</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
function validateRequest(availableQty, form) {
    const requestedQty = parseInt(form.quantity.value);
    
    if (requestedQty < 1) {
        alert('Quantity must be at least 1.');
        return false;
    }
    
    if (requestedQty > availableQty) {
        alert('Requested quantity exceeds available quantity.');
        return false;
    }
    
    return confirm('Are you sure you want to request ' + requestedQty + ' of this item?');
}
</script>
</body>
</html>