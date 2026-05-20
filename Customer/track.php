<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];

// Fetch Tracking Data
$shipment = null;
$error = '';

if (isset($_GET['id'])) {
    $track_id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT s.*, u.name as driver_name, u.phone as driver_phone, v.number_plate FROM shipments s LEFT JOIN users u ON s.driver_id = u.id LEFT JOIN vehicles v ON s.vehicle_id = v.id WHERE s.id = ? AND s.customer_id = ?");
    $stmt->bind_param("ii", $track_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $shipment = $res->fetch_assoc();
    } else {
        $error = "Shipment not found or unauthorized.";
    }
} else {
    // If no ID provided, get their most recent active shipment
    $stmt = $conn->prepare("SELECT s.*, u.name as driver_name, u.phone as driver_phone, v.number_plate FROM shipments s LEFT JOIN users u ON s.driver_id = u.id LEFT JOIN vehicles v ON s.vehicle_id = v.id WHERE s.customer_id = ? AND s.status IN ('active', 'in_transit') ORDER BY s.created_at DESC LIMIT 1");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $shipment = $res->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Shipment | Customer</title>
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
        .track-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 800px; margin: 0 auto; }
        .track-container h2 { margin-bottom: 20px; color: #1a1a2e; text-align: center;}
        
        .search-box { display: flex; gap: 10px; margin-bottom: 30px; justify-content: center; }
        .search-box input { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 250px; }
        .search-box button { background: #1a1a2e; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        .search-box button:hover { background: #E8460A; }

        .timeline { position: relative; max-width: 600px; margin: 40px auto; }
        .timeline::after { content: ''; position: absolute; width: 4px; background-color: #e0e0e0; top: 0; bottom: 0; left: 50%; margin-left: -2px; }
        .timeline-box { padding: 10px 30px; position: relative; background-color: inherit; width: 50%; box-sizing: border-box; }
        .timeline-box::after { content: ''; position: absolute; width: 20px; height: 20px; right: -10px; background-color: white; border: 4px solid #e0e0e0; top: 15px; border-radius: 50%; z-index: 1; }
        .left { left: 0; text-align: right; }
        .right { left: 50%; }
        .right::after { left: -10px; }
        .content-box { padding: 15px; background-color: #f4f6f9; position: relative; border-radius: 6px; }
        
        /* Active states */
        .timeline-box.active::after { border-color: #E8460A; background-color: #E8460A; }
        .timeline-box.completed::after { border-color: green; background-color: green; }
        
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
        .details-grid p { margin-bottom: 8px; color: #555; }
        .details-grid strong { color: #222; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_customer.php'; ?>
    <div class="content">
        <div class="track-container">
            <h2>📍 Track Shipment</h2>
            
            <form class="search-box" method="GET" action="">
                <input type="number" name="id" placeholder="Enter Shipment ID" required value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>">
                <button type="submit">Track</button>
            </form>

            <?php if($error): ?>
                <div style="text-align:center; color:red;"><?= $error ?></div>
            <?php elseif($shipment): ?>
                
                <?php
                // Determine timeline states based on DB status
                $s_pending = 'completed';
                $s_assigned = in_array($shipment['status'], ['active', 'in_transit', 'delivered']) ? 'completed' : '';
                $s_transit = in_array($shipment['status'], ['in_transit', 'delivered']) ? 'completed' : ($shipment['status'] == 'active' ? 'active' : '');
                $s_delivered = $shipment['status'] == 'delivered' ? 'completed' : ($shipment['status'] == 'in_transit' ? 'active' : '');
                ?>

                <div class="timeline">
                    <div class="timeline-box left <?= $s_pending ?>">
                        <div class="content-box">
                            <h3>Load Booked</h3>
                            <p><?= format_date($shipment['created_at']) ?></p>
                        </div>
                    </div>
                    <div class="timeline-box right <?= $s_assigned ?>">
                        <div class="content-box">
                            <h3>Driver Assigned</h3>
                            <p>Truck: <?= $shipment['truck_type'] ?></p>
                        </div>
                    </div>
                    <div class="timeline-box left <?= $s_transit ?>">
                        <div class="content-box">
                            <h3>In Transit</h3>
                            <p>Moving from <?= htmlspecialchars($shipment['pickup_city']) ?></p>
                        </div>
                    </div>
                    <div class="timeline-box right <?= $s_delivered ?>">
                        <div class="content-box">
                            <h3>Delivered</h3>
                            <p>Arrived at <?= htmlspecialchars($shipment['drop_city']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="details-grid">
                    <div>
                        <h3 style="margin-bottom: 10px;">Shipment Details</h3>
                        <p><strong>Tracking ID:</strong> #<?= $shipment['id'] ?></p>
                        <p><strong>Route:</strong> <?= htmlspecialchars($shipment['pickup_city']) ?> → <?= htmlspecialchars($shipment['drop_city']) ?></p>
                        <p><strong>Goods:</strong> <?= htmlspecialchars($shipment['goods_type']) ?> (<?= $shipment['weight'] ?>T)</p>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 10px;">Driver Information</h3>
                        <?php if($shipment['driver_name']): ?>
                            <p><strong>Name:</strong> <?= htmlspecialchars($shipment['driver_name']) ?></p>
                            <p><strong>Contact:</strong> <?= htmlspecialchars($shipment['driver_phone']) ?></p>
                            <p><strong>Vehicle:</strong> <?= htmlspecialchars($shipment['number_plate']) ?></p>
                        <?php else: ?>
                            <p style="color:gray;">Waiting for driver assignment...</p>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div style="text-align:center; color:gray;">Please enter a Shipment ID to track or wait until your pending shipments are activated.</div>
            <?php endif; ?>

        </div>
    </div>
</div>
</body>
</html>
