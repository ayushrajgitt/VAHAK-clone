<?php

session_start();

include '../Includes/db.php';

$id = $_GET['id'];

$query = "

UPDATE shipments

SET
shipment_status = 'accepted',
transporter_id = '" . $_SESSION['user_id'] . "'

WHERE id = '$id'

";

mysqli_query($conn, $query);

header("Location: dashboard.php");

?>