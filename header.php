<?php
if(!isset($_SESSION)) { 
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	} 
}
error_reporting(E_ALL & ~E_NOTICE  &  ~E_STRICT  &  ~E_WARNING);
$dt = date("Y-m-d");
$currencySymbol = "RM";
include("databaseconnection.php");
if(isset($_POST['btndonorregister']))
{
	$sql ="INSERT INTO donor(name,email_id,password,contact_no,status) VALUES ('$_POST[name]','$_POST[donoremailid]','$_POST[donornpassword]','$_POST[contactno]','Active')";
	$qsql = mysqli_query($con,$sql);
	if(mysqli_affected_rows($con) == 1)
	{
		echo "<script>alert('Donor Registration done successfully..');</script>";
		echo "<script>window.location='index.php';</script>";
	}
	else
	{
		echo mysqli_error($con);
	}
}
if(isset($_POST['btndonorlogin']))
{
	$sql  ="SELECT * FROM donor WHERE email_id='$_POST[donoremail_id]' AND password='$_POST[donorpassword]' AND status='Active'";
	$qsql =mysqli_query($con,$sql);
	if(mysqli_num_rows($qsql)  == 1)
	{
		$rs = mysqli_fetch_array($qsql);
		$_SESSION['donor_id'] = $rs['donor_id'];
		echo "<script>window.location='donoraccount.php';</script>";
	}
	else
	{
		echo "<script>alert('You have entered Invalid login credentials..');</script>";
	}
}
if(isset($_POST['btnreceiverlogin']))
{
	$sql  ="SELECT * FROM receiver WHERE email_id='$_POST[receiveremail_id]' AND password='$_POST[receiverpassword]' AND status='Active'";
	$qsql =mysqli_query($con,$sql);
	if(mysqli_num_rows($qsql)  == 1)
	{
		$rs = mysqli_fetch_array($qsql);
		$_SESSION['receiver_id'] = $rs['receiver_id'];
		echo "<script>window.location='receiveraccount.php';</script>";
	}
	else
	{
		echo "<script>alert('You have entered Invalid login credentials..');</script>";
	}
}
if(isset($_POST['btnstafflogin']))
{
	$sql  ="SELECT * FROM staff WHERE login_id='$_POST[staffloginid]' AND password='$_POST[staffpassword]' AND status='Active'";
	$qsql =mysqli_query($con,$sql);
	if(mysqli_num_rows($qsql)  == 1)
	{
		$rs = mysqli_fetch_array($qsql);
		$_SESSION['staff_id'] = $rs['staff_id'];
		echo "<script>window.location='dashboard.php';</script>";
	}
	else
	{
		echo "<script>alert('You have entered Invalid login credentials..');</script>";
	}
}
if(isset($_SESSION['donor_id']))
{
	$sqldonor  ="SELECT * FROM donor WHERE donor_id='$_SESSION[donor_id]'";
	$qsqldonor =mysqli_query($con,$sqldonor);
	echo mysqli_error($con);
	$rsdonor = mysqli_fetch_array($qsqldonor);
}
if(isset($_SESSION['receiver_id']))
{
	$sqlreceiver  ="SELECT * FROM receiver WHERE receiver_id='$_SESSION[receiver_id]'";
	$qsqlreceiver =mysqli_query($con,$sqlreceiver);
	echo mysqli_error($con);
	$rsreceiver = mysqli_fetch_array($qsqlreceiver);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>DONATION MANAGEMENT SYSTEM</title>

<link href="https://fonts.googleapis.com/css?family=Roboto:300,400%7CSource+Sans+Pro:700" rel="stylesheet">

<link type="text/css" rel="stylesheet" href="css/bootstrap.min.css" />
<link type="text/css" rel="stylesheet" href="css/owl.carousel.css" />
<link type="text/css" rel="stylesheet" href="css/owl.theme.default.css" />
<link rel="stylesheet" href="css/font-awesome.min.css">
<link type="text/css" rel="stylesheet" href="css/style.css" />
<link type="text/css" rel="stylesheet" href="css/jquery.dataTables.min.css" />
<style>
@import "compass/css3";

/*Be sure to look into browser prefixes for keyframes and annimations*/
.errorclass {
   animation-name: flash;
    animation-duration: 0.2s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
    animation-direction: alternate;
    animation-play-state: running;
}

@keyframes flash {
    from {color: red;}
    to {color: black;}
}
</style>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php
if(basename($_SERVER['PHP_SELF']) == "index.php")
{
?>
<header id="home">
<?php
}
else
{
?>
<header >
<?php
}
?>
<nav id="main-navbar">
<div class="container">
<div class="navbar-header">

<div class="navbar-brand">
<a class="logo" href="index.php"><img src="img/logo.png" alt="logo"></a>
</div>


<button class="navbar-toggle-btn">
<i class="fa fa-bars"></i>
</button>


<button class="search-toggle-btn">
<i class="fa fa-search"></i>
</button>

</div>

<?php

?>

<ul class="navbar-menu nav navbar-nav navbar-right">

<?php

if(isset($_SESSION['donor_id']))
{
?>
<li><a href="donoraccount.php">Panel</a></li>

	<li class="has-dropdown"><a href="#">Fund Donation</a>
		<ul class="dropdown">
			<li><a href="fundraiser.php" >Fund Raiser</a></li>
			<li><a href="viewfundcollection.php">Fund Donation Report</a></li>
		</ul>
	</li>

	<li class="has-dropdown"><a href="#">Item Donation</a>
		<ul class="dropdown">
			<li><a href="itemdonor.php">Donate Items</a></li>
			<li><a href="viewitemdonor.php">Item Donation Report</a></li>
		</ul>
	</li>


	<li class="has-dropdown"><a href="#">Account</a>
		<ul class="dropdown">
			<li><a href="donorprofile.php">Donor Profile</a></li>
			<li><a href="donorchangepassword.php" >Change password</a></li>
			<li><a href="logout.php" >Logout</a></li>
		</ul>
</li>
<?php
}
else if(isset($_SESSION['receiver_id']))
{
	?>
	<li><a href = "receiveraccount.php">Panel</a>

	<li class="has-dropdown"><a href="#">Request and History</a>
		<ul class="dropdown">
			<li><a href="listeditems.php" >View Item</a></li>
			<li><a href="viewrequest.php">View Request Record</a></li>
		</ul>
	</li>

	<li class="has-dropdown"><a href="#">Account</a>
		<ul class="dropdown">
			<li><a href="receiverprofile.php">Receiver Profile</a></li>
			<li><a href="receiverchangepassword.php" >Change password</a></li>
			<li><a href="logout.php" >Logout</a></li>
		</ul>
	</li>

	</li>
	<?php
}
else if(isset($_SESSION['staff_id']))
{
?>
<li><a href="dashboard.php">Dashboard</a></li>

<li class="has-dropdown"><a href="#">Fund Raiser</a>
	<ul class="dropdown">
		<li><a href="fund.php" >Add Fund Raiser</a></li>
		<li><a href="viewfundcollection.php" >View fund collection</a></li>
		<li><a href="viewfund.php" >View fund Raiser</a></li>
	</ul>
</li>

<li class="has-dropdown"><a href="#">Receivers</a>
	<ul class="dropdown">
		<li><a href="receiver.php" >Add Receiver</a></li>
		<li><a href="viewreceiver.php" >View Receivers</a></li>
		<li><a href="receivertype.php" >Add Receiver Type</a></li>
		<li><a href="viewreceivertype.php" >View Receiver Type</a></li>
	</ul>
</li>
<li class="has-dropdown"><a href="#">Report</a>
	<ul class="dropdown">
		<li><a href="viewdonor.php" >View Donor</a></li>
		<li><a href="viewitemdonor.php" >View Item Donation</a></li>
		<li><a href="approverequest.php" >View Request</a></li>
	</ul>
</li>
 

<li class="has-dropdown"><a href="#">Staff Account</a>
	<ul class="dropdown">
		<li><a href="staffprofile.php">Staff Profile</a></li>
		<li><a href="staffchangepassword.php" >Change password</a></li>
		<li><a href="staff.php" >Add Staff  </a></li>
		<li><a href="viewstaff.php" >View Staff </a></li>
		<li><a href="logout.php" >Logout</a></li>
	</ul>
</li>
<?php
}
else
{
?>
<li><a href="index.php">Home</a></li>
<li><a href="about.php">About</a></li>
<li><a href="fundraiser.php">Fund Raiser</a></li>
<li><a href="displayreceivers.php">Receivers</a></li>
<li><a href="contact.php">Contact</a></li>
<li class="has-dropdown"><a href="#">Login</a>
	<ul class="dropdown">
		<li><a href="" onclick="return false;"  data-toggle="modal" data-target="#DonorLoginModal" >Donor Login</a></li>
		<li><a href="" onclick="return false;" data-toggle="modal" data-target="#DonorRegisterModal">Donor Register</a></li>
		<li><a href="" onclick="return false;" data-toggle="modal" data-target="#ReceiverLoginModal">Receiver Login</a></li>
	</ul>
</li>
<?php
}
?>
</ul>

</div>
</nav>