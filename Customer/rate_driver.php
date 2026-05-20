<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_rating'])) {
    $shipment_id = (int) $_POST['shipment_id'];
    $rating = (int) $_POST['rating'];
    $review = sanitize($conn, $_POST['review']);
    
    // Verify shipment belongs to user and is delivered
    $stmt = $conn->prepare("SELECT driver_id FROM shipments WHERE id = ? AND customer_id = ? AND status = 'delivered'");
    $stmt->bind_param("ii", $shipment_id, $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $driver_id = $res->fetch_assoc()['driver_id'];
        
        $insert = $conn->prepare("INSERT INTO ratings (shipment_id, driver_id, customer_id, rating, review) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("iiiis", $shipment_id, $driver_id, $user_id, $rating, $review);
        if ($insert->execute()) {
            $success = "Thank you! Your feedback has been submitted.";
        } else {
            $error = "Failed to submit rating. You may have already rated this trip.";
        }
    } else {
        $error = "Invalid shipment or shipment is not yet delivered.";
    }
}

// Fetch unrated delivered shipments
$unrated = [];
$stmt = $conn->prepare("SELECT s.*, u.name as driver_name FROM shipments s LEFT JOIN users u ON s.driver_id = u.id LEFT JOIN ratings r ON s.id = r.shipment_id WHERE s.customer_id = ? AND s.status = 'delivered' AND r.id IS NULL");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $unrated[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Driver | Customer</title>
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
        .rating-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 700px; margin: 0 auto; }
        .rating-container h2 { margin-bottom: 20px; color: #1a1a2e; text-align: center;}
        .trip-card { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 20px; background: #fafafa;}
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-family: inherit;}
        .btn-submit { background: #E8460A; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
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
        <div class="rating-container">
            <h2>⭐ Rate Your Driver</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

            <?php if(count($unrated) > 0): ?>
                <p style="text-align:center; color:gray; margin-bottom: 20px;">Please share your experience for recently delivered shipments.</p>
                <?php foreach($unrated as $trip): ?>
                <div class="trip-card">
                    <p><strong>Trip #<?= $trip['id'] ?>:</strong> <?= htmlspecialchars($trip['pickup_city']) ?> to <?= htmlspecialchars($trip['drop_city']) ?></p>
                    <p><strong>Driver:</strong> <?= htmlspecialchars($trip['driver_name']) ?></p>
                    <form method="POST" style="margin-top: 15px;">
                        <input type="hidden" name="shipment_id" value="<?= $trip['id'] ?>">
                        <div class="form-group">
                            <label>Rating (1-5 Stars)</label>
                            <select name="rating" required>
                                <option value="5">⭐⭐⭐⭐⭐ - Excellent</option>
                                <option value="4">⭐⭐⭐⭐ - Good</option>
                                <option value="3">⭐⭐⭐ - Average</option>
                                <option value="2">⭐⭐ - Poor</option>
                                <option value="1">⭐ - Terrible</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Review (Optional)</label>
                            <textarea name="review" rows="3" placeholder="How was the service?"></textarea>
                        </div>
                        <button type="submit" name="submit_rating" class="btn-submit">Submit Feedback</button>
                    </form>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">You have no pending shipments to rate.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
