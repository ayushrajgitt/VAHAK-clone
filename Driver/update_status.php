<?php

session_start();

include '../Includes/db.php';

$id = $_GET['id'];

$status = $_GET['status'];

$query = "

UPDATE shipments

SET shipment_status = '$status'

WHERE id = '$id'

";

mysqli_query($conn, $query);

header("Location: dashboard.php");

?>