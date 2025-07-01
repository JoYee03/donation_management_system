<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php
// Create connection
$con=mysqli_connect("localhost","root","","donation_management");
echo mysqli_connect_error();
?>