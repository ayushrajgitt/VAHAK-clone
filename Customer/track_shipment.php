<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();

}

include '../Includes/db.php';

$customer_id = $_SESSION['user_id'];

$query = "

SELECT

shipments.*,
trucks.truck_name,
users.full_name AS driver_name

FROM shipments

LEFT JOIN trucks
ON shipments.truck_id = trucks.id

LEFT JOIN users
ON shipments.driver_id = users.id

WHERE customer_id = '$customer_id'

ORDER BY shipments.id DESC

";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Track Shipments</title>

<style>

body {

font-family: Arial;
background:#f4f6f9;
padding:40px;

}

.container {

background:white;
padding:30px;
border-radius:12px;

}

h1 {

margin-bottom:25px;
color:#1a1a2e;

}

table {

width:100%;
border-collapse:collapse;

}

th {

background:#1a1a2e;
color:white;
padding:14px;

}

td {

padding:14px;
border-bottom:1px solid #ddd;

}

.status {

padding:6px 12px;
border-radius:20px;
color:white;
font-size:13px;
font-weight:bold;

}

.pending {

background:orange;

}

.accepted {

background:#007bff;

}

.active {

background:green;

}

.completed {

background:#6f42c1;

}

</style>

</head>

<body>

<div class="container">

<h1>Track Your Shipments 🚚</h1>

<table>

<tr>

<th>ID</th>
<th>Truck</th>
<th>Driver</th>
<th>Source</th>
<th>Destination</th>
<th>Status</th>

</tr>

<?php while($shipment = mysqli_fetch_assoc($result)) { ?>

<tr>

<td>
#<?php echo $shipment['id']; ?>
</td>

<td>
<?php echo $shipment['truck_name']; ?>
</td>

<td>

<?php

if($shipment['driver_name']) {

    echo $shipment['driver_name'];

} else {

    echo "Not Assigned";

}

?>

</td>

<td>
<?php echo $shipment['source']; ?>
</td>

<td>
<?php echo $shipment['destination']; ?>
</td>

<td>

<span class="status <?php echo $shipment['shipment_status']; ?>">

<?php echo ucfirst($shipment['shipment_status']); ?>

</span>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>

</html>