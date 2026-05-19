<?php

session_start();

// check if user logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();

}

// check correct role
if ($_SESSION['role'] != 'driver') {

    header("Location: ../login.php");
    exit();

}


include '../Includes/db.php';

$driver_id = $_SESSION['user_id'];

// fetch shipments assigned to this driver

$query = "

SELECT shipments.*, trucks.truck_name

FROM shipments

LEFT JOIN trucks
ON shipments.truck_id = trucks.id

WHERE driver_id = '$driver_id'

";

$result = mysqli_query($conn, $query);

// total trips

$totalTripsQuery = "

SELECT COUNT(*) AS total_trips

FROM shipments

WHERE driver_id = '$driver_id'

";

$totalTripsResult = mysqli_query($conn, $totalTripsQuery);

$totalTrips = mysqli_fetch_assoc($totalTripsResult)['total_trips'];

// active deliveries

$activeDeliveriesQuery = "

SELECT COUNT(*) AS active_deliveries

FROM shipments

WHERE driver_id = '$driver_id'

AND shipment_status = 'active'

";

$activeDeliveriesResult = mysqli_query($conn, $activeDeliveriesQuery);

$activeDeliveries = mysqli_fetch_assoc($activeDeliveriesResult)['active_deliveries'];

// completed deliveries

$completedDeliveriesQuery = "

SELECT COUNT(*) AS completed_deliveries

FROM shipments

WHERE driver_id = '$driver_id'

AND shipment_status = 'completed'

";

$completedDeliveriesResult = mysqli_query($conn, $completedDeliveriesQuery);

$completedDeliveries = mysqli_fetch_assoc($completedDeliveriesResult)['completed_deliveries'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        /* page layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* sidebar */
        .sidebar {
            width: 250px;
            background: #1a1a2e;
            color: white;
            padding: 30px 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 28px;
            color: #ff6b35;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 22px;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            padding: 14px 15px;
            margin-bottom: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 15px;
        }

        .sidebar ul li:hover {
            background: #ff6b35;
            transform: translateX(5px);
        }

        /* content */
        .content {
            flex: 1;
            padding: 30px;
        }

        /* top bar */
        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar h1 {
            font-size: 28px;
            color: #1a1a2e;
        }

        .topbar p {
            color: gray;
            margin-top: 5px;
        }

        .driver-box {
            background: #ff6b35;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
        }

        /* stats cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #ff6b35;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #444;
        }

        .card p {
            font-size: 30px;
            font-weight: bold;
            color: #1a1a2e;
        }

        /* trips table */
        .table-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table-section h2 {
            margin-bottom: 20px;
            color: #1a1a2e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #1a1a2e;
            color: white;
            padding: 14px;
            text-align: left;
        }

        table td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f5f5f5;
        }

        /* status tags */
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-size: 13px;
            font-weight: bold;
        }

        .active    { background: green; }
        .pending   { background: orange; }
        .completed { background: #007bff; }

        /* mobile */
        @media (max-width: 768px) {
            .dashboard { flex-direction: column; }
            .sidebar { width: 100%; }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

<?php include '../includes/header.php'; ?>

<div class="dashboard">

    <!-- sidebar -->
    <div class="sidebar">
        <h2>VAHAK</h2>
        <h3>Driver Panel</h3>
        <ul>
            <li>🏠 Home</li>
            <li>📦 Available Loads</li>
            <li>🚚 My Trips</li>
            <li>💰 Earnings</li>
            <li>🚛 Vehicle Details</li>
            <li>🗺️ Route Information</li>
            <li>⭐ Ratings</li>
            <li>👤 Profile</li>
        </ul>
    </div>

    <!-- main area -->
    <div class="content">

        <!-- welcome topbar -->
        <div class="topbar">
            <div>
                <h1>Welcome Driver 👋</h1>
                <p>Manage your trips, earnings and deliveries easily.</p>
            </div>
            <div class="driver-box">Driver ID :
                D<?php echo 1000 + $_SESSION['user_id']; ?></div>
            </div>

        <!-- quick stats -->
        <div class="cards">
            <div class="card">
                <h2>Total Trips</h2>
                <p><?php echo $totalTrips; ?></p>
            </div>
            <div class="card">
                <h2>Active Deliveries</h2>
                <p><?php echo $activeDeliveries; ?></p>
            </div>
            <div class="card">
                <h2>Completed Deliveries</h2>
                <p><?php echo $completedDeliveries; ?></p>
            </div>
            <div class="card">
                <h2>Ratings</h2>
                <p>4.8 ★</p>
            </div>
        </div>

        <!-- recent trips -->
        <div class="table-section">
            <h2>Recent Trips</h2>

            <table>

            <tr>

            <th>Trip ID</th>
            <th>Vehicle</th>
            <th>Destination</th>
            <th>Status</th>
            <th>Action</th>

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
            <?php echo $shipment['destination']; ?>
            </td>

            <td>

            <span class="status <?php echo $shipment['shipment_status']; ?>">

            <?php echo ucfirst($shipment['shipment_status']); ?>

            </span>

            </td>

            <td>

            <?php if($shipment['shipment_status'] == 'accepted') { ?>

            <a
            href="update_status.php?id=<?php echo $shipment['id']; ?>&status=active"
            style="
            background:orange;
            color:white;
            padding:8px 14px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
            "
            >

            Start Trip

            </a>

            <?php } else if($shipment['shipment_status'] == 'active') { ?>

            <form action="verify_otp.php" method="POST">

            <input
            type="hidden"
            name="shipment_id"
            value="<?php echo $shipment['id']; ?>"
            >

            <input
            type="text"
            name="entered_otp"
            placeholder="Enter OTP"
            required
            style="
            padding:8px;
            border-radius:6px;
            border:1px solid #ccc;
            "
            >

            <button type="submit">

            Verify OTP

            </button>

            </form>

            <?php } else { ?>

            <span style="color:gray;font-weight:bold;">

            Completed

            </span>

            <?php } ?>

            </td>

            </tr>

            <?php } ?>

            </table>
        </div>

    </div>
</div>

</body>
</html>