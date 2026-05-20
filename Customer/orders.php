<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];

// Fetch all orders
$orders = [];
$stmt = $conn->prepare("SELECT s.*, u.name as driver_name, u.phone as driver_phone FROM shipments s LEFT JOIN users u ON s.driver_id = u.id WHERE s.customer_id = ? ORDER BY s.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | Customer</title>
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
    <?php include '../Includes/sidebar_customer.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>📋 My Orders</h2>
            <?php if(count($orders) > 0): ?>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Route</th>
                    <th>Goods</th>
                    <th>Driver</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td>#<?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['pickup_city']) ?> → <?= htmlspecialchars($order['drop_city']) ?></td>
                    <td><?= htmlspecialchars($order['goods_type']) ?> (<?= $order['weight'] ?>T)</td>
                    <td>
                        <?php if($order['driver_name']): ?>
                            <?= htmlspecialchars($order['driver_name']) ?> <br>
                            <small><?= htmlspecialchars($order['driver_phone']) ?></small>
                        <?php else: ?>
                            <span style="color:gray;">Pending assignment</span>
                        <?php endif; ?>
                    </td>
                    <td><?= format_date($order['created_at']) ?></td>
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
