<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('transporter');
$user_id = $_SESSION['user_id'];
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $phone = sanitize($conn, $_POST['phone']);
    $email = sanitize($conn, $_POST['email']);
    $company_name = sanitize($conn, $_POST['company_name']);
    $gst_number = sanitize($conn, $_POST['gst_number']);
    $pan_number = sanitize($conn, $_POST['pan_number']);
    
    // Check phone/email
    $stmt = $conn->prepare("SELECT id FROM users WHERE (phone = ? OR email = ?) AND id != ?");
    $stmt->bind_param("ssi", $phone, $email, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $error = "Phone number or email is already in use.";
    } else {
        $conn->begin_transaction();
        try {
            $update_user = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ? WHERE id = ?");
            $update_user->bind_param("sssi", $name, $phone, $email, $user_id);
            $update_user->execute();
            
            $update_tp = $conn->prepare("UPDATE transporter_profiles SET company_name = ?, gst_number = ?, pan_number = ? WHERE user_id = ?");
            $update_tp->bind_param("sssi", $company_name, $gst_number, $pan_number, $user_id);
            $update_tp->execute();
            
            $conn->commit();
            $_SESSION['name'] = $name;
            $success = "Company Profile updated successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Failed to update profile.";
        }
    }
}

// Fetch user & company data
$stmt = $conn->prepare("SELECT u.name, u.phone, u.email, tp.company_name, tp.gst_number, tp.pan_number FROM users u JOIN transporter_profiles tp ON u.id = tp.user_id WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Profile | Transporter</title>
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
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 700px; margin: 0 auto; }
        .form-container h2 { margin-bottom: 20px; color: #1a1a2e; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #E8460A; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #c03a08; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_transporter.php'; ?>
    <div class="content">
        <div class="form-container">
            <h2>🏢 Company Profile</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>
            <form method="POST" action="">
                <h3>Personal Details</h3>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" required pattern="[0-9]{10}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
                
                <h3 style="margin-top: 20px;">Business Details</h3>
                <div class="form-group">
                    <label>Company / Agency Name</label>
                    <input type="text" name="company_name" value="<?= htmlspecialchars($data['company_name']) ?>" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>GST Number</label>
                        <input type="text" name="gst_number" value="<?= htmlspecialchars($data['gst_number']) ?>">
                    </div>
                    <div class="form-group">
                        <label>PAN Number</label>
                        <input type="text" name="pan_number" value="<?= htmlspecialchars($data['pan_number']) ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit" style="margin-top: 15px;">Save Changes</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
