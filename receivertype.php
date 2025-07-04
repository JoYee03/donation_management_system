<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
include("header.php");
if(isset($_POST['submit']))
{
	if(isset($_GET['editid']))
	{
		$sql ="UPDATE receiver_type SET receiver_type='$_POST[receiver_type]',description='$_POST[description]',status='$_POST[status]' WHERE receiver_type_id='$_GET[editid]'";
		$qsql = mysqli_query($con,$sql);
		if(mysqli_affected_rows($con) == 1)
		{
			echo "<script>alert('Receiver type record updated successfully..');</script>";
		}
		else
		{
			echo mysqli_error($con);
		}		
	}
	else
	{
		$sql ="INSERT INTO receiver_type(receiver_type,description,status) VALUES('$_POST[receiver_type]','$_POST[description]','$_POST[status]')";
		$qsql = mysqli_query($con,$sql);
		if(mysqli_affected_rows($con) == 1)
		{
			echo "<script>alert('Receiver type record inserted successfully..');</script>";
			echo "<script>window.location='receivertype.php';</script>";
		}
		else
		{
			echo mysqli_error($con);
		}
	}
}
?>
<?php
if(isset($_GET['editid']))
{
	$sqledit = "SELECT * FROM receiver_type WHERE receiver_type_id='$_GET[editid]'";
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
<h1>Receiver Type</h1>
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
<div class="">
<div class="">
<div class="article-comments">
<div class="media">
<div class="media-left">
<img class="media-object" src="img/charity.jpg" style="width: 100px;height: 100px;">
</div>
<div class="media-body">
	<div class="media-heading">
	<h4>Receiver Type</h4>
	</div>
	<p>
<form method="post" action="" enctype="multipart/form-data" onsubmit="return validateform()">
<div class="row">
	<div class="col-md-2" style="padding-top: 5px;">Receiver Type</div>
	<div class="col-md-10">
		<input type="text" name="receiver_type" id="receiver_type" class="form-control" value="<?php echo $rsedit['receiver_type']; ?>">
		<span id="errreceiver_type" class="errorclass"></span>
	</div>
</div>

<br>

<div class="row">
	<div class="col-md-2" style="padding-top: 5px;">Description</div>
	<div class="col-md-10">
		<input type="text" name="description" id="description" class="form-control" value="<?php echo $rsedit['description']; ?>">
		<span id="errdescription" class="errorclass"></span>
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
				if($val == $rsedit['status']){echo "<option value='$val' selected>$val</option>";} else {echo "<option value='$val'>$val</option>";}
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
</div>
</main>
</div>
</div>
</div>

<?php
include("footer.php");
?>

<script>
function validateform()
{
	var i = 0;	
	$('.errorclass').html('');
	
	if(document.getElementById("receiver_type").value=="")
	{
		document.getElementById("errreceiver_type").innerHTML = "Please enter receiver type...";
		i = 1;
	}
	if(document.getElementById("description").value=="")
	{
		document.getElementById("errdescription").innerHTML = "Please enter description...";
		i = 1;
	}
	
	if(document.getElementById("status").value=="")
	{
		document.getElementById("errstatus").innerHTML = "Please select status...";
		i = 1;
	}
	
	if(i == 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
</script>