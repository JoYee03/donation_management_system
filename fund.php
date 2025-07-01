<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");

if(isset($_POST['submit']))
{
    $description = mysqli_real_escape_string($con,$_POST['fund_raiser_description']);
    
    // Process image only if new one was uploaded
    if(!empty($_FILES["banner_img"]["name"]))
    {
        $banner_img = rand() . $_FILES["banner_img"]["name"];
        move_uploaded_file($_FILES["banner_img"]["tmp_name"],"imgfundraiser/".$banner_img);
    }
    
    if(isset($_GET['editid']))
    {
        $sql = "UPDATE fund_raiser SET 
                title='".mysqli_real_escape_string($con,$_POST['title'])."',
                fund_raiser_description='$description',
                fund_amount='".mysqli_real_escape_string($con,$_POST['fund_amount'])."',
                start_date='".mysqli_real_escape_string($con,$_POST['start_date'])."', 
                end_date='".mysqli_real_escape_string($con,$_POST['end_date'])."',
                status='".mysqli_real_escape_string($con,$_POST['status'])."'";
        
        if(!empty($_FILES["banner_img"]["name"]))
        {
            $sql .= ", banner_img='$banner_img'";
        }
        
        $sql .= " WHERE fund_raiser_id='".mysqli_real_escape_string($con,$_GET['editid'])."'";
        
        $qsql = mysqli_query($con,$sql);
        if(mysqli_affected_rows($con) == 1)
        {
            echo "<script>alert('Fund Raiser updated successfully.');</script>";
        }
        else
        {
            echo mysqli_error($con);
        }        
    }
    else
    {
        // For new entries, require an image
        if(empty($_FILES["banner_img"]["name"]))
        {
            echo "<script>alert('Banner image is required for new fund raisers.');</script>";
        }
        else
        {
            $sql = "INSERT INTO fund_raiser(title,fund_raiser_description,fund_amount,start_date,end_date,status,banner_img) 
                    VALUES(
                        '".mysqli_real_escape_string($con,$_POST['title'])."',
                        '$description',
                        '".mysqli_real_escape_string($con,$_POST['fund_amount'])."',
                        '".mysqli_real_escape_string($con,$_POST['start_date'])."',
                        '".mysqli_real_escape_string($con,$_POST['end_date'])."',
                        '".mysqli_real_escape_string($con,$_POST['status'])."',
                        '$banner_img'
                    )";
            $qsql = mysqli_query($con,$sql);
            if(mysqli_affected_rows($con) == 1)
            {
                echo "<script>alert('Fund Raiser added successfully.');</script>";
            }
            else
            {
                echo mysqli_error($con);
            }
        }
    }
}
?>

<?php
if(isset($_GET['editid']))
{
    $sqledit = "SELECT * FROM fund_raiser WHERE fund_raiser_id='".mysqli_real_escape_string($con,$_GET['editid'])."'";
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
                    <h1>Fund Raiser</h1>
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
                                <h4>Fund Raiser</h4>
                            </div>
                            <p>
                                <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateform()">
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Title</div>
                                        <div class="col-md-10">
                                            <input type="text" name="title" id="title" class="form-control" value="<?php echo isset($rsedit['title']) ? htmlspecialchars($rsedit['title']) : ''; ?>">
                                            <span id="errtitle" class="errorclass"></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Banner Image</div>
                                        <div class="col-md-10">
                                            <input type="file" name="banner_img" id="banner_img" class="form-control">
                                            <small class="text-muted"><?php echo isset($_GET['editid']) ?></small>
                                            <span id="errbanner_img" class="errorclass"></span>
                                            <?php
                                            if(isset($rsedit['banner_img']) && $rsedit['banner_img'] != "" && file_exists("imgfundraiser/".$rsedit['banner_img']))
                                            {
                                                echo "<img src='imgfundraiser/".htmlspecialchars($rsedit['banner_img'])."' style='width: 250px;height: 250px;'>";
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Description</div>
                                        <div class="col-md-10">
                                            <textarea name="fund_raiser_description" id="fund_raiser_description" class="form-control"><?php echo isset($rsedit['fund_raiser_description']) ? htmlspecialchars_decode($rsedit['fund_raiser_description']) : ''; ?></textarea>
                                            <span id="errfund_raiser_description" class="errorclass"></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Start Date</div>
                                        <div class="col-md-10">
                                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                                   value="<?php echo isset($rsedit['start_date']) ? htmlspecialchars($rsedit['start_date']) : ''; ?>" 
                                                   min="<?php echo date("Y-m-d"); ?>">
                                            <span id="errstart_date" class="errorclass"></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">End Date</div>
                                        <div class="col-md-10">
                                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                                   value="<?php echo isset($rsedit['end_date']) ? htmlspecialchars($rsedit['end_date']) : ''; ?>" 
                                                   min="<?php echo date("Y-m-d"); ?>">
                                            <span id="errend_date" class="errorclass"></span>
                                        </div>
                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-md-2" style="padding-top: 5px;">Amount</div>
                                        <div class="col-md-10">
                                            <input type="text" name="fund_amount" id="fund_amount" class="form-control" value="<?php echo isset($rsedit['fund_amount']) ? htmlspecialchars($rsedit['fund_amount']) : ''; ?>">
                                            <span id="errfund_amount" class="errorclass"></span>
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
                                            <span id="errstatus" class="errorclass"></span>
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

<script src="https://cdn.tiny.cloud/1/vkp7vwptosm1ao2ztjqdp0riscxgp2sxw81z6ma02p9h4oqc/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({ selector:'textarea' });
    
    function validateform()
    {
        var i = 0;	
        $('.errorclass').html('');
        
        var numericExp = /^[0-9]+$/;
        var alphaSpaceNumericExp = /^[0-9a-zA-Z\s]+$/;
        
        if(!document.getElementById("title").value.match(alphaSpaceNumericExp))
        {
            document.getElementById("errtitle").innerHTML = "Entered title not valid...";
            i = 1;
        }
        if(document.getElementById("title").value=="")
        {
            document.getElementById("errtitle").innerHTML = "Kindly enter title...";
            i = 1;
        }
        
        // Only validate banner_img if it's a new entry
        if(<?php echo isset($_GET['editid']) ? 'false' : 'true'; ?> && document.getElementById("banner_img").value=="")
        {
            document.getElementById("errbanner_img").innerHTML = "Kindly enter banner image...";
            i = 1;
        }
        
        if(document.getElementById("fund_raiser_description").value=="")
        {
            document.getElementById("errfund_raiser_description").innerHTML = "Kindly enter fund raiser description...";
            i = 1;
        }
        if(document.getElementById("start_date").value=="")
        {
            document.getElementById("errstart_date").innerHTML = "Kindly enter the Start date...";
            i = 1;
        }
        if(document.getElementById("end_date").value=="")
        {
            document.getElementById("errend_date").innerHTML = "Kindly enter End date...";
            i = 1;
        }
        
        if(!document.getElementById("fund_amount").value.match(numericExp))
        {
            document.getElementById("errfund_amount").innerHTML = "Entered Fund Amount is not valid...";
            i = 1;
        }
        if(document.getElementById("fund_amount").value=="")
        {
            document.getElementById("errfund_amount").innerHTML = "Kindly enter the Fund amount...";
            i = 1;
        }
        
        if(document.getElementById("status").value=="")
        {
            document.getElementById("errstatus").innerHTML = "kindly select status...";
            i = 1;
        }
        
        return i === 0;
    }
</script>