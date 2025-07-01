<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("header.php");	
?>
</header>

<div id="cta" class="section">
<div class="section-bg" style="background-image: url(img/charity/downloadfile.jpg);" data-stellar-background-ratio="0.5"></div>
<div class="container">
<div class="row">
<div class="col-md-offset-2 col-md-8">
<div class="cta-content text-center">
<h1>FUND RAISER</h1>
</div>
</div>
</div>
</div>
</div>

<div id="causes" class="section">
<div class="container">
<div class="row">

<?php
$sql = "SELECT * FROM fund_raiser where status='Active'";
$qsql = mysqli_query($con,$sql);
while($rs = mysqli_fetch_array($qsql))
{
    $perc=0;
    $sqlfund_collection = "SELECT SUM(paid_amt) FROM fund_collection where fund_raiser_id='".mysqli_real_escape_string($con, $rs['fund_raiser_id'])."' AND status='Active'";
    $qsqlfund_collection = mysqli_query($con,$sqlfund_collection);
    $rsfund_collection = mysqli_fetch_array($qsqlfund_collection);

    // Calculate percentage - added null checks
    $collected = $rsfund_collection[0] ?? 0;
    $goal = $rs['fund_amount'] ?? 1; // Avoid division by zero
    $perc = ($goal > 0) ? round(($collected * 100) / $goal) : 0;
?>
<div class="col-md-4">
    <div class="causes">
        <div class="causes-img">
        <a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs['fund_raiser_id']; ?>">        
        <?php
        if(empty($rs['banner_img']))
        {
            echo "<img src='img/no-image-icon.png' style='height: 300px;'>";
        }
        else if(file_exists("imgfundraiser/".$rs['banner_img']))
        {
            echo "<img src='imgfundraiser/".htmlspecialchars($rs['banner_img']). "'  style='height: 300px;'>";
        }
        else
        {
            echo "<img src='img/no-image-icon.png' style='height: 300px;' >";    
        }
        ?>
        </a>
        </div>
        <div class="causes-progress">
        <div class="causes-progress-bar">
        <div style="width: <?php echo $perc; ?>%;">
        <span><?php echo $perc ?>%</span>
        </div>
        </div>
        <div>
        <span class="causes-raised">Raised: <strong>RM<?php echo number_format($collected); ?></strong></span>
        <span class="causes-goal">Goal: <strong>RM<?php echo number_format($goal); ?></strong></span>
        </div>
        </div>
        <div class="causes-content">
        <h3><a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs['fund_raiser_id']; ?>"><?php echo htmlspecialchars($rs['title']); ?></a></h3>
        <p><?php echo substr(strip_tags(htmlspecialchars_decode($rs['fund_raiser_description'])),0,100).'...'; ?></p>
        
        <?php if(isset($_SESSION['donor_id'])) { ?>
            <a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs['fund_raiser_id']; ?>" class="primary-button causes-donate">Donate Now</a>
        <?php } else { ?>
            <a href="#" onclick="event.preventDefault(); $('#DonateLoginModal_<?php echo $rs['fund_raiser_id']; ?>').modal('show');" class="primary-button causes-donate">Donate Now</a>
            
            <div class="modal fade" id="DonateLoginModal_<?php echo $rs['fund_raiser_id']; ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Login to Donate</h4>
                        </div>
                        <div class="modal-body">
                            <form method="post">
                                <div class="form-group">
                                    <label>Email ID</label>
                                    <input type="email" class="form-control" name="donoremail_id" required>
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" name="donorpassword" required>
                                </div>
                                <button type="submit" name="btndonorlogin" class="btn btn-primary btn-block">Login</button>
                                
                                <div class="text-center" style="margin-top: 15px;">
                                    <p>Don't have an account yet? <br>
                                    <a href="#" onclick="$('#DonateLoginModal_<?php echo $rs['fund_raiser_id']; ?>').modal('hide'); $('#DonorRegisterModal').modal('show');">Register now!</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        
        </div>
    </div>
</div>
<?php
}
?>

</div>
</div>
</div>

<div class="modal fade" id="DonorRegisterModal" tabindex="-1" role="dialog" aria-labelledby="DonorRegisterModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="DonorRegisterModalLabel">Donor Registration</h4>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Email ID</label>
                        <input type="email" class="form-control" name="donoremailid" required>
                    </div>
                    <div class="form-group">
                        <label>Contact No</label>
                        <input type="text" class="form-control" name="contactno" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="donornpassword" required>
                    </div>
                     <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                        <small id="passwordHelp" class="text-danger" style="display:none">Passwords do not match!</small>
                    </div>

                    <button type="submit" name="btndonorregister" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include("footer.php");
?>