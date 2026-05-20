<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];

// Transporter Reports Logic
// 1. Fleet Utilization (Active vs Idle Vehicles)
// Simplistic assumption: If a vehicle is assigned to an 'active' or 'in_transit' shipment, it's active.
$total_vehicles = $conn->query("SELECT COUNT(*) as cnt FROM vehicles WHERE owner_id = $user_id")->fetch_assoc()['cnt'];
$active_vehicles = $conn->query("SELECT COUNT(DISTINCT vehicle_id) as cnt FROM shipments WHERE transporter_id = $user_id AND status IN ('active', 'in_transit')")->fetch_assoc()['cnt'];
$idle_vehicles = $total_vehicles - $active_vehicles;

// 2. Earnings by Month
$stmt = $conn->prepare("SELECT MONTH(p.paid_at) as month, SUM(p.amount) as total FROM payments p JOIN shipments s ON p.shipment_id = s.id WHERE s.transporter_id = ? AND p.status = 'settled' AND YEAR(p.paid_at) = YEAR(CURRENT_DATE()) GROUP BY MONTH(p.paid_at) ORDER BY month");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$revenue_data = array_fill(1, 12, 0);
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
    <title>Fleet Reports | Transporter</title>
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
        .stat-box { background: #f4f6f9; padding: 20px; text-align: center; border-radius: 8px; margin-bottom: 15px;}
        .stat-box h3 { color: #555; font-size: 14px; margin-bottom: 10px;}
        .stat-box p { font-size: 28px; font-weight: bold; color: #E8460A;}
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <h2 style="margin-bottom: 20px;">📊 Fleet Reports & Analytics</h2>
        
        <div class="grid-layout">
            <!-- Revenue Line Chart -->
            <div class="chart-container">
                <h2>Monthly Fleet Revenue (<?= date('Y') ?>)</h2>
                <canvas id="revenueChart"></canvas>
            </div>
            
            <!-- Fleet Utilization -->
            <div class="chart-container">
                <h2>Fleet Utilization</h2>
                <div style="display:flex; gap:15px; margin-top:20px;">
                    <div class="stat-box" style="flex:1;">
                        <h3>Total Registered Vehicles</h3>
                        <p><?= $total_vehicles ?></p>
                    </div>
                </div>
                <div style="display:flex; gap:15px;">
                    <div class="stat-box" style="flex:1;">
                        <h3>Active on Trips</h3>
                        <p style="color:green;"><?= $active_vehicles ?></p>
                    </div>
                    <div class="stat-box" style="flex:1;">
                        <h3>Idle Vehicles</h3>
                        <p style="color:orange;"><?= $idle_vehicles < 0 ? 0 : $idle_vehicles ?></p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
// Chart.js Configuration
const ctx = document.getElementById('revenueChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= $revenue_chart_labels ?>,
        datasets: [{
            label: 'Settled Revenue (₹)',
            data: <?= $revenue_chart_data ?>,
            borderColor: '#E8460A',
            backgroundColor: 'rgba(232, 70, 10, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.3
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
