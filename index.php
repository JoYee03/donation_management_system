<?php
include("header.php");
?>

<div id="home-owl" class="owl-carousel owl-theme">

<div class="home-item">

<div class="section-bg" style="background-image: url(img/charity/IMG_20191210_133502.jpg);"></div>

<div class="home">
<div class="container">
<div class="row">
<div class="col-md-8">
<div class="home-content">
<h1>Donation Management System</h1>
<p class="lead">Facilitating responsible consumption and community support through efficient donation management.</p>
</div>
</div>
</div>
</div>
</div>

</div>


<div class="home-item">

<div class="section-bg" style="background-image: url(img/charity/IMG_20191210_133505.jpg);"></div>


<div class="home">
<div class="container">
<div class="row">
<div class="col-md-8">
<div class="home-content">
<h1>Donation Management System</h1>
<p class="lead">CONNECTING THOSE WITH SURPLUS RESOURCES TO THOSE IN NEED.</p>
</div>
</div>
</div>
</div>
</div>

</div>

</div>

</header>


<div id="about" class="section">

<div class="container">

<div class="row">

<div class="col-md-5">
<div class="section-title">
<h2 class="title">About Our Donation System</h2>
<p class="sub-title">A digital platform designed to support responsible consumption and reduce waste by facilitating the donation and reuse of second-hand items.</p>
</div>
<div class="about-content">
<p>Our Donation Management System aligns with Sustainable Development Goal 12, creating a bridge between donors and recipients through an efficient, transparent platform. The system features secure user verification, real-time tracking of donations and role-based access controls to ensure smooth operation.</p>
<a href="about.php" class="primary-button">Read More</a>
</div>
</div>


<div class="col-md-offset-1 col-md-6">
<a href="about.php" class="about-video" style="height: 550px;">
<img src="img/charity/IMG_20191210_124518.jpg" alt="">
</a>
</div>

</div>

</div>

</div>


<div id="numbers" class="section">

	<div class="container">

	<div class="row">

		<div class="col-md-3 col-sm-6">
		<div class="number">
		<i class="fa fa-smile-o"></i>
		<h3>
			<?php
			$sql ="SELECT * FROM donor";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
		</h3>
		<span>Donors</span>
		</div>
		</div>


		<div class="col-md-3 col-sm-6">
		<div class="number">
		<i class="fa fa-heartbeat"></i>
		<h3>
		<?php
		$sql ="SELECT * FROM receiver";
		$qsql = mysqli_query($con,$sql);
		echo mysqli_num_rows($qsql);
		?>
		</h3>
		<span>Receivers</span>
		</div>
		</div>


		<div class="col-md-3 col-sm-6">
		<div class="number">
		<i class="fa fa-money"></i>
		<h3><?php
		$sql ="SELECT sum(paid_amt) FROM fund_collection";
		$qsql = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($qsql);
		echo "RM".round($rs[0]);
		?></h3>
		<span>Donated</span>
		</div>
		</div>


		<div class="col-md-3 col-sm-6">
		<div class="number">
		<i class="fa fa-handshake-o"></i>
		<h3><?php
		$sql ="SELECT * FROM items";
		$qsql = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($qsql);
		echo mysqli_num_rows($qsql);
		?></h3>
		<span>Items Donate</span>
		</div>
		</div>

	</div>

	</div>

</div>

<div id="cta" class="section">

<div class="section-bg" style="background-image: url(img/charity/downloadfile.jpg);" data-stellar-background-ratio="0.5"></div>


<div class="container">

<div class="row">

<div class="col-md-offset-2 col-md-8">
<div class="cta-content text-center">
<h1>Join Our Donation Community</h1>
<a href="" class="primary-button" onclick="return false" data-toggle="modal" data-target="#DonorLoginModal">Login Panel...</a>
<a href="" class="primary-button" onclick="return false" data-toggle="modal" data-target="#DonorRegisterModal">Join Us Now...</a>
</div>
</div>

</div>

</div>

</div>


<div id="events" class="section">

<div class="container">

<div class="row">

<div class="col-md-8 col-md-offset-2">
<div class="section-title text-center">
<h2 class="title">System Features</h2>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="#">
<img src="img/event-1.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="#">Help people in need</a></h3>
<p>Provide direct support to an individual, family or community by paying medical expenses or offering financial aid..</p>
</div>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="#">
<img src="img/event-2.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="#">Take action in an emergency</a></h3>

<p>Raise funds in response to a natural disaster or humanitarian crisis. Make a difference in minutes.</p>
</div>
</div>
</div>

<div class="clearfix visible-md visible-lg"></div>

<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="#">
<img src="img/event-3.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="#">Take part in a charity event</a></h3>

<p>Choose from hundreds of official events including marathons, bike rides and bake offs….</p>
</div>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="#">
<img src="img/event-4.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="#">Donation Tracking</a></h3>
<p>Real-time status updates and notifications for all donation activities from posting to fulfillment.</p>
</div>
</div>
</div>

</div>

</div>

</div>

<hr>

<div id="causes" class="section" style="padding: 5px;">

<div class="container">

<div class="row">

<div class="col-md-8 col-md-offset-2">
<div class="section-title text-center">
<h2 class="title">Fund Raiser</h2>
<p class="sub-title">Fundraising or fund-raising is the process of seeking and gathering voluntary financial contributions by engaging individuals, businesses, charitable foundations, or governmental agencies..</p>
</div>
</div>


<?php
$sql = "SELECT * FROM fund_raiser where status='Active' order by fund_raiser_id DESC limit 3";
$qsql = mysqli_query($con,$sql);
while($rs = mysqli_fetch_array($qsql))
{
    $perc = 0;
    $sqlfund_collection = "SELECT SUM(paid_amt) FROM fund_collection where fund_raiser_id='$rs[0]' AND status='Active'";
    $qsqlfund_collection = mysqli_query($con,$sqlfund_collection);
    $rsfund_collection = mysqli_fetch_array($qsqlfund_collection);

    // Calculate percentage (with check to avoid division by zero)
    $perc = ($rs['fund_amount'] > 0) ? ($rsfund_collection[0] * 100 / $rs['fund_amount']) : 0;
    $perc = number_format($perc, 0); // Round to whole number
?>
<div class="col-md-4">
    <div class="causes">
        <div class="causes-img">
            <a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs[0]; ?>">        
            <?php
            if($rs['banner_img'] == "" || !file_exists("imgfundraiser/".$rs['banner_img'])) {
                echo "<img src='img/no-image-icon.png' style='height: 300px; width: 100%; object-fit: contain; background: #f5f5f5;'>";
            } else {
                echo "<img src='imgfundraiser/".$rs['banner_img']."' style='height: 300px; width: 100%; object-fit: cover;'>";
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
                <span class="causes-raised">Raised: <strong>RM<?php echo number_format($rsfund_collection[0], 2); ?></strong></span>
                <span class="causes-goal">Goal: <strong>RM<?php echo number_format($rs['fund_amount'], 2); ?></strong></span>
            </div>
        </div>
        <div class="causes-content">
            <h3><a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs[0]; ?>"><?php echo htmlspecialchars($rs['title']); ?></a></h3>
            <p><?php echo htmlspecialchars(substr($rs['fund_raiser_description'],0,100)).'...'; ?></p>
            <a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs[0]; ?>" class="primary-button causes-donate">Donate Now</a>
        </div>
    </div>
</div>
<?php
}
?>


<div class="clearfix visible-md visible-lg"></div>
</div>
<hr>
</div>

</div>


<div id="events" class="section">
<div class="section-title text-center">

</div>

</div>

<?php
include("footer.php");
?>