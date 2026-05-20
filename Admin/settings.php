<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // In a real app, these would save to a 'settings' table in the DB
    $success = "Platform settings updated successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings | Admin</title>
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
        .form-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 600px; }
        .form-container h2 { margin-bottom: 20px; color: #1a1a2e; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        .form-group input, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn-submit { background: #E8460A; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #c03a08; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_admin.php'; ?>
    <div class="content">
        <div class="form-container">
            <h2>⚙️ Platform Settings</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Platform Name</label>
                    <input type="text" name="site_name" value="Vahak Transport Portal" required>
                </div>
                <div class="form-group">
                    <label>Support Email</label>
                    <input type="email" name="support_email" value="support@vahak-clone.com" required>
                </div>
                <div class="form-group">
                    <label>Platform Commission Fee (%)</label>
                    <input type="number" step="0.1" name="commission" value="2.5" required>
                </div>
                <div class="form-group">
                    <label>Allow New Registrations</label>
                    <select name="allow_registration">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Save Settings</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
