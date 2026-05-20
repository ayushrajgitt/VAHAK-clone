<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('driver');
$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_route'])) {
    $origin = sanitize($conn, $_POST['origin']);
    $destination = sanitize($conn, $_POST['destination']);
    
    // Check if route exists
    $check = $conn->prepare("SELECT id FROM driver_routes WHERE driver_id = ? AND origin = ? AND destination = ?");
    $check->bind_param("iss", $user_id, $origin, $destination);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $error = "You already have this preferred route saved.";
    } else {
        $stmt = $conn->prepare("INSERT INTO driver_routes (driver_id, origin, destination) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $origin, $destination);
        if ($stmt->execute()) {
            $success = "Preferred route added!";
        } else {
            $error = "Failed to add route.";
        }
    }
}

// Fetch Preferred Routes
$routes = [];
// Assuming driver_routes table exists, if not we will fail gracefully or the schema has it (schema didn't explicitly have it but we'll mock it or use a separate table if it exists)
// Actually we didn't define driver_routes in db_setup.sql. Let's just create it on the fly if it doesn't exist for robustness.
$conn->query("CREATE TABLE IF NOT EXISTS driver_routes (id INT AUTO_INCREMENT PRIMARY KEY, driver_id INT, origin VARCHAR(100), destination VARCHAR(100), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");

$stmt = $conn->prepare("SELECT * FROM driver_routes WHERE driver_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $routes[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preferred Routes | Driver</title>
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
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_driver.php'; ?>
    <div class="content">
        <h2 style="margin-bottom: 20px;">🛣️ Preferred Routes</h2>
        
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

        <div class="grid-layout">
            <div class="form-section">
                <h2>Add Route</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Origin City</label>
                        <input type="text" name="origin" required>
                    </div>
                    <div class="form-group">
                        <label>Destination City</label>
                        <input type="text" name="destination" required>
                    </div>
                    <button type="submit" name="add_route" class="btn-submit">Save Route</button>
                </form>
            </div>

            <div class="table-section">
                <h2>Saved Routes</h2>
                <?php if(count($routes) > 0): ?>
                <table>
                    <tr>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Added On</th>
                    </tr>
                    <?php foreach($routes as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['origin']) ?></strong></td>
                        <td><strong><?= htmlspecialchars($r['destination']) ?></strong></td>
                        <td><?= format_date($r['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else: ?>
                <p>No preferred routes saved. Save routes to get better load recommendations.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
</body>
</html>
