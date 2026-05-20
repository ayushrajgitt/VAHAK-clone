<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages | Transporter</title>
    <link rel="stylesheet" href="../Css/style.Css">
    <style>
        body { font-family: Arial, Helvetica, sans-serif; background: #f4f6f9; color: #333; margin:0;}
        .dashboard { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #1a1a2e; color: white; padding: 30px 20px; }
        .sidebar h2 { text-align: center; margin-bottom: 35px; font-size: 28px; color: #E8460A; }
        .sidebar ul { list-style: none; padding:0;}
        .sidebar ul li { padding: 14px 15px; margin-bottom: 12px; background: rgba(255, 255, 255, 0.05); border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 15px; }
        .sidebar ul li:hover { background: #E8460A; }
        .content { flex: 1; padding: 30px; display: flex; flex-direction: column; }
        .chat-container { background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); flex: 1; display: flex; flex-direction: column; overflow: hidden; }
        .chat-header { background: #1a1a2e; color: white; padding: 20px; font-size: 18px; font-weight: bold; }
        .chat-messages { flex: 1; padding: 20px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 15px; }
        .message { max-width: 60%; padding: 12px 18px; border-radius: 20px; font-size: 15px; }
        .message.received { background: #e0e0e0; align-self: flex-start; border-bottom-left-radius: 5px; }
        .message.sent { background: #E8460A; color: white; align-self: flex-end; border-bottom-right-radius: 5px; }
        .chat-input { display: flex; border-top: 1px solid #ddd; background: white; padding: 15px; }
        .chat-input input { flex: 1; padding: 12px; border: 1px solid #ccc; border-radius: 25px; outline: none; }
        .chat-input button { background: #1a1a2e; color: white; border: none; padding: 0 25px; margin-left: 10px; border-radius: 25px; cursor: pointer; font-weight: bold; }
        .chat-input button:hover { background: #E8460A; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <h2 style="margin-bottom: 20px;">💬 Messages</h2>
        <div class="chat-container">
            <div class="chat-header">Fleet Drivers Chat</div>
            <div class="chat-messages">
                <div class="message sent">Hey, did you drop off the load at Mumbai yet?</div>
                <div class="message received">Yes boss, just updated the status to delivered.</div>
            </div>
            <div class="chat-input">
                <input type="text" placeholder="Type a message...">
                <button type="button">Send</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
