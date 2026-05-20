<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');

// Fetch All Ratings
$ratings = [];
$stmt = $conn->prepare("SELECT r.*, c.name as customer_name, d.name as driver_name, s.pickup_city, s.drop_city FROM ratings r JOIN users c ON r.customer_id = c.id JOIN users d ON r.driver_id = d.id JOIN shipments s ON r.shipment_id = s.id ORDER BY r.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $ratings[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ratings Oversight | Admin</title>
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
        .stars { color: #ffc107; font-size: 16px;}
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_admin.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>⭐ Platform Ratings</h2>
            <?php if(count($ratings) > 0): ?>
            <table>
                <tr>
                    <th>Trip ID</th>
                    <th>Customer</th>
                    <th>Driver</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Date</th>
                </tr>
                <?php foreach($ratings as $r): ?>
                <tr>
                    <td>#<?= $r['shipment_id'] ?><br><small><?= htmlspecialchars($r['pickup_city']) ?> → <?= htmlspecialchars($r['drop_city']) ?></small></td>
                    <td><?= htmlspecialchars($r['customer_name']) ?></td>
                    <td><?= htmlspecialchars($r['driver_name']) ?></td>
                    <td><span class="stars"><?= str_repeat('★', $r['rating']) ?></span></td>
                    <td style="max-width: 250px; font-style: italic;">"<?= htmlspecialchars($r['review']) ?>"</td>
                    <td><?= format_date($r['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>No ratings recorded on the platform yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
