<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('driver');
$user_id = $_SESSION['user_id'];
$success = $error = '';

// Handle Status Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $shipment_id = (int) $_POST['shipment_id'];
    $new_status = sanitize($conn, $_POST['status']);
    
    // Make sure the trip belongs to this driver
    $check = $conn->prepare("SELECT id FROM shipments WHERE id = ? AND driver_id = ?");
    $check->bind_param("ii", $shipment_id, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $update = $conn->prepare("UPDATE shipments SET status = ? WHERE id = ?");
        $update->bind_param("si", $new_status, $shipment_id);
        if ($update->execute()) {
            $success = "Shipment status updated to " . ucfirst($new_status) . "!";
            
            // If delivered, we should automatically create a payment record (Simulation)
            if ($new_status === 'delivered') {
                $check_payment = $conn->prepare("SELECT id FROM payments WHERE shipment_id = ?");
                $check_payment->bind_param("i", $shipment_id);
                $check_payment->execute();
                if ($check_payment->get_result()->num_rows == 0) {
                    // Create pending payment
                    $price = rand(5000, 25000); // Simulated price
                    $pay_stmt = $conn->prepare("INSERT INTO payments (shipment_id, amount, method, status) VALUES (?, ?, 'bank_transfer', 'pending')");
                    $pay_stmt->bind_param("id", $shipment_id, $price);
                    $pay_stmt->execute();
                }
            }
        } else {
            $error = "Failed to update status.";
        }
    } else {
        $error = "Invalid request.";
    }
}

// Fetch Active and Past Trips
$trips = [];
$stmt = $conn->prepare("SELECT s.*, u.name as customer_name, u.phone as customer_phone FROM shipments s JOIN users u ON s.customer_id = u.id WHERE s.driver_id = ? ORDER BY CASE WHEN s.status IN ('active', 'in_transit') THEN 1 ELSE 2 END, s.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $trips[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Trips | Driver</title>
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
        .trip-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
        .trip-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); position: relative; }
        .trip-card.active-trip { border-top: 4px solid #E8460A; }
        .trip-card.past-trip { border-top: 4px solid gray; opacity: 0.85; }
        .trip-card h3 { color: #1a1a2e; margin-bottom: 15px; font-size: 18px; display: flex; justify-content: space-between; align-items: center; }
        .trip-details { margin-bottom: 15px; font-size: 14px; color: #555; line-height: 1.6; }
        .trip-details strong { color: #222; }
        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; color: white; }
        .status-badge.active, .status-badge.in_transit { background: #E8460A; }
        .status-badge.delivered { background: green; }
        .update-form { display: flex; gap: 10px; margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; }
        .update-form select { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        .update-form button { background: #1a1a2e; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .update-form button:hover { background: #E8460A; }
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
        <h2>🚚 My Trips</h2>
        <p style="margin-bottom: 20px; color: gray;">Manage your active deliveries and view past trips.</p>
        
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

        <div class="trip-grid">
            <?php if(count($trips) > 0): ?>
                <?php foreach($trips as $trip): ?>
                <?php $is_active = in_array($trip['status'], ['active', 'in_transit']); ?>
                <div class="trip-card <?= $is_active ? 'active-trip' : 'past-trip' ?>">
                    <h3>
                        <?= htmlspecialchars($trip['pickup_city']) ?> ➔ <?= htmlspecialchars($trip['drop_city']) ?>
                        <span class="status-badge <?= $trip['status'] ?>"><?= ucfirst($trip['status']) ?></span>
                    </h3>
                    <div class="trip-details">
                        <p><strong>Customer:</strong> <?= htmlspecialchars($trip['customer_name']) ?> (<?= htmlspecialchars($trip['customer_phone']) ?>)</p>
                        <p><strong>Goods:</strong> <?= htmlspecialchars($trip['goods_type']) ?> (<?= $trip['weight'] ?> Tons)</p>
                        <p><strong>Date Accepted:</strong> <?= format_date($trip['created_at']) ?></p>
                        <?php if($trip['delivery_date']): ?>
                            <p><strong>Delivered On:</strong> <?= format_date($trip['delivery_date']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($is_active): ?>
                    <form method="POST" class="update-form">
                        <input type="hidden" name="shipment_id" value="<?= $trip['id'] ?>">
                        <select name="status" required>
                            <option value="">-- Update Status --</option>
                            <?php if($trip['status'] == 'active'): ?>
                                <option value="in_transit">Mark as In Transit</option>
                            <?php endif; ?>
                            <option value="delivered">Mark as Delivered</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have no trips. Go to <a href="available_loads.php">Available Loads</a> to accept one.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
