<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

// Security check - ensure donor is logged in
if(!isset($_SESSION['donor_id'])) {
    echo "<script>alert('Please login to access this page'); window.location='index.php';</script>";
    exit;
}

$error = "";
$success = "";

if(isset($_POST['submit'])) {
    // Sanitize inputs
    $current_password = mysqli_real_escape_string($con, $_POST['opassword']);
    $new_password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirmpassword']);

    // Validate passwords match
    if($new_password !== $confirm_password) {
        $error = "New password and confirmation password don't match!";
    } 
    // Validate password strength (optional)
    elseif(strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long!";
    }
    else {
        // Verify current password first
        $sql = "SELECT donor_id FROM donor WHERE donor_id='$_SESSION[donor_id]' AND password='$current_password'";
        $qsql = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($qsql) == 1) {
            // Update password
            $update_sql = "UPDATE donor SET password='$new_password' WHERE donor_id='$_SESSION[donor_id]'";
            $qupdate = mysqli_query($con, $update_sql);
            
            if(mysqli_affected_rows($con) == 1) {
                $success = "Password updated successfully!";
                // Clear form
                echo "<script>document.getElementById('passwordForm').reset();</script>";
            } else {
                $error = "Failed to update password: " . mysqli_error($con);
            }
        } else {
            $error = "Current password is incorrect!";
        }
    }
    
    // Show error/success message
    if($error) {
        echo "<script>alert('$error');</script>";
    }
    if($success) {
        echo "<script>alert('$success');</script>";
    }
}
?>

<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>Change Password</h1>
                </div>
            </div>
        </div>
    </div>
</div>

</header>

<div class="section" style="padding-top: 1px;">
    <div class="container">
        <div class="row">
            <main id="" class="col-md-12">
                <div class="article-comments">
                    <div class="media">
                        <div class="media-left">
                            <img class="media-object" src="img/charity.jpg" style="width: 100px;height: 100px;">
                        </div>
                        <div class="media-body">
                            <form method="post" action="" id="passwordForm" onsubmit="return validateform()">
                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Current Password</label>
                                    <div class="col-md-9">
                                        <input type="password" name="opassword" id="opassword" class="form-control" required>
                                        <span id="erropassword" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">New Password</label>
                                    <div class="col-md-9">
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        <small class="form-text text-muted">Minimum 8 characters</small>
                                        <span id="errpassword" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label">Confirm New Password</label>
                                    <div class="col-md-9">
                                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required>
                                        <span id="errconfirmpassword" class="text-danger"></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-9 offset-md-3">
                                        <input type="submit" name="submit" id="submit" class="btn btn-success" value="Change Password" style="width: 200px;">
                                        <a href="forgot-password.php" class="btn btn-link">Forgot Password?</a>
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
function validateform() {
    var valid = true;
    $('.text-danger').html('');
    
    // Current password check
    if($("#opassword").val() == "") {
        $("#erropassword").html("Please enter your current password");
        valid = false;
    }
    
    // New password check
    if($("#password").val() == "") {
        $("#errpassword").html("Please enter a new password");
        valid = false;
    } else if($("#password").val().length < 8) {
        $("#errpassword").html("Password must be at least 8 characters");
        valid = false;
    }
    
    // Confirm password check
    if($("#confirmpassword").val() == "") {
        $("#errconfirmpassword").html("Please confirm your new password");
        valid = false;
    } else if($("#password").val() !== $("#confirmpassword").val()) {
        $("#errconfirmpassword").html("Passwords don't match");
        valid = false;
    }
    
    return valid;
}

// Real-time password matching
$("#confirmpassword").on('keyup', function() {
    if($(this).val() !== $("#password").val()) {
        $("#errconfirmpassword").html("Passwords don't match");
    } else {
        $("#errconfirmpassword").html("");
    }
});
</script>