<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');

// Fetch some basic report data
$reports = [];

// 1. Shipments by Status
$stmt = $conn->prepare("SELECT status, COUNT(*) as count FROM shipments GROUP BY status");
$stmt->execute();
$res = $stmt->get_result();
$shipment_status = [];
while ($row = $res->fetch_assoc()) {
    $shipment_status[] = $row;
}

// 2. Revenue by Month (Current Year)
$stmt = $conn->prepare("SELECT MONTH(paid_at) as month, SUM(amount) as total FROM payments WHERE status = 'settled' AND YEAR(paid_at) = YEAR(CURRENT_DATE()) GROUP BY MONTH(paid_at) ORDER BY month");
$stmt->execute();
$res = $stmt->get_result();
$revenue_data = array_fill(1, 12, 0); // Initialize all months with 0
while ($row = $res->fetch_assoc()) {
    $revenue_data[(int)$row['month']] = (float)$row['total'];
}
$month_names = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$revenue_chart_labels = json_encode($month_names);
$revenue_chart_data = json_encode(array_values($revenue_data));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports & Analytics | Admin</title>
    <link rel="stylesheet" href="../Css/style.Css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f4f6f9; color: #333; margin:0;}
        .dashboard { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1a1a2e; color: white; padding: 30px 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 35px; font-size: 28px; color: #E8460A; }
        .sidebar ul { list-style: none; padding:0;}
        .sidebar ul li { padding: 14px 15px; margin-bottom: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .sidebar ul li:hover { background: #E8460A; }
        .content { flex: 1; padding: 30px; }
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .chart-container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .chart-container h2 { margin-bottom: 20px; color: #1a1a2e; font-size: 18px; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th { background: #1a1a2e; color: white; padding: 10px; text-align: left; }
        table td { padding: 10px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_admin.php'; ?>
    <div class="content">
        <h2 style="margin-bottom: 20px;">📈 Reports & Analytics</h2>
        
        <div class="grid-layout">
            <!-- Revenue Line Chart -->
            <div class="chart-container">
                <h2>Monthly Revenue (<?= date('Y') ?>)</h2>
                <canvas id="revenueChart"></canvas>
            </div>
            
            <!-- Shipments Status Data -->
            <div class="chart-container">
                <h2>Shipment Status Overview</h2>
                <table>
                    <tr>
                        <th>Status</th>
                        <th>Total Count</th>
                    </tr>
                    <?php foreach($shipment_status as $status): ?>
                    <tr>
                        <td><strong><?= ucfirst($status['status']) ?></strong></td>
                        <td><?= $status['count'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script>
// Chart.js Configuration
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= $revenue_chart_labels ?>,
        datasets: [{
            label: 'Settled Revenue (₹)',
            data: <?= $revenue_chart_data ?>,
            backgroundColor: '#E8460A',
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
</body>
</html>
