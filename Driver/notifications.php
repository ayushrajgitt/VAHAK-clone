<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('driver');
$user_id = $_SESSION['user_id'];

// Fetch Notifications
$notifications = [];
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Mark as read
$update = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
$update->bind_param("i", $user_id);
$update->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications | Driver</title>
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
        .notif-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 800px; margin: 0 auto; }
        .notif-container h2 { margin-bottom: 20px; color: #1a1a2e; }
        .notif-card { padding: 15px 20px; border-left: 4px solid #ccc; background: #f9f9f9; margin-bottom: 15px; border-radius: 4px; }
        .notif-card.unread { border-left-color: #E8460A; background: #fff5f2; }
        .notif-card h4 { margin-bottom: 5px; color: #222; }
        .notif-card p { font-size: 14px; color: #555; margin-bottom: 5px; }
        .notif-time { font-size: 12px; color: #888; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_driver.php'; ?>
    <div class="content">
        <div class="notif-container">
            <h2>🔔 Alerts & Notifications</h2>
            
            <?php if(count($notifications) > 0): ?>
                <?php foreach($notifications as $n): ?>
                <div class="notif-card <?= $n['is_read'] ? '' : 'unread' ?>">
                    <h4><?= htmlspecialchars($n['title']) ?></h4>
                    <p><?= htmlspecialchars($n['message']) ?></p>
                    <div class="notif-time"><?= time_ago($n['created_at']) ?></div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color:gray;">You have no new notifications.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
