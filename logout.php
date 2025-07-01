<?php
error_reporting(0);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_destroy();
echo "<script>window.location='index.php';</script>";
?>