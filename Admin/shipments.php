<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_shipment'])) {
    $shipment_id = (int) $_POST['shipment_id'];
    
    // Only cancel if not delivered
    $check = $conn->prepare("SELECT status FROM shipments WHERE id = ?");
    $check->bind_param("i", $shipment_id);
    $check->execute();
    $status = $check->get_result()->fetch_assoc()['status'];
    
    if ($status !== 'delivered') {
        $update = $conn->prepare("UPDATE shipments SET status = 'cancelled' WHERE id = ?");
        $update->bind_param("i", $shipment_id);
        if ($update->execute()) {
            $success = "Shipment #$shipment_id has been cancelled.";
        } else {
            $error = "Failed to cancel shipment.";
        }
    } else {
        $error = "Cannot cancel a delivered shipment.";
    }
}

// Fetch All Shipments
$shipments = [];
$stmt = $conn->prepare("SELECT s.*, u1.name as customer_name, u2.name as driver_name, u3.name as transporter_name FROM shipments s JOIN users u1 ON s.customer_id = u1.id LEFT JOIN users u2 ON s.driver_id = u2.id LEFT JOIN users u3 ON s.transporter_id = u3.id ORDER BY s.created_at DESC");
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
    <title>All Shipments | Admin</title>
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
        table th { background: #1a1a2e; color: white; padding: 12px; text-align: left; font-size: 14px;}
        table td { padding: 12px; border-bottom: 1px solid #ddd; font-size: 14px;}
        table tr:hover { background: #f5f5f5; }
        .status { padding: 4px 8px; border-radius: 12px; color: white; font-size: 12px; font-weight: bold; }
        .status.active, .status.in_transit { background: #E8460A; }
        .status.pending { background: orange; }
        .status.delivered { background: green; }
        .status.cancelled { background: red; }
        .btn-cancel { padding: 4px 8px; border: none; border-radius: 4px; background: #dc3545; color: white; cursor: pointer; font-size: 12px;}
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_admin.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>📦 Platform Shipments</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>
            
            <?php if(count($shipments) > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Route</th>
                    <th>Customer</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach($shipments as $shipment): ?>
                <tr>
                    <td>#<?= $shipment['id'] ?></td>
                    <td><?= htmlspecialchars($shipment['pickup_city']) ?> → <?= htmlspecialchars($shipment['drop_city']) ?></td>
                    <td><?= htmlspecialchars($shipment['customer_name']) ?></td>
                    <td>
                        <?php if($shipment['transporter_name']): ?>
                            <small>T: <?= htmlspecialchars($shipment['transporter_name']) ?></small><br>
                        <?php endif; ?>
                        <?php if($shipment['driver_name']): ?>
                            <small>D: <?= htmlspecialchars($shipment['driver_name']) ?></small>
                        <?php else: ?>
                            <span style="color:gray; font-size:12px;">Unassigned</span>
                        <?php endif; ?>
                    </td>
                    <td><?= format_date($shipment['created_at']) ?></td>
                    <td><span class="status <?= $shipment['status'] ?>"><?= ucfirst($shipment['status']) ?></span></td>
                    <td>
                        <?php if(!in_array($shipment['status'], ['delivered', 'cancelled'])): ?>
                        <form method="POST">
                            <input type="hidden" name="shipment_id" value="<?= $shipment['id'] ?>">
                            <button type="submit" name="cancel_shipment" class="btn-cancel" onclick="return confirm('Are you sure you want to cancel this shipment?');">Cancel</button>
                        </form>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>No shipments recorded.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
