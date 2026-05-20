<?php
require_once 'Includes/db_connect.php';
require_once 'Includes/functions.php';
require_once 'Includes/twilio_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = sanitize($conn, $_POST['phone']);
    $role = sanitize($conn, $_POST['role']);
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ? AND role = ?");
    $stmt->bind_param("ss", $phone, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
    } else {
        // Dynamic on-the-fly registration (silent signup)
        $default_name = ucfirst($role) . " User";
        $default_email = $phone . "@vahak-user.com";
        $default_password = password_hash('password', PASSWORD_DEFAULT); // Default placeholder
        
        $insert = $conn->prepare("INSERT INTO users (name, phone, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("sssss", $default_name, $phone, $default_email, $default_password, $role);
        
        if ($insert->execute()) {
            $user_id = $conn->insert_id;
            
            // If transporter, create default profile
            if ($role === 'transporter') {
                $comp_name = $default_name . " Fleet";
                $gst = "";
                $pan = "";
                $profile_stmt = $conn->prepare("INSERT INTO transporter_profiles (user_id, company_name, gst_number, pan_number) VALUES (?, ?, ?, ?)");
                $profile_stmt->bind_param("isss", $user_id, $comp_name, $gst, $pan);
                $profile_stmt->execute();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create dynamic user profile.']);
            exit();
        }
    }
    
    if (TWILIO_SERVICE_SID == 'YOUR_TWILIO_SERVICE_SID_HERE') {
        // Fallback: Local simulation mode using DB
        $otp = rand(100000, 999999);
        $expires_at = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        
        $update = $conn->prepare("UPDATE users SET otp_code = ?, otp_expires_at = ? WHERE id = ?");
        $update->bind_param("ssi", $otp, $expires_at, $user_id);
        
        if ($update->execute()) {
            echo json_encode(['success' => true, 'message' => 'OTP generated (Simulated)', 'dev_otp' => $otp]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error in simulation']);
        }
    } else {
        // Real Mode: Twilio Verify API
        $url = 'https://verify.twilio.com/v2/Services/' . TWILIO_SERVICE_SID . '/Verifications';
        $data = [
            'To' => '+91' . $phone,
            'Channel' => 'sms'
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, TWILIO_ACCOUNT_SID . ':' . TWILIO_AUTH_TOKEN);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code == 201 || $http_code == 200) {
            echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send verification via Twilio', 'error' => json_decode($response, true)]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
