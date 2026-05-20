<?php
session_start();
require_once 'Includes/db_connect.php';
require_once 'Includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($conn, $_POST['name']);
    $phone = sanitize($conn, $_POST['phone']);
    $email = sanitize($conn, $_POST['email']);
    $password = $_POST['password'];
    $role = sanitize($conn, $_POST['role']);
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if phone or email already exists
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? OR email = ?");
    $check_stmt->bind_param("ss", $phone, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error = "Phone number or Email already registered!";
    } else {
        $conn->begin_transaction();
        try {
            // Insert into users
            $stmt = $conn->prepare("INSERT INTO users (name, phone, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $phone, $email, $hashed_password, $role);
            $stmt->execute();
            $user_id = $conn->insert_id;

            // If transporter, insert into transporter_profiles
            if ($role == 'transporter') {
                $company_name = sanitize($conn, $_POST['company_name']);
                $gst_number = sanitize($conn, $_POST['gst_number']);
                $pan_number = sanitize($conn, $_POST['pan_number']);
                
                $trans_stmt = $conn->prepare("INSERT INTO transporter_profiles (user_id, company_name, gst_number, pan_number) VALUES (?, ?, ?, ?)");
                $trans_stmt->bind_param("isss", $user_id, $company_name, $gst_number, $pan_number);
                $trans_stmt->execute();
            }

            $conn->commit();
            $success = "Registration successful! You can now login.";
        } catch (Exception $e) {
            $conn->rollback();
            $error = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register | Vahak Transport Portal</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Css/style.Css">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Lato', sans-serif;
      padding: 40px 20px;
    }
    .register-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 16px;
      padding: 36px 32px;
      backdrop-filter: blur(14px);
      width: 100%;
      max-width: 500px;
      color: #fff;
    }
    .register-card h3 {
      font-family: 'Poppins', sans-serif;
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 6px;
    }
    .register-card > p {
      font-size: 13px;
      color: rgba(255,255,255,0.6);
      margin-bottom: 24px;
    }
    .form-group { margin-bottom: 14px; }
    .form-group label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: rgba(255,255,255,0.7);
      margin-bottom: 7px;
    }
    .form-group input, .form-group select {
      width: 100%;
      background: rgba(255,255,255,0.07);
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px;
      padding: 12px 14px;
      font-family: 'Lato', sans-serif;
      font-size: 14px;
      color: #fff;
      outline: none;
    }
    .form-group select { color: #000; } /* Text color for options */
    .form-group input:focus, .form-group select:focus { border-color: #E8460A; }
    .submit-btn {
      width: 100%;
      padding: 13px;
      background: #E8460A;
      border: none;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-size: 15px;
      font-weight: 700;
      color: #fff;
      cursor: pointer;
      margin-top: 10px;
    }
    .submit-btn:hover { background: #c03a08; }
    .card-footer-note { text-align: center; margin-top: 16px; font-size: 13px; color: rgba(255,255,255,0.6); }
    .card-footer-note a { color: #ff8c60; text-decoration: none; }
    
    #transporter-fields { display: none; margin-top: 20px; padding-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); }
    
    .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-size: 14px; }
    .alert-error { background: rgba(255,0,0,0.2); border: 1px solid red; color: #ff9999; }
    .alert-success { background: rgba(0,255,0,0.2); border: 1px solid green; color: #99ff99; }
  </style>
</head>
<body>

<div class="register-card">
  <h3>Create an Account</h3>
  <p>Join Vahak to manage your transport business</p>

  <?php if($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
  <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

  <form action="" method="POST" id="registerForm">
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="name" required>
    </div>
    
    <div class="form-group">
      <label>Mobile Number</label>
      <input type="text" name="phone" required pattern="[0-9]{10}" title="10 digit mobile number">
    </div>

    <div class="form-group">
      <label>Email Address</label>
      <input type="email" name="email" required>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" required minlength="6">
    </div>

    <div class="form-group">
      <label>I want to register as:</label>
      <select name="role" id="roleSelect" required>
        <option value="">-- Select Role --</option>
        <option value="customer">Customer (Book Loads)</option>
        <option value="driver">Driver (Find Loads)</option>
        <option value="transporter">Transporter (Fleet Owner)</option>
      </select>
    </div>

    <!-- Transporter specific fields -->
    <div id="transporter-fields">
        <h4 style="margin-bottom: 15px; color: #E8460A;">Company Details</h4>
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" id="company_name">
        </div>
        <div class="form-group">
            <label>GST Number (Optional)</label>
            <input type="text" name="gst_number">
        </div>
        <div class="form-group">
            <label>PAN Number</label>
            <input type="text" name="pan_number" id="pan_number">
        </div>
    </div>

    <button type="submit" class="submit-btn">Register →</button>
  </form>

  <div class="card-footer-note">
    Already have an account? <a href="login.php">Login here</a>
  </div>
</div>

<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        const transFields = document.getElementById('transporter-fields');
        const companyName = document.getElementById('company_name');
        const panNumber = document.getElementById('pan_number');
        
        if (this.value === 'transporter') {
            transFields.style.display = 'block';
            companyName.required = true;
            panNumber.required = true;
        } else {
            transFields.style.display = 'none';
            companyName.required = false;
            panNumber.required = false;
        }
    });
</script>

</body>
</html>
