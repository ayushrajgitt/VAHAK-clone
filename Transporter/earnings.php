<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];

// Calculate Fleet Earnings
$earnings = ['total' => 0, 'pending' => 0];

// Total Settled
$stmt = $conn->prepare("SELECT SUM(amount) as total FROM payments p JOIN shipments s ON p.shipment_id = s.id WHERE s.transporter_id = ? AND p.status = 'settled'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$earnings['total'] = $res['total'] ? $res['total'] : 0;

// Total Pending
$stmt = $conn->prepare("SELECT SUM(amount) as total FROM payments p JOIN shipments s ON p.shipment_id = s.id WHERE s.transporter_id = ? AND p.status IN ('pending', 'paid')");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$earnings['pending'] = $res['total'] ? $res['total'] : 0;

// Fetch Payment History
$payments = [];
$stmt = $conn->prepare("SELECT p.*, s.pickup_city, s.drop_city, u.name as driver_name FROM payments p JOIN shipments s ON p.shipment_id = s.id LEFT JOIN users u ON s.driver_id = u.id WHERE s.transporter_id = ? ORDER BY p.id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $payments[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings | Transporter</title>
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
        .cards { display: flex; gap: 25px; margin-bottom: 35px; }
        .card { flex: 1; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); text-align: center; border-bottom: 5px solid #E8460A; }
        .card.pending { border-bottom-color: orange; }
        .card h2 { font-size: 18px; margin-bottom: 15px; color: #444; }
        .card p { font-size: 38px; font-weight: bold; color: #1a1a2e; }
        .table-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .table-section h2 { margin-bottom: 20px; color: #1a1a2e; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1a1a2e; color: white; padding: 14px; text-align: left; }
        table td { padding: 14px; border-bottom: 1px solid #ddd; }
        table tr:hover { background: #f5f5f5; }
        .status { padding: 6px 12px; border-radius: 20px; color: white; font-size: 13px; font-weight: bold; }
        .status.settled { background: green; }
        .status.paid { background: #007bff; }
        .status.pending { background: orange; }
        .status.failed { background: red; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <h2>💰 Fleet Earnings</h2>
        <p style="margin-bottom: 20px; color: gray;">Track your company's revenue and pending settlements.</p>
        
        <div class="cards">
            <div class="card">
                <h2>Total Settled Revenue</h2>
                <p><?= format_currency($earnings['total']) ?></p>
            </div>
            <div class="card pending">
                <h2>Pending Settlements</h2>
                <p><?= format_currency($earnings['pending']) ?></p>
            </div>
        </div>

        <div class="table-section">
            <h2>Payment History</h2>
            <?php if(count($payments) > 0): ?>
            <table>
                <tr>
                    <th>Pay ID</th>
                    <th>Trip Details</th>
                    <th>Driver</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach($payments as $payment): ?>
                <tr>
                    <td>#PAY<?= $payment['id'] ?></td>
                    <td>Trip #<?= $payment['shipment_id'] ?> (<?= htmlspecialchars($payment['pickup_city']) ?> → <?= htmlspecialchars($payment['drop_city']) ?>)</td>
                    <td><?= $payment['driver_name'] ? htmlspecialchars($payment['driver_name']) : '-' ?></td>
                    <td><?= format_currency($payment['amount']) ?></td>
                    <td><?= $payment['paid_at'] ? format_date($payment['paid_at']) : '-' ?></td>
                    <td><span class="status <?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>No payment history found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
