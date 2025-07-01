<?php
include("header.php");
if(!isset($_SESSION['receiver_id']))
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
					<p class="lead">Find the help you need through our generous donors.</p>
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
<p class="lead">THIS NEW YEAR, FIND THE SUPPORT YOU NEED FOR YOUR HEALTH, EDUCATION AND DREAMS.</p>
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
<p> DMS enables individuals in need to find support through donations from generous individuals and organizations. 
	By streamlining the donation process, it ensures timely support for those who need it most, creating a meaningful impact on underprivileged communities.</p>
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
		$sql ="SELECT * FROM items";
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
<h1>Need help?</h1>
<a href="listeditems.php" class="primary-button">Check Listed Items Available</a>
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
<a href="single-event.html">
<img src="img/event-1.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="single-event.html">Find support for your needs</a></h3>
<p>Access essential items and resources donated by generous individuals and organizations in your community.</p>
</div>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="single-event.html">
<img src="img/event-2.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="single-event.html">Get help in emergencies</a></h3>

<p>Find immediate assistance during difficult times through our network of donors and support services.</p>
</div>
</div>
</div>

<div class="clearfix visible-md visible-lg"></div>

<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="single-event.html">
<img src="img/event-3.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="single-event.html">Connect with community resources</a></h3>

<p>Discover local programs and services that can provide additional support for your situation.</p>
</div>
</div>
</div>


<div class="col-md-6">
<div class="event">
<div class="event-img">
<a href="single-event.html">
<img src="img/event-4.jpg" alt="">
</a>
</div>
<div class="event-content">
<h3><a href="single-event.html">Receive essential items</a></h3>
<p>Get access to donated goods that can help with your daily needs and improve your quality of life.</p>
</div>
</div>
</div>

</div>

</div>

</div>

<?php
include("footer.php");
?>