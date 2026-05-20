<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vehicle'])) {
    $type = sanitize($conn, $_POST['type']);
    $capacity = (float) $_POST['capacity'];
    $number_plate = sanitize($conn, $_POST['number_plate']);
    
    $check = $conn->prepare("SELECT id FROM vehicles WHERE number_plate = ?");
    $check->bind_param("s", $number_plate);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $error = "A vehicle with this number plate is already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO vehicles (owner_id, type, capacity, number_plate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isds", $user_id, $type, $capacity, $number_plate);
        if ($stmt->execute()) {
            $success = "Vehicle added to your fleet successfully!";
        } else {
            $error = "Failed to add vehicle.";
        }
    }
}

// Fetch Fleet
$vehicles = [];
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE owner_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fleet Management | Transporter</title>
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
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #E8460A; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-weight: bold; }
        .btn-submit:hover { background: #c03a08; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1a1a2e; color: white; padding: 12px; text-align: left; font-size: 14px;}
        table td { padding: 12px; border-bottom: 1px solid #ddd; font-size: 14px;}
        .status { padding: 4px 8px; border-radius: 12px; color: white; font-size: 12px; font-weight: bold; }
        .status.available { background: green; }
        .status.on_trip { background: orange; }
        .status.maintenance { background: red; }
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
        <h2 style="margin-bottom: 20px;">🚛 Fleet Management</h2>
        
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

        <div class="grid-layout">
            <!-- Add Vehicle Form -->
            <div class="form-section">
                <h2>Add New Vehicle</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Vehicle Type</label>
                        <select name="type" required>
                            <option value="Open Half Body">Open Half Body</option>
                            <option value="Container">Container</option>
                            <option value="Trailer">Trailer</option>
                            <option value="LCV">LCV</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Capacity (Tons)</label>
                        <input type="number" step="0.1" name="capacity" required>
                    </div>
                    <div class="form-group">
                        <label>Number Plate</label>
                        <input type="text" name="number_plate" placeholder="e.g. MH 04 AB 1234" required>
                    </div>
                    <button type="submit" name="add_vehicle" class="btn-submit">Add to Fleet</button>
                </form>
            </div>

            <!-- Fleet Table -->
            <div class="table-section">
                <h2>Your Fleet</h2>
                <?php if(count($vehicles) > 0): ?>
                <table>
                    <tr>
                        <th>Plate</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach($vehicles as $v): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($v['number_plate']) ?></strong></td>
                        <td><?= htmlspecialchars($v['type']) ?></td>
                        <td><?= $v['capacity'] ?>T</td>
                        <td><span class="status <?= $v['status'] ?>"><?= ucfirst(str_replace('_', ' ', $v['status'])) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else: ?>
                <p>No vehicles in your fleet yet.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
</body>
</html>
