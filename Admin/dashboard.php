<?php

session_start();

// check if user logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();

}

// check correct role
if ($_SESSION['role'] != 'admin') {

    header("Location: ../login.php");
    exit();

}

include '../Includes/db.php';

// total users

$userQuery = "

SELECT COUNT(*) AS total_users

FROM users

";

$userResult = mysqli_query($conn, $userQuery);

$userData = mysqli_fetch_assoc($userResult);

$totalUsers = $userData['total_users'];

// total shipments

$shipmentQuery = "

SELECT COUNT(*) AS total_shipments

FROM shipments

";

$shipmentResult = mysqli_query($conn, $shipmentQuery);

$shipmentData = mysqli_fetch_assoc($shipmentResult);

$totalShipments = $shipmentData['total_shipments'];

// active deliveries

$activeQuery = "

SELECT COUNT(*) AS active_shipments

FROM shipments

WHERE shipment_status = 'active'

";

$activeResult = mysqli_query($conn, $activeQuery);

$activeData = mysqli_fetch_assoc($activeResult);

$activeShipments = $activeData['active_shipments'];

// completed deliveries

$completedQuery = "

SELECT COUNT(*) AS completed_shipments

FROM shipments

WHERE shipment_status = 'completed'

";

$completedResult = mysqli_query($conn, $completedQuery);

$completedData = mysqli_fetch_assoc($completedResult);

$completedShipments = $completedData['completed_shipments'];

// recent shipment activity

$recentQuery = "

SELECT
shipments.id,
shipments.shipment_status,
users.full_name

FROM shipments

LEFT JOIN users
ON shipments.customer_id = users.id

ORDER BY shipments.id DESC

LIMIT 5

";

$recentResult = mysqli_query($conn, $recentQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

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

        /* main layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* sidebar */
        .sidebar {
            width: 260px;
            background: #1a1a2e;
            color: white;
            padding: 30px 20px;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 35px;
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

        /* content area */
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

        .admin-box {
            background: #ff6b35;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
        }

        /* stat cards */
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

        /* shipment table */
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

        /* status badges */
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
        .accepted { background: #ff6b35;}

        /* responsive */
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

    <!-- sidebar navigation -->
    <div class="sidebar">
        <h2>VAHAK</h2>
        <h3>Admin Panel</h3>
        <ul>
            <li>📊 Dashboard</li>
            <li>👥 Manage Customers</li>
            <li>🚚 Manage Drivers</li>
            <li>📦 Manage Shipments</li>
            <li>💳 Payments</li>
            <li>📑 Reports</li>
            <li>📈 Analytics</li>
            <li>⚙️ Settings</li>
        </ul>
    </div>

    <!-- main content -->
    <div class="content">

        <!-- header bar -->
        <div class="topbar">
            <div>
                <h1>Administrator Control Panel 👨‍💼</h1>
                <p>Manage customers, drivers and shipment operations.</p>
            </div>
            <div class="admin-box">Admin ID : A1001</div>
        </div>

        <!-- overview cards -->
        <div class="cards">
            <div class="card">
                <h2>Total Users</h2>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="card">
                <h2>Total Shipments</h2>
                <p><?php echo $totalShipments; ?></p>
            </div>
            <div class="card">
                <h2>Active Deliveries</h2>
                <p><?php echo $activeShipments; ?></p>
            </div>
            <div class="card">
                <h2>Completed Deliveries</h2>
                <p><?php echo $completedShipments; ?></p>
            </div>
        </div>

        <!-- recent shipments table -->
        <div class="table-section">
            <h2>Recent Shipment Activity</h2>

            <table>
                <tr>
                    <th>Shipment ID</th>
                    <th>Customer</th>
                    <th>Driver</th>
                    <th>Status</th>
                </tr>

                <?php while($shipment = mysqli_fetch_assoc($recentResult)) { ?>

                <tr>

                <td>

                #<?php echo $shipment['id']; ?>

                </td>

                <td>

                <?php echo $shipment['full_name']; ?>

                </td>

                <td>

                Driver Assigned

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

    </div>
</div>

</body>
</html>