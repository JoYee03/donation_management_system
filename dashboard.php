<?php
include("header.php");
if(!isset($_SESSION['staff_id']))
{
	echo "<script>window.location='index.php';</script>";
}
?>
</header>


<div id="about" class="section">

<div class="container">

<div class="row">

<div class="col-md-12">
<div class="section-title">
	<center><h2 class="title">DASHBOARD</h2></center>
</div>
</div>



</div>

</div>

</div>


<div id="numbers" class="section">

<div class="container">

<div class="row">

	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/alubm.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM album";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Activities</span>
		</div>
	</div>
	
	
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/appointment.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM item_requests";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Approved Donation</span>
		</div>
	</div>
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/donor.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM donor";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Registered Donors</span>
		</div>
	</div>
	
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/food donation.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM receiver";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Registered Recipients</span>
		</div>
	</div>
	
	
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/fund1.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM fund_raiser";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Active Fundraiser</span>
		</div>
	</div>
	
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/fundcollect.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM fund_collection ";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Fundraiser Participants</span>
		</div>
	</div>
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/member_type.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM receiver_type ";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Receiver Type Records</span>
		</div>
	</div>
	
	
	<div class="col-md-3 col-sm-6">
		<div class="number">
			<img src="img/staff.jpg" style="width:100%;height: 150px;">
			<h3>
			<?php
			$sql ="SELECT * FROM staff ";
			$qsql = mysqli_query($con,$sql);
			echo mysqli_num_rows($qsql);
			?>
			</h3>
			<span>Staff Records</span>
		</div>
	</div>
	
	
	


</div>

</div>

</div>

<?php
include("footer.php");
?>