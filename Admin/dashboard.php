<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');
$user_name = $_SESSION['name'];

// Platform Stats
$stats = [
    'total_users' => 0,
    'total_shipments' => 0,
    'active_shipments' => 0,
    'total_revenue' => 0
];

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM users WHERE role != 'admin'");
$stmt->execute();
$stats['total_users'] = $stmt->get_result()->fetch_assoc()['cnt'];

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM shipments");
$stmt->execute();
$stats['total_shipments'] = $stmt->get_result()->fetch_assoc()['cnt'];

$stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM shipments WHERE status IN ('active', 'in_transit')");
$stmt->execute();
$stats['active_shipments'] = $stmt->get_result()->fetch_assoc()['cnt'];

$stmt = $conn->prepare("SELECT SUM(amount) as total FROM payments WHERE status = 'settled'");
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$stats['total_revenue'] = $res['total'] ? $res['total'] : 0;

// Recent Users
$recent_users = [];
$stmt = $conn->prepare("SELECT id, name, role, status, created_at FROM users WHERE role != 'admin' ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $recent_users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .status.active { background: green; }
        .status.suspended { background: red; }
    </style>
</head>

<body>

<?php include '../Includes/header.php'; ?>

<div class="dashboard">

    <?php include '../Includes/sidebar_admin.php'; ?>

    <div class="content">

        <div class="topbar-dash">
            <div>
                <h1>Admin Dashboard 👑</h1>
                <p>Platform overview and management.</p>
            </div>
            <div class="profile-box">Admin</div>
        </div>

        <div class="cards">
            <div class="card">
                <h2>Total Users</h2>
                <p><?= $stats['total_users'] ?></p>
            </div>
            <div class="card">
                <h2>Total Shipments</h2>
                <p><?= $stats['total_shipments'] ?></p>
            </div>
            <div class="card">
                <h2>Active Shipments</h2>
                <p><?= $stats['active_shipments'] ?></p>
            </div>
            <div class="card">
                <h2>Platform Value (Settled)</h2>
                <p><?= format_currency($stats['total_revenue']) ?></p>
            </div>
        </div>

        <div class="table-section">
            <h2>Recent Registrations</h2>
            <table>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Status</th>
                </tr>
                <?php foreach($recent_users as $u): ?>
                <tr>
                    <td>#<?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= ucfirst($u['role']) ?></td>
                    <td><?= format_date($u['created_at']) ?></td>
                    <td><span class="status <?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </div>
</div>

</body>
</html>