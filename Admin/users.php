<?php
require_once '../Includes/db_connect.php';
require_once '../Includes/auth.php';
require_once '../Includes/functions.php';

require_role('admin');
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_status'])) {
    $target_id = (int) $_POST['user_id'];
    $new_status = sanitize($conn, $_POST['status']);
    
    $update = $conn->prepare("UPDATE users SET status = ? WHERE id = ? AND role != 'admin'");
    $update->bind_param("si", $new_status, $target_id);
    if ($update->execute()) {
        $success = "User status updated to " . ucfirst($new_status) . ".";
    } else {
        $error = "Failed to update status.";
    }
}

// Fetch Users
$users = [];
$stmt = $conn->prepare("SELECT id, name, email, phone, role, status, created_at FROM users WHERE role != 'admin' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users Management | Admin</title>
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
        .table-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .table-section h2 { margin-bottom: 20px; color: #1a1a2e; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #1a1a2e; color: white; padding: 12px; text-align: left; }
        table td { padding: 12px; border-bottom: 1px solid #ddd; }
        table tr:hover { background: #f5f5f5; }
        .status { padding: 6px 12px; border-radius: 20px; color: white; font-size: 13px; font-weight: bold; }
        .status.active { background: green; }
        .status.suspended { background: red; }
        .btn-action { padding: 6px 12px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; color: white; }
        .btn-suspend { background: #dc3545; }
        .btn-activate { background: #28a745; }
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<?php include '../Includes/header.php'; ?>
<div class="dashboard">
    <?php include '../Includes/sidebar_admin.php'; ?>
    <div class="content">
        <div class="table-section">
            <h2>👥 All Users</h2>
            <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
            <?php if($error) echo "<div class='alert alert-error'>$error</div>"; ?>
            
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php foreach($users as $u): ?>
                <tr>
                    <td>#<?= $u['id'] ?></td>
                    <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                    <td><?= htmlspecialchars($u['phone']) ?><br><small><?= htmlspecialchars($u['email']) ?></small></td>
                    <td><?= ucfirst($u['role']) ?></td>
                    <td><span class="status <?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <?php if($u['status'] == 'active'): ?>
                                <input type="hidden" name="status" value="suspended">
                                <button type="submit" name="toggle_status" class="btn-action btn-suspend">Suspend</button>
                            <?php else: ?>
                                <input type="hidden" name="status" value="active">
                                <button type="submit" name="toggle_status" class="btn-action btn-activate">Activate</button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>
</body>
</html>
