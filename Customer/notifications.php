<?php

session_start();

include '../Includes/db.php';

$customer_id = $_SESSION['user_id'];

$query = "

SELECT *

FROM shipments

WHERE customer_id = '$customer_id'

AND shipment_status = 'active'

ORDER BY id DESC

";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>

<html>

<head>

<title>Notifications</title>

<style>

body {

font-family:Arial;
background:#f4f6f9;
padding:40px;

}

.box {

background:white;
padding:25px;
border-radius:12px;
margin-bottom:20px;

}

h1 {

margin-bottom:30px;

}

.otp {

font-size:28px;
font-weight:bold;
color:#ff6b35;

}

</style>

</head>

<body>

<h1>Notifications 🔔</h1>

<?php while($shipment = mysqli_fetch_assoc($result)) { ?>

<div class="box">

<h3>

Shipment #<?php echo $shipment['id']; ?>

is now ACTIVE

</h3>

<p>

Your delivery OTP is:

</p>

<div class="otp">

<?php echo $shipment['delivery_otp']; ?>

</div>

<p>

Share this OTP only after receiving your goods.

</p>

</div>

<?php } ?>

</body>

</html>