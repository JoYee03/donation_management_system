<?php
include("databaseconnection.php");
header('Content-Type: application/json');

$donors = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM donor WHERE status='Active'"))[0];
$donations = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM item_donation WHERE status='Active'"))[0];
$completed = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM item_donation WHERE status='Completed'"))[0];
$recipients = mysqli_fetch_array(mysqli_query($con, "SELECT COUNT(*) FROM receiver WHERE status='Active'"))[0];

echo json_encode([
    'donors' => $donors,
    'donations' => $donations,
    'completed' => $completed,
    'recipients' => $recipients
]);
?>