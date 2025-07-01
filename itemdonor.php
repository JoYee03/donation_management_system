<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

// Fetch valid categories from database
$allowedCategories = array();
$sqlCategories = "SELECT category_id, category_name FROM item_categories";
$resultCategories = mysqli_query($con, $sqlCategories);
if($resultCategories) {
    while($row = mysqli_fetch_assoc($resultCategories)) {
        $allowedCategories[$row['category_id']] = $row['category_name'];
    }
} else {
    echo "<script>alert('Error loading categories: " . mysqli_error($con) . "');</script>";
}

// Process form submission
if(isset($_POST['submit']))
{   
    // Escape all POST variables with null checks
    $donor_id = isset($_POST['donor_id']) ? mysqli_real_escape_string($con, $_POST['donor_id']) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($con, $_POST['address']) : '';
    $city = isset($_POST['city']) ? mysqli_real_escape_string($con, $_POST['city']) : '';
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $item_name = isset($_POST['item_name']) ? mysqli_real_escape_string($con, $_POST['item_name']) : '';
    $description = isset($_POST['description']) ? mysqli_real_escape_string($con, $_POST['description']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $item_condition = isset($_POST['item_condition']) ? mysqli_real_escape_string($con, $_POST['item_condition']) : '';
    $status = isset($_POST['status']) ? mysqli_real_escape_string($con, $_POST['status']) : 'Pending';
    $date_added = date('Y-m-d H:i:s');

    // Validate required fields
    if(empty($donor_id) || empty($address) || empty($city) || empty($item_name) || empty($description) || $quantity < 1) {
        echo "<script>alert('Please fill all required fields.');</script>";
        exit();
    }

    // Validate category_id
    if(!array_key_exists($category_id, $allowedCategories)) {
        echo "<script>alert('Invalid category selected.');</script>";
        exit();
    }

    // Handle file upload
    $image_path = '';
    if(isset($_FILES['item_image']) && $_FILES['item_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/items/';
        if(!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['item_image']['type'];
        
        if(in_array($file_type, $allowed_types)) {
            $file_ext = pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('item_', true) . '.' . $file_ext;
            $destination = $upload_dir . $filename;
            
            if(move_uploaded_file($_FILES['item_image']['tmp_name'], $destination)) {
                $image_path = $destination;
                
                // Delete old image if editing
                if(isset($_GET['editid']) && isset($rsedit['image_path']) && file_exists($rsedit['image_path'])) {
                    unlink($rsedit['image_path']);
                }
            } else {
                echo "<script>alert('Error uploading image.');</script>";
                exit();
            }
        } else {
            echo "<script>alert('Only JPG, PNG, and GIF images are allowed.');</script>";
            exit();
        }
    } elseif(isset($_GET['editid']) && isset($rsedit['image_path'])) {
        // Keep existing image if editing and no new image uploaded
        $image_path = $rsedit['image_path'];
    } elseif(!isset($_GET['editid'])) {
        echo "<script>alert('Item image is required.');</script>";
        exit();
    }

    if(isset($_GET['editid']))
    {
        // Update existing donation
        $sql ="UPDATE items SET 
               donor_id='$donor_id',
               address='$address',
               city='$city',
               category_id='$category_id',
               item_name='$item_name',
               description='$description',
               quantity='$quantity',
               item_condition='$item_condition',
               status='$status'";
        
        if(!empty($image_path)) {
            $sql .= ", image_path='$image_path'";
        }
        
        $sql .= " WHERE item_id='$_GET[editid]'";
        
        $qsql = mysqli_query($con,$sql);
        if($qsql)
        {
            echo "<script>alert('Donation record updated successfully.');</script>";
        }
        else
        {
            echo "<script>alert('Error updating donation: " . mysqli_error($con) . "');</script>";
            exit();
        }       
    }
    else
    {
        // Insert new donation
        $sql ="INSERT INTO items(
               donor_id, address, city, category_id,
               item_name, description, 
               quantity, item_condition, status, image_path, date_added) 
               VALUES(
               '$donor_id',
               '$address',
               '$city',
               '$category_id',
               '$item_name',
               '$description',
               '$quantity',
               '$item_condition',
               '$status',
               '$image_path',
               '$date_added')";
               
        $qsql = mysqli_query($con,$sql);
        if($qsql)
        {
            echo "<script>alert('Donation submitted successfully. It will be reviewed by admin.');</script>";
            echo "<script>window.location='viewitemdonor.php';</script>";
            exit();
        }
        else
        {
            echo "<script>alert('Error submitting donation: " . mysqli_error($con) . "');</script>";
            exit();
        }
    }
}

// Fetch donor info if needed
if(isset($_SESSION['donor_id'])) {
    $sqldonor = "SELECT * FROM donor WHERE donor_id='$_SESSION[donor_id]'";
    $qsqldonor = mysqli_query($con,$sqldonor);
    if($qsqldonor) {
        $rsdonor = mysqli_fetch_array($qsqldonor);
    } else {
        echo "<script>alert('Error fetching donor information: " . mysqli_error($con) . "');</script>";
    }
}

// Fetch donation record if editing
if(isset($_GET['editid']))
{
    $sqledit = "SELECT * FROM items WHERE item_id='$_GET[editid]'";
    $qsqledit = mysqli_query($con,$sqledit);
    if($qsqledit) {
        $rsedit = mysqli_fetch_array($qsqledit);
    } else {
        echo "<script>alert('Error fetching donation record: " . mysqli_error($con) . "');</script>";
    }
}
?>
<div id="page-header">
<div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>

<div class="container">
<div class="row">
<div class="col-md-12">
<div class="header-content">
<h1>Item Donation</h1>
</div>
</div>
</div>
</div>
</div>

<div class="section" style="padding-top: 1px;">
<div class="container">
<div class="row">
<main id="" class="col-md-12">
<div class="">
<div class="">
<div class="article-comments">
<div class="media">
<div class="media-left">
<img class="media-object" src="img/charity.jpg" style="width: 100px;height: 100px;">
</div>
<div class="media-body">
    <div class="media-heading">
    <h4>Donate Items</h4>
    </div>
    <p>
    <form method="post" action="" onsubmit="return validateform()" enctype="multipart/form-data">
    <input type="hidden" name="donor_id" id="donor_id" class="form-control" value="<?php echo $rsdonor['donor_id'] ?? $_SESSION['donor_id'] ?? ''; ?>">
    <span id="errdonor_id" class="errorclass"></span>

    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Item Name</div>
        <div class="col-md-10">
            <input type="text" name="item_name" id="item_name" class="form-control" 
                   value="<?php echo $rsedit['item_name'] ?? ''; ?>" required>
            <span id="erritem_name" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Item Image</div>
        <div class="col-md-10">
            <input type="file" name="item_image" id="item_image" class="form-control" accept="image/*" <?php echo !isset($_GET['editid']) ? 'required' : ''; ?>>
            <span id="erritem_image" class="errorclass"></span>
            <?php if(isset($rsedit['image_path']) && !empty($rsedit['image_path'])): ?>
                <div class="current-image">
                    <p>Current Image:</p>
                    <img src="<?php echo $rsedit['image_path']; ?>" style="max-width: 200px; max-height: 200px;">
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Item Category</div>
        <div class="col-md-10">
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Select Category</option>
                <?php
                foreach($allowedCategories as $id => $category) {
                    $selected = (isset($rsedit['category_id']) && $rsedit['category_id'] == $id) ? 'selected' : '';
                    echo "<option value='$id' $selected>$category</option>";
                }
                ?>
            </select>
            <span id="errcategory_id" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Description</div>
        <div class="col-md-10">
            <textarea name="description" id="description" class="form-control" rows="4" required><?php 
                echo $rsedit['description'] ?? ''; 
            ?></textarea>
            <span id="errdescription" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Quantity</div>
        <div class="col-md-10">
            <input type="number" name="quantity" id="quantity" class="form-control" 
                   value="<?php echo $rsedit['quantity'] ?? 1; ?>" min="1" required>
            <span id="errquantity" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Condition</div>
        <div class="col-md-10">
            <select name="item_condition" id="item_condition" class="form-control" required>
                <option value="">Select Condition</option>
                <option value="New" <?php if(isset($rsedit['item_condition']) && $rsedit['item_condition']=='New') echo 'selected'; ?>>New</option>
                <option value="Like New" <?php if(isset($rsedit['item_condition']) && $rsedit['item_condition']=='Like New') echo 'selected'; ?>>Like New</option>
                <option value="Good" <?php if(isset($rsedit['item_condition']) && $rsedit['item_condition']=='Good') echo 'selected'; ?>>Good</option>
                <option value="Fair" <?php if(isset($rsedit['item_condition']) && $rsedit['item_condition']=='Fair') echo 'selected'; ?>>Fair</option>
                <option value="Poor" <?php if(isset($rsedit['item_condition']) && $rsedit['item_condition']=='Poor') echo 'selected'; ?>>Poor</option>
            </select>
            <span id="erritem_condition" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Address</div>
        <div class="col-md-10">
            <textarea name="address" rows="4" id="address" class="form-control" required><?php 
                echo $rsedit['address'] ?? ''; 
            ?></textarea>
            <span id="erraddress" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">City</div>
        <div class="col-md-10">
            <input type="text" name="city" id="city" class="form-control" 
                   value="<?php echo $rsedit['city'] ?? ''; ?>" required>
            <span id="errcity" class="errorclass"></span>
        </div>
    </div>
    
    <br>
    
    <?php if(isset($_SESSION['staff_id'])): ?>
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;">Status</div>
        <div class="col-md-10">
            <select class="form-control" name="status" id="status" required>
                <option value="">Select Status</option>
                <?php
                $arr = array("Pending","Approved","Rejected","Claimed");
                foreach($arr as $val) {
                    $selected = (isset($rsedit['status']) && $rsedit['status'] == $val) ? 'selected' : '';
                    echo "<option value='$val' $selected>$val</option>";
                }
                ?>
            </select>
            <span id="errstatus" class="errorclass"></span>
        </div>
    </div>
    <?php else: ?>
    <input type='hidden' name="status" id="status" value="Pending">
    <span id="errstatus" class="errorclass"></span>
    <?php endif; ?>
    
    <br>
    
    <div class="row">
        <div class="col-md-2" style="padding-top: 5px;"></div>
        <div class="col-md-10">
            <input type="submit" name="submit" id="submit" class="form-control btn btn-success" 
                   style="width: 200px;" value="<?php echo isset($_GET['editid']) ? 'Update Donation' : 'Submit Donation'; ?>">
        </div>
    </div>
    </form>
    </p>
    </div>
</div>
</div>
</div>
</main>
</div>
</div>
</div>

<?php include("footer.php"); ?>

<script>
function validateform() {
    var valid = true;
    $('.errorclass').html('');
    
    // Validate all required fields
    const requiredFields = [
        'item_name', 'category_id', 'description', 
        'quantity', 'item_condition', 'address',
        'city'
    ];
    
    requiredFields.forEach(field => {
        const value = document.getElementById(field).value.trim();
        if (!value) {
            document.getElementById('err' + field).innerHTML = "This field is required";
            valid = false;
        }
    });
    
    // Additional validation for quantity
    const quantity = document.getElementById('quantity').value;
    if (quantity && quantity < 1) {
        document.getElementById('errquantity').innerHTML = "Quantity must be at least 1";
        valid = false;
    }
    
    // Validate image file type client-side
    const fileInput = document.getElementById('item_image');
    if(fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if(validTypes.indexOf(file.type) === -1) {
            document.getElementById('erritem_image').innerHTML = "Only JPG, PNG, and GIF images are allowed";
            valid = false;
        }
        
        // Validate file size (max 2MB)
        if(file.size > 2097152) {
            document.getElementById('erritem_image').innerHTML = "Image must be less than 2MB";
            valid = false;
        }
    } else if(!<?php echo isset($_GET['editid']) && isset($rsedit['image_path']) ? 'true' : 'false'; ?>) {
        document.getElementById('erritem_image').innerHTML = "Item image is required";
        valid = false;
    }
    
    return valid;
}
</script>