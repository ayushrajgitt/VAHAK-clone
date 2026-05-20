<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];
$success = $error = '';

// Handle Load Assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_load'])) {
    $shipment_id = (int) $_POST['shipment_id'];
    $driver_id = (int) $_POST['driver_id'];
    $vehicle_id = (int) $_POST['vehicle_id'];
    
    // Check if still pending
    $check = $conn->prepare("SELECT status FROM shipments WHERE id = ?");
    $check->bind_param("i", $shipment_id);
    $check->execute();
    $status = $check->get_result()->fetch_assoc()['status'];
    
    if ($status === 'pending') {
        $update = $conn->prepare("UPDATE shipments SET transporter_id = ?, driver_id = ?, vehicle_id = ?, status = 'active' WHERE id = ?");
        $update->bind_param("iiii", $user_id, $driver_id, $vehicle_id, $shipment_id);
        if ($update->execute()) {
            $success = "Load successfully assigned to your driver! It is now in your fleet's active shipments.";
        } else {
            $error = "Failed to assign load. Try again.";
        }
    } else {
        $error = "Sorry, this load is no longer available.";
    }
}

// Fetch pending loads
$loads = [];
$stmt = $conn->prepare("SELECT s.*, u.name as customer_name FROM shipments s JOIN users u ON s.customer_id = u.id WHERE s.status = 'pending' ORDER BY s.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $loads[] = $row;
}

// Fetch Transporter's Drivers
$my_drivers = [];
$d_stmt = $conn->prepare("SELECT u.id, u.name FROM users u JOIN driver_transporter dt ON u.id = dt.driver_id WHERE dt.transporter_id = ?");
$d_stmt->bind_param("i", $user_id);
$d_stmt->execute();
$d_res = $d_stmt->get_result();
while ($row = $d_res->fetch_assoc()) {
    $my_drivers[] = $row;
}

// Fetch Transporter's Vehicles
$my_vehicles = [];
$v_stmt = $conn->prepare("SELECT id, number_plate, type, capacity FROM vehicles WHERE owner_id = ?");
$v_stmt->bind_param("i", $user_id);
$v_stmt->execute();
$v_res = $v_stmt->get_result();
while ($row = $v_res->fetch_assoc()) {
    $my_vehicles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find Loads | Transporter</title>
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
        .load-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
        .load-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-top: 4px solid #E8460A; }
        .load-card h3 { color: #1a1a2e; margin-bottom: 10px; font-size: 18px; }
        .load-details { margin-bottom: 15px; font-size: 14px; color: #555; line-height: 1.6; }
        .load-details strong { color: #222; }
        .assign-form { margin-top: 15px; padding-top: 15px; border-top: 1px solid #eee; }
        .form-group { margin-bottom: 10px; }
        .form-group select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-accept { background: #E8460A; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s; margin-top: 5px;}
        .btn-accept:hover { background: #c03a08; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <h2>📦 Find & Assign Loads</h2>
        <p style="margin-bottom: 20px; color: gray;">Browse open shipments and assign them to your fleet drivers.</p>
        
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
                        <p><strong>Required Truck:</strong> <span style="color:#E8460A; font-weight:bold;"><?= htmlspecialchars($load['truck_type']) ?></span></p>
                        <p><strong>Pickup Date:</strong> <?= $load['pickup_date'] ? format_date($load['pickup_date']) : 'Flexible' ?></p>
                    </div>
                    
                    <form method="POST" class="assign-form">
                        <input type="hidden" name="shipment_id" value="<?= $load['id'] ?>">
                        <div class="form-group">
                            <select name="driver_id" required>
                                <option value="">-- Assign Driver --</option>
                                <?php foreach($my_drivers as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="vehicle_id" required>
                                <option value="">-- Assign Vehicle --</option>
                                <?php foreach($my_vehicles as $v): ?>
                                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['number_plate']) ?> (<?= htmlspecialchars($v['type']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="assign_load" class="btn-accept">Assign to Fleet</button>
                    </form>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No pending loads available right now.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
