<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');

// Fetch Popular Routes
$routes = [];
$stmt = $conn->prepare("SELECT pickup_city, drop_city, COUNT(*) as count FROM shipments GROUP BY pickup_city, drop_city ORDER BY count DESC LIMIT 50");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $routes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Popular Routes | Admin</title>
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
        .table-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 800px; }
        .table-section h2 { margin-bottom: 20px; color: #1a1a2e; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1a1a2e; color: white; padding: 12px; text-align: left; font-size: 14px;}
        table td { padding: 12px; border-bottom: 1px solid #ddd; font-size: 14px;}
        table tr:hover { background: #f5f5f5; }
        .badge { background: #E8460A; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_admin.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>📍 Platform Hot Routes</h2>
            <?php if(count($routes) > 0): ?>
            <table>
                <tr>
                    <th>Rank</th>
                    <th>Origin City</th>
                    <th>Destination City</th>
                    <th>Total Shipments</th>
                </tr>
                <?php $i = 1; foreach($routes as $r): ?>
                <tr>
                    <td><strong>#<?= $i++ ?></strong></td>
                    <td><?= htmlspecialchars($r['pickup_city']) ?></td>
                    <td><?= htmlspecialchars($r['drop_city']) ?></td>
                    <td><span class="badge"><?= $r['count'] ?> Trips</span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>No routes recorded on the platform yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
