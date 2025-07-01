<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

if(isset($_POST['submit']))
{
    // Secure all inputs with mysqli_real_escape_string
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $pin_code = mysqli_real_escape_string($con, $_POST['pin_code']);
    $email_id = mysqli_real_escape_string($con, $_POST['email_id']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $contact_no = mysqli_real_escape_string($con, $_POST['contact_no']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    if(isset($_GET['editid']))
    {
        $sql = "UPDATE donor SET 
                name='$name',
                address='$address',
                city='$city',
                pin_code='$pin_code',
                email_id='$email_id',
                password='$password',
                contact_no='$contact_no',
                status='$status' 
                WHERE donor_id='".mysqli_real_escape_string($con, $_GET['editid'])."'";
        
        $qsql = mysqli_query($con,$sql);
        if(mysqli_affected_rows($con) == 1)
        {
            echo "<script>alert('Donor record updated successfully.');</script>";
        }
        else
        {
            echo mysqli_error($con);
        }        
    }
    else
    {
        $sql = "INSERT INTO donor(name,address,city,pin_code,email_id,password,contact_no,status) 
                VALUES('$name','$address','$city','$pin_code','$email_id','$password','$contact_no','$status')";
        
        $qsql = mysqli_query($con,$sql);
        if(mysqli_affected_rows($con) == 1)
        {
            echo "<script>alert('Donor record inserted successfully.');</script>";
            echo "<script>window.location='donor.php';</script>";
        }
        else
        {
            echo mysqli_error($con);
        }
    }
}

if(isset($_GET['editid']))
{
    $sqledit = "SELECT * FROM donor WHERE donor_id='".mysqli_real_escape_string($con, $_GET['editid'])."'";
    $qsqledit = mysqli_query($con,$sqledit);
    $rsedit = mysqli_fetch_array($qsqledit);
}
?>

<div id="page-header">
    <div class="section-bg" style="background-image: url(img/background-2.jpg);"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="header-content">
                    <h1>Donor</h1>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            <div class="media-heading">
                                <h4>Donor</h4>
                            </div>
                            <p>
                                <form method="post" action="">
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Name</div>
                                        <div class="col-md-10">
                                            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($rsedit['name']) ? htmlspecialchars($rsedit['name']) : ''; ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Address</div>
                                        <div class="col-md-10">
                                            <textarea name="address" id="address" class="form-control"><?php echo isset($rsedit['address']) ? htmlspecialchars($rsedit['address']) : ''; ?></textarea>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">City</div>
                                        <div class="col-md-10">
                                            <input type="text" name="city" id="city" class="form-control" value="<?php echo isset($rsedit['city']) ? htmlspecialchars($rsedit['city']) : ''; ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Pin Code</div>
                                        <div class="col-md-10">
                                            <input type="text" name="pin_code" id="pin_code" class="form-control" value="<?php echo isset($rsedit['pin_code']) ? htmlspecialchars($rsedit['pin_code']) : ''; ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Email Id</div>
                                        <div class="col-md-10">
                                            <input type="email" name="email_id" id="email_id" class="form-control" value="<?php echo isset($rsedit['email_id']) ? htmlspecialchars($rsedit['email_id']) : ''; ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Password</div>
                                        <div class="col-md-10">
                                            <input type="password" name="password" id="password" class="form-control" value="<?php echo isset($rsedit['password']) ? htmlspecialchars($rsedit['password']) : ''; ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Contact no</div>
                                        <div class="col-md-10">
                                            <input type="text" name="contact_no" id="contact_no" class="form-control" value="<?php echo isset($rsedit['contact_no']) ? htmlspecialchars($rsedit['contact_no']) : ''; ?>">
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Status</div>
                                        <div class="col-md-10">
                                            <select class="form-control" name="status" id="status">
                                                <option value="">Select Status</option>
                                                <?php
                                                $arr = array("Active","Inactive");
                                                foreach($arr as $val)
                                                {
                                                    $selected = (isset($rsedit['status']) && $rsedit['status'] == $val) ? 'selected' : '';
                                                    echo "<option value='".htmlspecialchars($val)."' $selected>".htmlspecialchars($val)."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;"></div>
                                        <div class="col-md-10">
                                            <input type="submit" name="submit" id="submit" class="form-control btn btn-success" style="width: 200px;">
                                        </div>
                                    </div>
                                </form>
                            </p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>