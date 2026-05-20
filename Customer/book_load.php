<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pickup_city = sanitize($conn, $_POST['pickup_city']);
    $drop_city = sanitize($conn, $_POST['drop_city']);
    $goods_type = sanitize($conn, $_POST['goods_type']);
    $weight = (float) $_POST['weight'];
    $truck_type = sanitize($conn, $_POST['truck_type']);
    $pickup_date = $_POST['pickup_date'];

    $stmt = $conn->prepare("INSERT INTO shipments (customer_id, pickup_city, drop_city, goods_type, weight, truck_type, pickup_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssdss", $user_id, $pickup_city, $drop_city, $goods_type, $weight, $truck_type, $pickup_date);
    
    if ($stmt->execute()) {
        $success = "Load booked successfully! Waiting for drivers to accept.";
    } else {
        $error = "Failed to book load. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Load | Customer</title>
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
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 600px; margin: 0 auto; }
        .form-container h2 { margin-bottom: 20px; color: #1a1a2e; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #E8460A; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #c03a08; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_customer.php'; ?>
    <div class="content">
        <div class="form-container">
            <h2>📦 Book a New Load</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>Pickup City</label>
                    <input type="text" name="pickup_city" required>
                </div>
                <div class="form-group">
                    <label>Drop City</label>
                    <input type="text" name="drop_city" required>
                </div>
                <div class="form-group">
                    <label>Type of Goods</label>
                    <input type="text" name="goods_type" placeholder="e.g. Electronics, Furniture, FMCG" required>
                </div>
                <div class="form-group">
                    <label>Weight (in Tons)</label>
                    <input type="number" step="0.1" name="weight" required>
                </div>
                <div class="form-group">
                    <label>Preferred Truck Type</label>
                    <select name="truck_type" required>
                        <option value="Open Half Body">Open Half Body</option>
                        <option value="Container">Container</option>
                        <option value="Trailer">Trailer</option>
                        <option value="LCV">LCV (Light Commercial Vehicle)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pickup Date</label>
                    <input type="date" name="pickup_date" required min="<?= date('Y-m-d') ?>">
                </div>
                <button type="submit" class="btn-submit">Book Load Now</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
