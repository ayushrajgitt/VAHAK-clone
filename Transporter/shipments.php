<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];

// Fetch Transporter Shipments
$shipments = [];
$stmt = $conn->prepare("SELECT s.*, u1.name as customer_name, u2.name as driver_name, v.number_plate FROM shipments s JOIN users u1 ON s.customer_id = u1.id LEFT JOIN users u2 ON s.driver_id = u2.id LEFT JOIN vehicles v ON s.vehicle_id = v.id WHERE s.transporter_id = ? ORDER BY s.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $shipments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Active Shipments | Transporter</title>
    <link rel="stylesheet" href="../Css/style.Css">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f4f6f9; color: #333; margin:0;}
        .dashboard { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1a1a2e; color: white; padding: 30px 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 35px; font-size: 28px; color: #E8460A; }
        .sidebar ul { list-style: none; padding:0;}
        .sidebar ul li { padding: 14px 15px; margin-bottom: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .sidebar ul li:hover { background: #E8460A; }
        .content { flex: 1; padding: 30px; }
        .table-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
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
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>🚚 Fleet Shipments</h2>
            <?php if(count($shipments) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Route</th>
                    <th>Customer</th>
                    <th>Driver & Vehicle</th>
                    <th>Status</th>
                </tr>
                <?php foreach($shipments as $shipment): ?>
                <tr>
                    <td>#<?= $shipment['id'] ?></td>
                    <td><?= htmlspecialchars($shipment['pickup_city']) ?> → <?= htmlspecialchars($shipment['drop_city']) ?></td>
                    <td><?= htmlspecialchars($shipment['customer_name']) ?></td>
                    <td>
                        <?= $shipment['driver_name'] ? htmlspecialchars($shipment['driver_name']) : '<span style="color:red">Unassigned</span>' ?><br>
                        <small><?= $shipment['number_plate'] ? htmlspecialchars($shipment['number_plate']) : '' ?></small>
                    </td>
                    <td><span class="status <?= $shipment['status'] ?>"><?= ucfirst($shipment['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>No shipments managed by your fleet yet. <a href="available_loads.php">Find loads!</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
