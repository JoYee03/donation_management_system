<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

// Check if receiver is logged in
if (!isset($_SESSION['receiver_id'])) {
    header("Location: receiveraccount.php");
    exit();
}

if(isset($_POST['submit'])) {
    // Verify old password and update new password
    $sql = "UPDATE receiver SET password='".mysqli_real_escape_string($con, $_POST['password'])."' 
            WHERE receiver_id='".$_SESSION['receiver_id']."' 
            AND password='".mysqli_real_escape_string($con, $_POST['opassword'])."'";
    $qsql = mysqli_query($con, $sql);
    
    if(mysqli_affected_rows($con) == 1) {
        echo "<script>alert('Password updated successfully.');</script>";
        echo "<script>window.location='receiverchangepassword.php';</script>";
    } else {
        echo "<script>alert('Failed to update password. Please check your current password.');</script>";
    }
}
?>

<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>Receiver - Change Password</h1>
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
                                    <p>
                                        <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateform()">
                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Current Password</div>
                                                <div class="col-md-10">
                                                    <input type="password" name="opassword" id="opassword" class="form-control">
                                                    <span id="erropassword" class="errorclass"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">New Password</div>
                                                <div class="col-md-10">
                                                    <input type="password" name="password" id="password" class="form-control">
                                                    <span id="errpassword" class="errorclass"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;">Confirm Password</div>
                                                <div class="col-md-10">
                                                    <input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
                                                    <span id="errconfirmpassword" class="errorclass"></span>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-md-2" style="padding-top: 5px;"></div>
                                                <div class="col-md-10">
                                                    <input type="submit" name="submit" id="submit" class="form-control btn btn-success" style="width: 200px;" value="Update Password">
                                                </div>
                                            </div>
                                        </form>
                                    </p>
                                </div>
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
    var i = 0;    
    $('.errorclass').html('');
    
    // Validate current password
    if(document.getElementById("opassword").value == "") {
        document.getElementById("erropassword").innerHTML = "Please enter your current password";
        i = 1;
    }
    
    // Validate new password
    if(document.getElementById("password").value == "") {
        document.getElementById("errpassword").innerHTML = "Please enter a new password";
        i = 1;
    } else if(document.getElementById("password").value.length < 8) {
        document.getElementById("errpassword").innerHTML = "Password must be at least 8 characters";
        i = 1;
    }
    
    // Validate password confirmation
    if(document.getElementById("confirmpassword").value == "") {
        document.getElementById("errconfirmpassword").innerHTML = "Please confirm your new password";
        i = 1;
    } else if(document.getElementById("password").value != document.getElementById("confirmpassword").value) {
        document.getElementById("errconfirmpassword").innerHTML = "Passwords do not match";
        i = 1;
    }
    
    return i === 0;
}
</script>