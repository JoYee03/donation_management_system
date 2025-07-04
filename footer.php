<!-- Donor Login Modal Starts here -->
<div class="modal fade" id="DonorLoginModal" role="dialog">
<form method="post" action="" onsubmit="return validateform1()">
	<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Donor Login Window</h4>
			</div>
			<div class="modal-body"  style="padding-left: 55px;padding-right: 55px;">
			  
<div class="row">
Email ID : <input type="email" name="donoremail_id" id="donoremail_id" class="form-control">
<span id="errdonoremail_id" class="errorclass"></span>
</div>	
<br>
			  
<div class="row">
Password : <input type="password" name="donorpassword" id="donorpassword" class="form-control">
<span id="errdonorpassword" class="errorclass"></span>
</div>			  
			  
			</div>
			<div class="modal-footer">
			  <button type="submit" name="btndonorlogin" id="btndonorlogin" class="btn btn-default" >Click to Login</button>
			</div>
		  </div>
	</div>
	</form>
</div>
<!-- Donor Login Modal Ends here -->


<!-- DonorRegisterModal Starts here -->
<div class="modal fade" id="DonorRegisterModal" role="dialog" >
<form method="post" action="" onsubmit="return validateform2()">
	<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Donor Register Window</h4>
			</div>
			
			<div class="modal-body" style="padding-left: 55px;padding-right: 55px;">
<div class="row">
Name : <input type="text" name="name" id="name" class="form-control">
<span id="errname" class="errorclass"></span>
</div>	
<br>
			 
						  
<div class="row">
Email ID: <input type="email" name="donoremailid" id="donoremailid" class="form-control">
<span id="errdonoremailid" class="errorclass"></span>
</div>	
<br>

<div class="row">
Contact no: <input type="number" name="contactno" id="contactno" class="form-control">
<span id="errcontactno" class="errorclass"></span>
</div>		
<br>
 
<div class="row">
Password : <input type="password" name="donornpassword" id="donornpassword" class="form-control">
<span id="errdonornpassword" class="errorclass"></span>
</div>		
<br>	  

<div class="row">
Confirm password: <input type="password" name="donorcpassword" id="donorcpassword" class="form-control">
<span id="errdonorcpassword" class="errorclass"></span>
</div>	
<br>	
			</div>
			
			  
			<div class="modal-footer">
<button type="submit" name="btndonorregister" id="btndonorregister" class="btn btn-info" >Click to Register</button>
			</div>
		  </div>
	</div>
	</form>
</div>
<!-- DonorRegisterModal Ends here -->

<!-- Staff Login Modal Starts here -->
<div class="modal fade" id="StaffLoginModal" role="dialog">
<form method="post" action="" onsubmit="return validateform3()">
	<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Staff Login Window</h4>
			</div>
			<div class="modal-body"  style="padding-left: 55px;padding-right: 55px;">
			  
<div class="row">
Login ID : <input type="text" name="staffloginid" id="staffloginid" class="form-control">
<span id="errstaffloginid" class="errorclass"></span>
</div>	
<br>
			  
<div class="row">
Password : <input type="password" name="staffpassword" id="staffpassword" class="form-control">
<span id="errstaffpassword" class="errorclass"></span>
</div>			  
			  
			</div>
			<div class="modal-footer">
			  <button type="submit" name="btnstafflogin" id="btnstafflogin" class="btn btn-default" >Click to Login</button>
			</div>
		  </div>
	</div>
	</form>
</div>
<!-- Staff Login Modal Ends here -->

<!-- Receiver Login Modal -->
<div class="modal fade" id="ReceiverLoginModal" role="dialog">
<form method="post" action="" onsubmit="return validateform4()">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Receiver Login Window</h4>
            </div>
            <div class="modal-body" style="padding-left: 55px;padding-right: 55px;">
                <div class="row">
                    Email ID : <input type="email" name="receiveremail_id" id="receiveremail_id" class="form-control">
                    <span id="errreceiveremail_id" class="errorclass"></span>
                </div>
                <br>
                <div class="row">
                    Password : <input type="password" name="receiverpassword" id="receiverpassword" class="form-control">
                    <span id="errreceiverpassword" class="errorclass"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="btnreceiverlogin" id="btnreceiverlogin" class="btn btn-default">Click to Login</button>
            </div>
        </div>
    </div>
</form>
</div>
<!-- Receiver Login Modal Ends here -->

<footer id="footer" class="section">

<div class="container">

<div class="row">

<div class="col-md-4">
<div class="footer">
<div class="footer-logo">
<a class="logo" href="#"><img src="img/logo.png" alt=""></a>
</div>
<p>DMS is a non-profit initiative located at Block B, Level 3, Cyberjaya Square, Persiaran Apec, 63000 Cyberjaya, Selangor, Malaysia. It is dedicated to supporting underprivileged communities, including the elderly, economically disadvantaged individuals and people with mental health challenges. Through its Donation Management System, DMS efficiently manages the collection, organization and distribution of donations to ensure meaningful and timely assistance for those in need.</p>
</div>
</div>


<div class="col-md-4">
</div>


<div class="col-md-4">
<div class="footer">
<h3 class="footer-title">Newsletter</h3>
<p>

<ul class="footer-contact">
<li><i class="fa fa-map-marker"></i> Donation Management System,<br> &nbsp;&nbsp;&nbsp; Block B, Level 3, Cyberjaya Square <br> &nbsp;&nbsp;&nbsp; Persiaran Apec, 63000 Cyberjaya, <br> &nbsp;&nbsp;&nbsp; Selangor, Malaysia </li>
<li><i class="fa fa-phone"></i> 080-2287236</li>
<li><i class="fa fa-envelope"></i> <a href="#"><span class="__cf_email__" data-cfemail="8fcce7eefde6fbf6cfeae2eee6e3a1ece0e2">dms@gmail.com</span></a></li>
</ul>

</p>
<hr>
<ul class="footer-social">
<li><a href="#"><i class="fa fa-facebook"></i></a></li>
<li><a href="#"><i class="fa fa-twitter"></i></a></li>
<li><a href="#"><i class="fa fa-google-plus"></i></a></li>
<li><a href="#"><i class="fa fa-instagram"></i></a></li>
<li><a href="#"><i class="fa fa-pinterest"></i></a></li>
</ul>
</div>
</div>

</div>


<div id="footer-bottom" class="row">
<div class="col-md-6 col-md-push-6">
<?php
if(!isset($_SESSION['staff_id']) && !isset($_SESSION['donor_id']) )
{
?>
	<ul class="footer-nav">
	<li><a href="" onclick="return false"  data-toggle="modal" data-target="#StaffLoginModal" >Staff Login</a></li>
	</ul>
<?php
}
?>
</div>
<div class="col-md-6 col-md-pull-6">
<div class="footer-copyright">
<span>
Copyright &copy;<script data-cfasync="false" src="scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script type="7909e31ca7813c4694085191-text/javascript">document.write(new Date().getFullYear());</script>  
</span>
</div>
</div>
</div>

</div>

</footer>


<script src="js/jquery.min.js" type="7909e31ca7813c4694085191-text/javascript"></script>
<script src="js/bootstrap.min.js" type="7909e31ca7813c4694085191-text/javascript"></script>
<script src="js/owl.carousel.min.js" type="7909e31ca7813c4694085191-text/javascript"></script>
<script src="js/jquery.stellar.min.js" type="7909e31ca7813c4694085191-text/javascript"></script>
<script src="js/main.js" type="7909e31ca7813c4694085191-text/javascript"></script>

<script src="scripts/7089c43e/cloudflare-static/rocket-loader.min.js" data-cf-settings="7909e31ca7813c4694085191-|49" defer=""></script>

<script src="js/jquery-3.3.1.js" ></script>
<script src="js/jquery.dataTables.min.js" ></script>
<script>
$(document).ready( function () {
    $('#datatable').DataTable();
} );
</script>
</body>

</html>

<script>
function validateform1()
{
	var i = 0;	
	$('.errorclass').html('');
	
	if(document.getElementById("donoremail_id").value=="")
	{
		document.getElementById("errdonoremail_id").innerHTML = "Kindly enter the Donor Login ID...";
		i = 1;
	}
	if(document.getElementById("donorpassword").value=="")
	{
		document.getElementById("errdonorpassword").innerHTML = "Kindly enter the Donor Password......";
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
<script>
function validateform2()
{
	var i = 0;	
	$('.errorclass').html('');
	
	if(document.getElementById("name").value=="")
	{
		document.getElementById("errname").innerHTML = "Kindly enter the name ...";
		i = 1;
	}
	if(document.getElementById("donoremailid").value=="")
	{
		document.getElementById("errdonoremailid").innerHTML = "Kindly enter the donor Email ID..";
		i = 1;
	}
	if(document.getElementById("contactno").value=="")
	{
		document.getElementById("errcontactno").innerHTML = "Kindly enter Contact no...";
		i = 1;
	}
	if(document.getElementById("donornpassword").value=="")
	{
		document.getElementById("errdonornpassword").innerHTML = "Kindly enter donorn password....";
		i = 1;
	}
	if(document.getElementById("donorcpassword").value=="")
	{
		document.getElementById("errdonorcpassword").innerHTML = "Kindly enter confirm password....";
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
<script>
function validateform3()
{
	var i = 0;	
	$('.errorclass').html('');
	
	if(document.getElementById("staffloginid").value=="")
	{
		document.getElementById("errstaffloginid").innerHTML = "Kindly enter the staff login id ...";
		i = 1;
	}
	if(document.getElementById("staffpassword").value=="")
	{
		document.getElementById("errstaffpassword").innerHTML = "Kindly enter the staff password ...";
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
<script>
function validateform4() {
    var i = 0;  
    $('.errorclass').html('');
    
    if(document.getElementById("receiveremail_id").value=="") {
        document.getElementById("errreceiveremail_id").innerHTML = "Please enter your email...";
        i = 1;
    }
    if(document.getElementById("receiverpassword").value=="") {
        document.getElementById("errreceiverpassword").innerHTML = "Please enter your password...";
        i = 1;
    }
    
    return i === 0;
}
</script>