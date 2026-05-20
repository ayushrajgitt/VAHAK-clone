<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];

// Fetch payments
$payments = [];
$stmt = $conn->prepare("SELECT p.*, s.pickup_city, s.drop_city FROM payments p JOIN shipments s ON p.shipment_id = s.id WHERE s.customer_id = ? ORDER BY p.paid_at DESC");
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
    <title>Payment History | Customer</title>
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
        .status.paid, .status.settled { background: green; }
        .status.pending { background: orange; }
        .status.failed { background: red; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_customer.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>💳 Payment History</h2>
            <?php if(count($payments) > 0): ?>
            <table>
                <tr>
                    <th>Payment ID</th>
                    <th>Order Details</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php foreach($payments as $payment): ?>
                <tr>
                    <td>#PAY<?= $payment['id'] ?></td>
                    <td>Order #<?= $payment['shipment_id'] ?> (<?= htmlspecialchars($payment['pickup_city']) ?> → <?= htmlspecialchars($payment['drop_city']) ?>)</td>
                    <td><?= format_currency($payment['amount']) ?></td>
                    <td><?= strtoupper($payment['method']) ?></td>
                    <td><?= $payment['paid_at'] ? format_date($payment['paid_at']) : '-' ?></td>
                    <td><span class="status <?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>You have no payment history.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
