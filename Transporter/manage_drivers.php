<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];
$success = $error = '';

// Add Driver by Phone Number
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_driver'])) {
    $driver_phone = sanitize($conn, $_POST['driver_phone']);
    
    // Find driver user
    $find_driver = $conn->prepare("SELECT id, role FROM users WHERE phone = ?");
    $find_driver->bind_param("s", $driver_phone);
    $find_driver->execute();
    $driver_res = $find_driver->get_result();
    
    if ($driver_res->num_rows > 0) {
        $driver = $driver_res->fetch_assoc();
        if ($driver['role'] === 'driver') {
            // Check if already linked
            $check_link = $conn->prepare("SELECT id FROM driver_transporter WHERE driver_id = ? AND transporter_id = ?");
            $check_link->bind_param("ii", $driver['id'], $user_id);
            $check_link->execute();
            if ($check_link->get_result()->num_rows > 0) {
                $error = "This driver is already in your fleet.";
            } else {
                $link_stmt = $conn->prepare("INSERT INTO driver_transporter (driver_id, transporter_id) VALUES (?, ?)");
                $link_stmt->bind_param("ii", $driver['id'], $user_id);
                if ($link_stmt->execute()) {
                    $success = "Driver added to your fleet successfully!";
                } else {
                    $error = "Failed to add driver.";
                }
            }
        } else {
            $error = "User with this phone number is not a registered driver.";
        }
    } else {
        $error = "Driver not found with this phone number.";
    }
}

// Fetch Drivers in Fleet
$drivers = [];
$stmt = $conn->prepare("SELECT u.id, u.name, u.phone, u.email, dt.id as link_id FROM users u JOIN driver_transporter dt ON u.id = dt.driver_id WHERE dt.transporter_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $drivers[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Drivers | Transporter</title>
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
        .grid-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
        .form-section, .table-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .form-section h2, .table-section h2 { margin-bottom: 20px; color: #1a1a2e; font-size: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #E8460A; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        .btn-submit:hover { background: #c03a08; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1a1a2e; color: white; padding: 12px; text-align: left; font-size: 14px;}
        table td { padding: 12px; border-bottom: 1px solid #ddd; font-size: 14px;}
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        @media (max-width: 900px) { .grid-layout { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <h2 style="margin-bottom: 20px;">👥 Manage Drivers</h2>
        
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

        <div class="grid-layout">
            <div class="form-section">
                <h2>Add Driver to Fleet</h2>
                <p style="font-size: 13px; color: gray; margin-bottom: 15px;">Enter the registered mobile number of the driver you want to add.</p>
                <form method="POST">
                    <div class="form-group">
                        <label>Driver's Mobile Number</label>
                        <input type="text" name="driver_phone" placeholder="e.g. 9876543210" required pattern="[0-9]{10}">
                    </div>
                    <button type="submit" name="add_driver" class="btn-submit">Add Driver</button>
                </form>
            </div>

            <div class="table-section">
                <h2>Your Drivers</h2>
                <?php if(count($drivers) > 0): ?>
                <table>
                    <tr>
                        <th>Driver Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                    <?php foreach($drivers as $d): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($d['name']) ?></strong></td>
                        <td><?= htmlspecialchars($d['phone']) ?></td>
                        <td><?= htmlspecialchars($d['email']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else: ?>
                <p>No drivers in your fleet yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
