<?php

session_start();

include '../Includes/db.php';

$id = $_GET['id'];

$status = $_GET['status'];

// generate OTP

$otp = rand(1000, 9999);

$query = "

UPDATE shipments

SET

shipment_status = '$status',
delivery_otp = '$otp'

WHERE id = '$id'

";

mysqli_query($conn, $query);

header("Location: dashboard.php");

?>