<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('driver');
$user_id = $_SESSION['user_id'];
$success = $error = '';

// Handle Load Acceptance
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accept_load'])) {
    $shipment_id = (int) $_POST['shipment_id'];
    
    // Check if still pending
    $check = $conn->prepare("SELECT status FROM shipments WHERE id = ?");
    $check->bind_param("i", $shipment_id);
    $check->execute();
    $status = $check->get_result()->fetch_assoc()['status'];
    
    if ($status === 'pending') {
        $update = $conn->prepare("UPDATE shipments SET driver_id = ?, status = 'active' WHERE id = ?");
        $update->bind_param("ii", $user_id, $shipment_id);
        if ($update->execute()) {
            $success = "Load accepted successfully! It is now in your active trips.";
        } else {
            $error = "Failed to accept load. Try again.";
        }
    } else {
        $error = "Sorry, this load is no longer available.";
    }
}

// Fetch Pending Loads
$loads = [];
// Assuming drivers can only see loads that are 'pending'
$stmt = $conn->prepare("SELECT s.*, u.name as customer_name FROM shipments s JOIN users u ON s.customer_id = u.id WHERE s.status = 'pending' ORDER BY s.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $loads[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Loads | Driver</title>
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
        .load-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .load-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-top: 4px solid #E8460A; }
        .load-card h3 { color: #1a1a2e; margin-bottom: 10px; font-size: 18px; }
        .load-details { margin-bottom: 15px; font-size: 14px; color: #555; line-height: 1.6; }
        .load-details strong { color: #222; }
        .btn-accept { background: #E8460A; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s; }
        .btn-accept:hover { background: #c03a08; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_driver.php'; ?>
    <div class="content">
        <h2>📦 Available Loads</h2>
        <p style="margin-bottom: 20px; color: gray;">Browse and accept open shipments from customers.</p>
        
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

        <div class="load-grid">
            <?php if(count($loads) > 0): ?>
                <?php foreach($loads as $load): ?>
                <div class="load-card">
                    <h3><?= htmlspecialchars($load['pickup_city']) ?> ➔ <?= htmlspecialchars($load['drop_city']) ?></h3>
                    <div class="load-details">
                        <p><strong>Customer:</strong> <?= htmlspecialchars($load['customer_name']) ?></p>
                        <p><strong>Goods:</strong> <?= htmlspecialchars($load['goods_type']) ?> (<?= $load['weight'] ?> Tons)</p>
                        <p><strong>Required Truck:</strong> <?= htmlspecialchars($load['truck_type']) ?></p>
                        <p><strong>Pickup Date:</strong> <?= $load['pickup_date'] ? format_date($load['pickup_date']) : 'Flexible' ?></p>
                        <p><strong>Posted:</strong> <?= time_ago($load['created_at']) ?></p>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="shipment_id" value="<?= $load['id'] ?>">
                        <button type="submit" name="accept_load" class="btn-accept">Accept Load</button>
                    </form>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No pending loads available right now. Check back later!</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
