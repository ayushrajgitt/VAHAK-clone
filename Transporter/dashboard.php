<?php

session_start();

// check if user logged in
if (!isset($_SESSION['user_id'])) {

    header("Location: ../login.php");
    exit();

}

// check correct role
if ($_SESSION['role'] != 'transporter') {

    header("Location: ../login.php");
    exit();

}

?>

<?php

include '../Includes/db.php';

// fetch drivers

$driverQuery = "

SELECT * FROM users
WHERE role = 'driver'

";

$driverResult = mysqli_query($conn, $driverQuery);

// fetch pending shipments

$query = "

SELECT
shipments.*,
users.full_name,
trucks.truck_name

FROM shipments

JOIN users
ON shipments.customer_id = users.id

JOIN trucks
ON shipments.truck_id = trucks.id

WHERE shipment_status = 'pending'

ORDER BY shipments.id DESC

";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transporter Dashboard</title>

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

        /* overall layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* left sidebar */
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
            margin-bottom: 25px;
            font-size: 20px;
            text-align: center;
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

        /* right content */
        .content {
            flex: 1;
            padding: 30px;
        }

        /* top bar */
        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
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

        .profile-box {
            background: #ff6b35;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
        }

        /* summary cards */
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
            transition: 0.3s;
            border-left: 5px solid #ff6b35;
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
            font-size: 32px;
            font-weight: bold;
            color: #1a1a2e;
        }

        /* orders table */
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

    <!-- sidebar -->
    <div class="sidebar">
        <h2>VAHAK</h2>
        <h3>Transporter Panel</h3>
        <ul>
            <li>🏠 Dashboard</li>
            <li>🚚 Manage Fleet</li>
            <li>👨‍✈️ Drivers</li>
            <li>📦 Active Shipments</li>
            <li>📍 Route Tracking</li>
            <li>💰 Revenue</li>
            <li>📊 Reports</li>
            <li>👤 Company Profile</li>
        </ul>
    </div>

    <!-- main content -->
    <div class="content">

        <!-- welcome bar -->
        <div class="topbar">
            <div>
                <h1>Welcome Transporter 👋</h1>
                <p>Manage vehicles, drivers and shipment operations.</p>
            </div>
            <div class="profile-box">Transporter ID :
                T<?php echo 1000 + $_SESSION['user_id']; ?></div>
            </div>

        <!-- stats cards -->
        <div class="cards">
            <div class="card">
                <h2>Total Vehicles</h2>
                <p>25</p>
            </div>
            <div class="card">
                <h2>Active Drivers</h2>
                <p>5</p>
            </div>
            <div class="card">
                <h2>Running Shipments</h2>
                <p>

            <?php

            $countQuery = "

            SELECT COUNT(*) AS total
            FROM shipments
            WHERE shipment_status = 'pending'

            ";

            $countResult = mysqli_query($conn, $countQuery);

            $countData = mysqli_fetch_assoc($countResult);

            echo $countData['total'];

            ?>

            </p>
            </div>
            <div class="card">
                <h2>Monthly Revenue</h2>
                <p>₹45K</p>
            </div>
        </div>

        <!-- recent orders -->
        <div class="table-section">
            <h2>Active Fleet Operations</h2>

            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Truck</th>
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

                    <span class="status pending">

                    <?php echo ucfirst($shipment['shipment_status']); ?>

                    </span>

                    </td>

                    <td>

                    <form action="assign_driver.php" method="POST">

                    <input
                    type="hidden"
                    name="shipment_id"
                    value="<?php echo $shipment['id']; ?>"
                    >

                    <select name="driver_id" required>

                    <option value="">
                    Select Driver
                    </option>

                    <?php

                    mysqli_data_seek($driverResult, 0);

                    while($driver = mysqli_fetch_assoc($driverResult)) {

                    ?>

                    <option value="<?php echo $driver['id']; ?>">

                    <?php echo $driver['full_name']; ?>

                    </option>

                    <?php } ?>

                    </select>

                    <button type="submit">

                    Assign

                    </button>

                    </form>

                    </td>

                    </tr>

                <?php } ?>
            </table>
        </div>

    </div>
</div>

</body>
</html>