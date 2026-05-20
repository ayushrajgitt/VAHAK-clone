<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('customer');
$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = sanitize($conn, $_POST['subject']);
    $message = sanitize($conn, $_POST['message']);
    
    // Support ticket simulation
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message, is_read) VALUES (?, 1, ?, 0)"); // 1 is Admin ID typically
    $full_message = "SUPPORT TICKET: " . $subject . "\n\n" . $message;
    $stmt->bind_param("is", $user_id, $full_message);
    if ($stmt->execute()) {
        $success = "Your support ticket has been raised. Our team will contact you shortly.";
    } else {
        $error = "Failed to submit support request.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support | Customer</title>
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
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .form-container, .faq-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        h2 { margin-bottom: 20px; color: #1a1a2e; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-family: inherit;}
        .btn-submit { background: #E8460A; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #c03a08; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        
        .faq-item { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee; }
        .faq-item h4 { color: #E8460A; margin-bottom: 8px; }
        .faq-item p { font-size: 14px; color: #555; line-height: 1.5; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_customer.php'; ?>
    <div class="content">
        <h2 style="margin-bottom: 20px;">📞 Help & Support</h2>
        
        <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
        <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>

        <div class="grid-layout">
            <div class="form-container">
                <h2>Contact Us</h2>
                <form method="POST" action="">
                    <div class="form-group">
                        <label>Issue Subject</label>
                        <select name="subject" required>
                            <option value="">-- Select Category --</option>
                            <option value="Shipment Delay">Shipment Delay</option>
                            <option value="Payment Issue">Payment Issue</option>
                            <option value="Damage/Loss">Damage or Loss of Goods</option>
                            <option value="App Feedback">Platform Feedback</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message Details</label>
                        <textarea name="message" rows="6" required placeholder="Describe your issue in detail..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Submit Ticket</button>
                </form>
            </div>

            <div class="faq-container">
                <h2>Frequently Asked Questions</h2>
                <div class="faq-item">
                    <h4>How long does it take for a driver to accept a load?</h4>
                    <p>Usually, loads are picked up within 1-2 hours depending on the pickup location and availability of required trucks.</p>
                </div>
                <div class="faq-item">
                    <h4>How are payments processed?</h4>
                    <p>Payments are automatically processed and held securely until the shipment is marked as "Delivered" by the driver. After delivery, funds are settled.</p>
                </div>
                <div class="faq-item">
                    <h4>What if my goods are damaged?</h4>
                    <p>You can raise a dispute via this support page immediately. Our team will pause the settlement and investigate the issue with the transporter.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
