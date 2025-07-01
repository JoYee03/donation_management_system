<?php
include("header.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if(isset($_POST['submit']))
{
    // Initialize variables
    $image = $id_proof = $address_proof = '';
    $uploadErrors = [];
    
    try {
        // Process file uploads with validation
        $uploadDir = [
            'image' => 'imgreceiver/',
            'id_proof' => 'imgidproof/',
            'address_proof' => 'imgaddressproof/'
        ];
        
        foreach ($uploadDir as $fileType => $dir) {
            if ($_FILES[$fileType]['error'] === UPLOAD_ERR_OK) {
                // Validate file type and size
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($_FILES[$fileType]['type'], $allowedTypes)) {
                    throw new Exception("Invalid file type for $fileType. Only JPG, PNG, GIF, or PDF allowed.");
                }
                
                if ($_FILES[$fileType]['size'] > $maxSize) {
                    throw new Exception("File too large for $fileType. Maximum 2MB allowed.");
                }
                
                // Create directory if it doesn't exist
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                $filename = uniqid() . '_' . basename($_FILES[$fileType]['name']);
                $$fileType = $filename;
                
                if (!move_uploaded_file($_FILES[$fileType]['tmp_name'], $dir . $filename)) {
                    throw new Exception("Failed to upload $fileType");
                }
            } elseif ($_FILES[$fileType]['error'] !== UPLOAD_ERR_NO_FILE && !isset($_GET['editid'])) {
                throw new Exception("Error uploading $fileType: " . $_FILES[$fileType]['error']);
            }
        }

        // Sanitize and validate inputs
        $requiredFields = [
            'receiver_type_id' => 'Receiver Type',
            'name' => 'Full Name',
            'address' => 'Address',
            'contact_no' => 'Contact Number',
            'email_id' => 'Email',
            'status' => 'Status'
        ];
        
        $cleanData = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($_POST[$field])) {
                throw new Exception("$label is required");
            }
            $cleanData[$field] = mysqli_real_escape_string($con, $_POST[$field]);
        }
        
        // Optional fields
        $cleanData['description'] = mysqli_real_escape_string($con, $_POST['description'] ?? '');

        // Hash the password
        if (!empty($_POST['password'])) {
            $cleanData['password'] = mysqli_real_escape_string($con, $_POST['password']);
        } elseif(isset($_GET['editid'])) {
            // Keep existing password if not provided during edit
            $cleanData['password'] = $rsedit['password'];
        }

        if(isset($_GET['editid']))
        {
            // Update existing receiver
            $sql = "UPDATE receiver SET 
                    receiver_type_id = '{$cleanData['receiver_type_id']}',
                    name = '{$cleanData['name']}',
                    address = '{$cleanData['address']}',
                    contact_no = '{$cleanData['contact_no']}',
                    email_id = '{$cleanData['email_id']}',
                    description = '{$cleanData['description']}',
                    status = '{$cleanData['status']}'";

            if(!empty($cleanData['password'])) {
                $sql .= ", password = '{$cleanData['password']}'";
            }
            
            if(!empty($image)) {
                $sql .= ", image = '$image'";
                // Delete old image if it exists
                if (!empty($rsedit['image']) && file_exists("imgreceiver/{$rsedit['image']}")) {
                    unlink("imgreceiver/{$rsedit['image']}");
                }
            }
            
            if(!empty($id_proof)) {
                $sql .= ", id_proof = '$id_proof'";
                // Delete old id_proof if it exists
                if (!empty($rsedit['id_proof']) && file_exists("imgidproof/{$rsedit['id_proof']}")) {
                    unlink("imgidproof/{$rsedit['id_proof']}");
                }
            }
            
            if(!empty($address_proof)) {
                $sql .= ", address_proof = '$address_proof'";
                // Delete old address_proof if it exists
                if (!empty($rsedit['address_proof']) && file_exists("imgaddressproof/{$rsedit['address_proof']}")) {
                    unlink("imgaddressproof/{$rsedit['address_proof']}");
                }
            }
            
            $sql .= " WHERE receiver_id = '{$_GET['editid']}'";
            
            $qsql = mysqli_query($con, $sql);
            
            if(!$qsql) {
                throw new Exception("Database error: " . mysqli_error($con));
            }
            
            $_SESSION['success_message'] = 'Receiver updated successfully.';
        }
        else
        {
            // Insert new receiver
            $sql = "INSERT INTO receiver(
                    receiver_type_id, 
                    name, 
                    image, 
                    address, 
                    contact_no, 
                    email_id,
                    password,
                    id_proof, 
                    address_proof, 
                    description, 
                    status
                ) VALUES(
                    '{$cleanData['receiver_type_id']}',
                    '{$cleanData['name']}',
                    '$image',
                    '{$cleanData['address']}',
                    '{$cleanData['contact_no']}',
                    '{$cleanData['email_id']}',
                    '{$cleanData['password']}',
                    '$id_proof',
                    '$address_proof',
                    '{$cleanData['description']}',
                    '{$cleanData['status']}'
                )";
            
            $qsql = mysqli_query($con, $sql);
            
            if(!$qsql) {
                throw new Exception("Database error: " . mysqli_error($con));
            }
            
            $_SESSION['success_message'] = 'Receiver added successfully.';
        }
        
        // Redirect to prevent form resubmission
        header("Location: receiver.php");
        exit();
        
    } catch (Exception $e) {
        // Clean up any uploaded files if error occurred
        if (!empty($image) && file_exists("imgreceiver/$image")) unlink("imgreceiver/$image");
        if (!empty($id_proof) && file_exists("imgidproof/$id_proof")) unlink("imgidproof/$id_proof");
        if (!empty($address_proof) && file_exists("imgaddressproof/$address_proof")) unlink("imgaddressproof/$address_proof");
        
        $error_message = $e->getMessage();
    }
}

// Display success message if set
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('{$_SESSION['success_message']}');</script>";
    unset($_SESSION['success_message']);
}

// Display error message if set
if (isset($error_message)) {
    echo "<script>alert('Error: " . addslashes($error_message) . "');</script>";
}

// Load data for editing
if(isset($_GET['editid']))
{
    $sqledit = "SELECT * FROM receiver WHERE receiver_id='{$_GET['editid']}'";
    $qsqledit = mysqli_query($con, $sqledit);
    if (!$qsqledit) {
        die("Database error: " . mysqli_error($con));
    }
    $rsedit = mysqli_fetch_array($qsqledit);
}
?>

<!-- HTML Form -->
<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1><?php echo isset($_GET['editid']) ? 'Edit' : 'Add'; ?> Receiver</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section" style="padding-top: 1px;">
    <div class="container">
        <div class="row">
            <main class="col-md-12">
                <div class="article-comments">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object" src="img/charity.jpg" style="width: 100px;height: 100px;">
                        </div>
                        <div class="media-body">
                            <div class="media-heading">
                                <h4>Receiver Information</h4>
                            </div>
                            <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateForm()">
                                <!-- Receiver Type -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Receiver Type</label></div>
                                    <div class="col-md-10">
                                        <select name="receiver_type_id" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <?php
                                            $sql = "SELECT * FROM receiver_type WHERE status='Active'";
                                            $result = mysqli_query($con, $sql);
                                            if (!$result) {
                                                die("Database error: " . mysqli_error($con));
                                            }
                                            while($row = mysqli_fetch_assoc($result)) {
                                                $selected = (isset($rsedit) && $rsedit['receiver_type_id'] == $row['receiver_type_id']) ? 'selected' : '';
                                                echo "<option value='{$row['receiver_type_id']}' $selected>{$row['receiver_type']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Full Name</label></div>
                                    <div class="col-md-10">
                                        <input type="text" name="name" class="form-control" 
                                               value="<?php echo isset($rsedit['name']) ? htmlspecialchars($rsedit['name']) : ''; ?>" required>
                                    </div>
                                </div>

                                <!-- Image -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Photo</label></div>
                                    <div class="col-md-10">
                                        <input type="file" name="image" class="form-control" accept="image/*" <?php echo !isset($_GET['editid']) ? 'required' : ''; ?>>
                                        <?php if(isset($rsedit['image']) && file_exists("imgreceiver/".$rsedit['image'])): ?>
                                            <img src="imgreceiver/<?php echo htmlspecialchars($rsedit['image']); ?>" width="100" class="mt-2">
                                        <?php endif; ?>
                                        <small class="text-muted">Max 2MB (JPG, PNG, GIF)</small>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Address</label></div>
                                    <div class="col-md-10">
                                        <textarea name="address" class="form-control" required><?php 
                                            echo isset($rsedit['address']) ? htmlspecialchars($rsedit['address']) : ''; 
                                        ?></textarea>
                                    </div>
                                </div>

                                <!-- Contact Number -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Contact No.</label></div>
                                    <div class="col-md-10">
                                        <input type="text" name="contact_no" class="form-control" 
                                               value="<?php echo isset($rsedit['contact_no']) ? htmlspecialchars($rsedit['contact_no']) : ''; ?>" required>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Email</label></div>
                                    <div class="col-md-10">
                                        <input type="email" name="email_id" class="form-control" 
                                            value="<?php echo isset($rsedit['email_id']) ? htmlspecialchars($rsedit['email_id']) : ''; ?>" required>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Password</label></div>
                                    <div class="col-md-10">
                                        <input type="password" name="password" class="form-control" 
                                            placeholder="<?php echo isset($_GET['editid']) ? 'Leave blank to keep current password' : ''; ?>" 
                                            <?php echo !isset($_GET['editid']) ? 'required' : ''; ?>>
                                        <?php if(isset($_GET['editid'])): ?>
                                            <small class="text-muted">Leave blank to keep current password</small>
                                        <?php else: ?>
                                            <small class="text-muted">Minimum 8 characters</small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- ID Proof -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>ID Proof</label></div>
                                    <div class="col-md-10">
                                        <input type="file" name="id_proof" class="form-control" accept="image/*,.pdf" <?php echo !isset($_GET['editid']) ? 'required' : ''; ?>>
                                        <?php if(isset($rsedit['id_proof']) && file_exists("imgidproof/".$rsedit['id_proof'])): ?>
                                            <a href="imgidproof/<?php echo htmlspecialchars($rsedit['id_proof']); ?>" download class="btn btn-sm btn-info mt-2">Download ID Proof</a>
                                        <?php endif; ?>
                                        <small class="text-muted">Max 2MB (JPG, PNG, GIF, PDF)</small>
                                    </div>
                                </div>

                                <!-- Address Proof -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Address Proof</label></div>
                                    <div class="col-md-10">
                                        <input type="file" name="address_proof" class="form-control" accept="image/*,.pdf" <?php echo !isset($_GET['editid']) ? 'required' : ''; ?>>
                                        <?php if(isset($rsedit['address_proof']) && file_exists("imgaddressproof/".$rsedit['address_proof'])): ?>
                                            <a href="imgaddressproof/<?php echo htmlspecialchars($rsedit['address_proof']); ?>" download class="btn btn-sm btn-info mt-2">Download Address Proof</a>
                                        <?php endif; ?>
                                        <small class="text-muted">Max 2MB (JPG, PNG, GIF, PDF)</small>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Description</label></div>
                                    <div class="col-md-10">
                                        <textarea name="description" class="form-control"><?php 
                                            echo isset($rsedit['description']) ? htmlspecialchars($rsedit['description']) : ''; 
                                        ?></textarea>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="row form-group">
                                    <div class="col-md-2"><label>Status</label></div>
                                    <div class="col-md-10">
                                        <select name="status" class="form-control" required>
                                            <option value="">Select Status</option>
                                            <option value="Active" <?php echo (isset($rsedit['status']) && $rsedit['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="Inactive" <?php echo (isset($rsedit['status']) && $rsedit['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row form-group">
                                    <div class="col-md-10 offset-md-2">
                                        <input type="submit" name="submit" value="Save Receiver" class="btn btn-primary">
                                        <a href="receiver.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>

<script>
function validateForm() {
    // Simple client-side validation
    const fields = [
        {name: "receiver_type_id", message: "Please select receiver type"},
        {name: "name", message: "Please enter name"},
        {name: "address", message: "Please enter address"},
        {name: "contact_no", message: "Please enter contact number"},
        {name: "email_id", message: "Please enter valid email"},
        {name: "status", message: "Please select status"}
    ];
    
    // Only require password for new records
    if (!document.querySelector('input[name="editid"]')) {
        fields.push({name: "password", message: "Please enter password"});
    }
    
    for (const field of fields) {
        const element = document.getElementsByName(field.name)[0];
        if (!element.value.trim()) {
            alert(field.message);
            element.focus();
            return false;
        }
    }
    
    // Validate file sizes (client-side)
    const maxSize = 2 * 1024 * 1024; // 2MB
    const fileInputs = ['image', 'id_proof', 'address_proof'];
    
    for (const inputName of fileInputs) {
        const fileInput = document.getElementsByName(inputName)[0];
        if (fileInput.files.length > 0 && fileInput.files[0].size > maxSize) {
            alert(`File too large for ${inputName.replace('_', ' ')}. Maximum 2MB allowed.`);
            return false;
        }
    }
    
    return true;
}
</script>