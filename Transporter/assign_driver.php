<?php

session_start();

include '../Includes/db.php';

$shipment_id = $_POST['shipment_id'];

$driver_id = $_POST['driver_id'];

$query = "

UPDATE shipments

SET

driver_id = '$driver_id',
shipment_status = 'accepted'

WHERE id = '$shipment_id'

";

mysqli_query($conn, $query);

header("Location: dashboard.php");

?>