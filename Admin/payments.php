<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');
$success = $error = '';

// Process Settlement
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['settle_payment'])) {
    $payment_id = (int) $_POST['payment_id'];
    
    $update = $conn->prepare("UPDATE payments SET status = 'settled' WHERE id = ?");
    $update->bind_param("i", $payment_id);
    if ($update->execute()) {
        $success = "Payment #$payment_id has been settled.";
    } else {
        $error = "Failed to settle payment.";
    }
}

// Fetch All Payments
$payments = [];
$stmt = $conn->prepare("SELECT p.*, s.pickup_city, s.drop_city, u1.name as driver_name, u2.name as transporter_name, u3.name as customer_name FROM payments p JOIN shipments s ON p.shipment_id = s.id LEFT JOIN users u1 ON s.driver_id = u1.id LEFT JOIN users u2 ON s.transporter_id = u2.id JOIN users u3 ON s.customer_id = u3.id ORDER BY p.created_at DESC");
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
    <title>Payments | Admin</title>
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
        .status.settled { background: green; }
        .status.paid { background: #007bff; }
        .status.pending { background: orange; }
        .status.failed { background: red; }
        .btn-settle { padding: 4px 8px; border: none; border-radius: 4px; background: #28a745; color: white; cursor: pointer; font-size: 12px; font-weight:bold;}
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
            <h2>💳 Platform Payments</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>
            
            <?php if(count($payments) > 0): ?>
            <table>
                <tr>
                    <th>Pay ID</th>
                    <th>Shipment</th>
                    <th>Customer</th>
                    <th>Payee</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach($payments as $payment): ?>
                <tr>
                    <td>#PAY<?= $payment['id'] ?></td>
                    <td>#<?= $payment['shipment_id'] ?> (<?= htmlspecialchars($payment['pickup_city']) ?> → <?= htmlspecialchars($payment['drop_city']) ?>)</td>
                    <td><?= htmlspecialchars($payment['customer_name']) ?></td>
                    <td>
                        <?php 
                        if ($payment['transporter_name']) {
                            echo "T: " . htmlspecialchars($payment['transporter_name']);
                        } else {
                            echo "D: " . htmlspecialchars($payment['driver_name']);
                        }
                        ?>
                    </td>
                    <td><?= format_currency($payment['amount']) ?></td>
                    <td><span class="status <?= $payment['status'] ?>"><?= ucfirst($payment['status']) ?></span></td>
                    <td>
                        <?php if($payment['status'] === 'pending' || $payment['status'] === 'paid'): ?>
                        <form method="POST">
                            <input type="hidden" name="payment_id" value="<?= $payment['id'] ?>">
                            <button type="submit" name="settle_payment" class="btn-settle">Settle</button>
                        </form>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <p>No payments recorded.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
