<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('driver');
$user_id = $_SESSION['user_id'];

// Fetch Ratings
$ratings = [];
$stmt = $conn->prepare("SELECT r.*, c.name as customer_name, s.pickup_city, s.drop_city FROM ratings r JOIN users c ON r.customer_id = c.id JOIN shipments s ON r.shipment_id = s.id WHERE r.driver_id = ? ORDER BY r.created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_score = 0;
while ($row = $result->fetch_assoc()) {
    $ratings[] = $row;
    $total_score += $row['rating'];
}

$average_rating = count($ratings) > 0 ? round($total_score / count($ratings), 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Ratings | Driver</title>
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
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 800px; margin: 0 auto; }
        .container h2 { margin-bottom: 20px; color: #1a1a2e; }
        
        .avg-rating { background: #1a1a2e; color: white; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px;}
        .avg-rating h1 { font-size: 48px; color: #E8460A; margin: 10px 0;}
        .avg-rating p { color: #ccc; }

        .review-card { padding: 15px 20px; border: 1px solid #eee; margin-bottom: 15px; border-radius: 8px; }
        .review-card h4 { margin-bottom: 5px; color: #222; display: flex; justify-content: space-between;}
        .stars { color: #ffc107; font-size: 18px;}
        .review-card p.text { font-size: 15px; color: #555; margin: 10px 0; font-style: italic;}
        .review-card p.meta { font-size: 13px; color: #888; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_driver.php'; ?>
    <div class="content">
        <div class="container">
            <h2>⭐ My Ratings & Reviews</h2>
            
            <div class="avg-rating">
                <p>Overall Rating</p>
                <h1><?= $average_rating ?> / 5.0</h1>
                <p>Based on <?= count($ratings) ?> reviews</p>
            </div>

            <?php if(count($ratings) > 0): ?>
                <h3>Recent Feedback</h3>
                <?php foreach($ratings as $r): ?>
                <div class="review-card">
                    <h4>
                        <?= htmlspecialchars($r['customer_name']) ?>
                        <span class="stars"><?= str_repeat('★', $r['rating']) ?><span style="color:#ddd;"><?= str_repeat('★', 5 - $r['rating']) ?></span></span>
                    </h4>
                    <p class="meta">Trip #<?= $r['shipment_id'] ?> (<?= htmlspecialchars($r['pickup_city']) ?> → <?= htmlspecialchars($r['drop_city']) ?>)</p>
                    <?php if($r['review']): ?>
                        <p class="text">"<?= htmlspecialchars($r['review']) ?>"</p>
                    <?php endif; ?>
                    <p class="meta" style="margin-top:5px; text-align:right;"><?= format_date($r['created_at']) ?></p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:gray; text-align:center;">You have not received any ratings yet. Complete trips to get feedback!</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
