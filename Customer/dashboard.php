<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

// Fetch Stats
$stats = [
    'total_orders' => 0,
    'active_shipments' => 0,
    'completed' => 0,
    'total_payments' => 0
];

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats['total_orders'] = $stmt->get_result()->fetch_assoc()['cnt'];

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ? AND status IN ('pending', 'active', 'in_transit')");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats['active_shipments'] = $stmt->get_result()->fetch_assoc()['cnt'];

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM shipments WHERE customer_id = ? AND status = 'delivered'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stats['completed'] = $stmt->get_result()->fetch_assoc()['cnt'];

$stmt = $conn->prepare("SELECT SUM(amount) as total FROM payments p JOIN shipments s ON p.shipment_id = s.id WHERE s.customer_id = ? AND p.status = 'paid'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stats['total_payments'] = $res['total'] ? $res['total'] : 0;

// Fetch Recent Orders
$recent_orders = [];
$stmt = $conn->prepare("SELECT id, truck_type, drop_city, status FROM shipments WHERE customer_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $recent_orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../Css/style.Css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background: #f4f6f9; color: #333; }
        .dashboard { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1a1a2e; color: white; padding: 30px 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 35px; font-size: 28px; color: #E8460A; }
        .sidebar h3 { margin-bottom: 25px; font-size: 20px; text-align: center; }
        .sidebar ul { list-style: none; }
        .sidebar ul li { padding: 14px 15px; margin-bottom: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .sidebar ul li:hover { background: #E8460A; transform: translateX(5px); }
        .content { flex: 1; padding: 30px; }
        .topbar-dash { background: white; padding: 20px 25px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); margin-bottom: 30px; }
        .topbar-dash h1 { font-size: 28px; color: #1a1a2e; }
        .topbar-dash p { color: gray; margin-top: 5px; }
        .profile-box { background: #E8460A; color: white; padding: 12px 18px; border-radius: 8px; font-weight: bold; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-bottom: 35px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); transition: 0.3s; border-left: 5px solid #E8460A; }
        .card:hover { transform: translateY(-5px); }
        .card h2 { font-size: 18px; margin-bottom: 15px; color: #444; }
        .card p { font-size: 32px; font-weight: bold; color: #1a1a2e; }
        .table-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
        .table-section h2 { margin-bottom: 20px; color: #1a1a2e; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1a1a2e; color: white; padding: 14px; text-align: left; }
        table td { padding: 14px; border-bottom: 1px solid #ddd; }
        table tr:hover { background: #f5f5f5; }
        .status { padding: 6px 12px; border-radius: 20px; color: white; font-size: 13px; font-weight: bold; }
        .status.active, .status.in_transit { background: #E8460A; }
        .status.pending { background: orange; }
        .status.delivered { background: green; }
        .status.cancelled { background: red; }
        @media (max-width: 768px) { .dashboard { flex-direction: column; } .sidebar { width: 100%; } .topbar-dash { flex-direction: column; align-items: flex-start; gap: 15px; } }
    </style>
</head>

<body>

<?php include '../Includes/header.php'; ?>

<div class="dashboard">

    <?php include '../Includes/sidebar_customer.php'; ?>

    <div class="content">

        <div class="topbar-dash">
            <div>
                <h1>Welcome, <?= htmlspecialchars($user_name) ?> 👋</h1>
                <p>Manage your shipments and orders easily.</p>
            </div>
            <div class="profile-box">Customer ID : C<?= 1000 + $user_id ?></div>
        </div>

        <div class="cards">
            <div class="card">
                <h2>Total Orders</h2>
                <p><?= $stats['total_orders'] ?></p>
            </div>
            <div class="card">
                <h2>Active Shipments</h2>
                <p><?= $stats['active_shipments'] ?></p>
            </div>
            <div class="card">
                <h2>Completed Deliveries</h2>
                <p><?= $stats['completed'] ?></p>
            </div>
            <div class="card">
                <h2>Total Payments</h2>
                <p><?= format_currency($stats['total_payments']) ?></p>
            </div>
        </div>

        <div class="table-section">
            <h2>Recent Orders</h2>

            <?php if(count($recent_orders) > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Truck Type</th>
                    <th>Destination</th>
                    <th>Status</th>
                </tr>
                <?php foreach($recent_orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['truck_type']) ?></td>
                    <td><?= htmlspecialchars($order['drop_city']) ?></td>
                    <td><span class="status <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>You haven't placed any orders yet. <a href="book_load.php">Book your first load!</a></p>
            <?php endif; ?>
        </div>

    </div>
</div>

</body>
</html>