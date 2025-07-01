<?php
include("header.php");
if(!isset($_SESSION['donor_id']))
{
	echo "<script>window.location='index.php';</script>";
}
?>
<div id="home-owl" class="owl-carousel owl-theme">

<div class="home-item">

<div class="section-bg" style="background-image: url(img/charity/IMG_20191210_133502.jpg);"></div>

<div class="home">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="home-content">
					<h1>Donation Management</h1>
					<p class="lead">With your support, we can help more people in need.</p>
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
<h1>Donation Management</h1>
<p class="lead">THIS NEW YEAR, COME TOGETHER TO SUPPORT HEALTH, EDUCATION
AND DREAMS OF MILLION PEOPLE.</p>
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
<h2 class="title">Welcome to Donation Management</h2>
<p class="sub-title">The Donation Management System (DMS) is a non-profit initiative supporting vulnerable communities, including the elderly, economically disadvantaged and those with mental health challenges. 
	Located in Cyberjaya, Malaysia, DMS streamlines the collection, management and distribution of donations. </p>
</div>
<div class="about-content">
<p> DMS enables individuals and organizations to easily donate funds, food or resources, while tracking the impact of their contributions. 
	By streamlining the donation process, it ensures timely support for those in need, creating a meaningful impact on underprivileged communities.</p>
<a href="#" class="primary-button">Read More</a>
</div>
</div>


<div class="col-md-offset-1 col-md-6">
<a href="#" class="about-video" style="height: 550px;">
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
		$sql ="SELECT * FROM item_requests";
		$qsql = mysqli_query($con,$sql);
		$rs = mysqli_fetch_array($qsql);
		echo mysqli_num_rows($qsql);
		?></h3>
		<span>Item donors</span>
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
<h1>Start donating now!</h1>
<a href="fundraiser.php" class="primary-button">Donate Funds</a>
<a href="itemdonor.php" class="primary-button">Donate Items</a>
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
<h2 class="title">Donation Management</h2>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<img src="img/event-1.jpg" alt="">
</div>
<div class="event-content">
<h3>Help people in need</h3>
<p>Provide direct support to an individual, family or community by paying medical expenses or offering financial aid..</p>
</div>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<img src="img/event-2.jpg" alt="">
</div>
<div class="event-content">
<h3>Take action in an emergency</h3>

<p>Raise funds in response to a natural disaster or humanitarian crisis. Make a difference in minutes.</p>
</div>
</div>
</div>

<div class="clearfix visible-md visible-lg"></div>

<div class="col-md-6">
<div class="event">
<div class="event-img">
<img src="img/event-3.jpg" alt="">
</div>
<div class="event-content">
<h3>Take part in a charity event</h3>

<p>Choose from hundreds of official events including marathons, bike rides, Dryathlons and bake offs….</p>
</div>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<img src="img/event-4.jpg" alt="">
</div>
<div class="event-content">
<h3>Celebrate an occasion</h3>
<p>Mark a special event like a birthday, wedding or final exam by asking friends for donations rather than gifts..</p>
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
        <p><?php echo substr(htmlspecialchars($rs['fund_raiser_description']),0,100).'...'; ?></p>
        <a href="funraiserdetailed.php?fund_raiser_id=<?php echo $rs['fund_raiser_id']; ?>" class="primary-button causes-donate">Donate Now</a>
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

</div>

</div>

</div>


<?php
include("footer.php");
?>