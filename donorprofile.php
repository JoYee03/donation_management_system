<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

// Verify database connection
if (!isset($con)) {
    die("Database connection error");
}

// Create image directory if it doesn't exist
if (!file_exists('imgdonor')) {
    mkdir('imgdonor', 0755, true);
}

if (isset($_POST['submit'])) {
    // Initialize variables
    $profile_img = '';
    $errors = [];

    // Validate inputs
    if (empty($_POST['name'])) {
        $errors[] = "Name is required";
    }
    if (empty($_POST['email_id'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST['email_id'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($_POST['contact_no'])) {
        $errors[] = "Contact number is required";
    }

    // Process file upload if present
    if (!empty($_FILES["profile_img"]["name"])) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($_FILES["profile_img"]["type"], $allowed_types)) {
            if ($_FILES["profile_img"]["size"] <= $max_size) {
                $profile_img = uniqid() . '_' . basename($_FILES["profile_img"]["name"]);
                $target_file = "imgdonor/" . $profile_img;
                
                if (!move_uploaded_file($_FILES["profile_img"]["tmp_name"], $target_file)) {
                    $errors[] = "Error uploading profile image";
                }
            } else {
                $errors[] = "Image size too large (max 2MB)";
            }
        } else {
            $errors[] = "Only JPG, PNG & GIF files are allowed";
        }
    }

    if (empty($errors) && isset($_SESSION['donor_id'])) {
        // Escape all inputs
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $city = mysqli_real_escape_string($con, $_POST['city']);
        $pin_code = mysqli_real_escape_string($con, $_POST['pin_code']);
        $email_id = mysqli_real_escape_string($con, $_POST['email_id']);
        $contact_no = mysqli_real_escape_string($con, $_POST['contact_no']);
        $donor_id = mysqli_real_escape_string($con, $_SESSION['donor_id']);

        // Build SQL query
        $sql = "UPDATE donor SET 
                name = '$name',
                address = '$address',
                city = '$city',
                pin_code = '$pin_code',
                email_id = '$email_id',
                contact_no = '$contact_no'";
        
        // Add profile image if uploaded
        if (!empty($profile_img)) {
            // Delete old image if exists
            if (!empty($rsedit['profile_img']) && file_exists("imgdonor/" . $rsedit['profile_img'])) {
                unlink("imgdonor/" . $rsedit['profile_img']);
            }
            $sql .= ", profile_img = '$profile_img'";
        }
        
        $sql .= " WHERE donor_id = '$donor_id'";
        
        // Execute query
        if (mysqli_query($con, $sql)) {
            if (mysqli_affected_rows($con) > 0) {
                echo "<script>alert('Profile updated successfully');</script>";
                // Refresh to show updated data
                echo "<script>window.location.href = window.location.href;</script>";
                exit();
            }
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
        }
    } elseif (!empty($errors)) {
        echo "<script>alert('" . implode("\\n", $errors) . "');</script>";
    }
}

// Fetch current donor data
if (isset($_SESSION['donor_id'])) {
    $sqledit = "SELECT * FROM donor WHERE donor_id = '" . mysqli_real_escape_string($con, $_SESSION['donor_id']) . "'";
    $qsqledit = mysqli_query($con, $sqledit);
    $rsedit = mysqli_fetch_array($qsqledit);
}
?>

<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>Donor Profile</h1>
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
                            <?php
                            if (empty($rsedit['profile_img'])) {
                                echo "<img class='media-object' src='img/no-image-icon.png' style='width: 100px;height: 100px;'>";
                            } elseif (file_exists("imgdonor/" . $rsedit['profile_img'])) {
                                echo "<img class='media-object' src='imgdonor/" . $rsedit['profile_img'] . "' style='width: 100px;height: 100px;'>";
                            } else {
                                echo "<img class='media-object' src='img/no-image-icon.png' style='width: 100px;height: 100px;'>";
                            }
                            ?>
                        </div>
                        <div class="media-body">
                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">Name</div>
                                    <div class="col-md-10">
                                        <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($rsedit['name']); ?>" required>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">Profile Image</div>
                                    <div class="col-md-10">
                                        <input type="file" name="profile_img" id="profile_img" class="form-control" accept="image/jpeg,image/png,image/gif">
                                        <?php
                                        if (empty($rsedit['profile_img'])) {
                                            echo "<img src='img/no-image-icon.png' style='height: 300px;'>";
                                        } elseif (file_exists("imgdonor/" . $rsedit['profile_img'])) {
                                            echo "<img src='imgdonor/" . $rsedit['profile_img'] . "' style='height: 250px;'>";
                                        } else {
                                            echo "<img src='img/no-image-icon.png' style='height: 300px;'>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">Address</div>
                                    <div class="col-md-10">
                                        <textarea name="address" id="address" class="form-control"><?php echo htmlspecialchars($rsedit['address']); ?></textarea>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">City</div>
                                    <div class="col-md-10">
                                        <input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($rsedit['city']); ?>">
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">Pin Code</div>
                                    <div class="col-md-10">
                                        <input type="text" name="pin_code" id="pin_code" class="form-control" value="<?php echo htmlspecialchars($rsedit['pin_code']); ?>">
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">Email</div>
                                    <div class="col-md-10">
                                        <input type="email" name="email_id" id="email_id" class="form-control" value="<?php echo htmlspecialchars($rsedit['email_id']); ?>" required>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2" style="padding-top: 5px;">Contact No</div>
                                    <div class="col-md-10">
                                        <input type="text" name="contact_no" id="contact_no" class="form-control" value="<?php echo htmlspecialchars($rsedit['contact_no']); ?>" required>
                                    </div>
                                </div>

                                <br>

                                <div class="row">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-10">
                                        <input type="submit" name="submit" class="btn btn-success" style="width: 200px;" value="Update Profile">
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