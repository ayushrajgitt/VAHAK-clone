<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Includes/db_connect.php';
require_once 'Includes/functions.php';
require_once 'Includes/twilio_config.php';

session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    if ($role == 'customer') header('Location: Customer/dashboard.php');
    elseif ($role == 'driver') header('Location: Driver/dashboard.php');
    elseif ($role == 'transporter') header('Location: Transporter/dashboard.php');
    elseif ($role == 'admin') header('Location: Admin/dashboard.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $role = sanitize($conn, $_POST['role']);
    $phone = sanitize($conn, $_POST['phone']); 
    $otp_code = sanitize($conn, $_POST['otp_code']);

    // Query to find user by phone AND role
    $stmt = $conn->prepare("SELECT id, name, role, status, otp_code, otp_expires_at FROM users WHERE phone = ? AND role = ?");
    $stmt->bind_param("ss", $phone, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Check if user is active
        if ($user['status'] !== 'active') {
            echo "<script>alert('Account is not active. Please contact support.'); window.location.href='login.php';</script>";
            exit();
        }

        $is_verified = false;

        if (TWILIO_SERVICE_SID == 'YOUR_TWILIO_SERVICE_SID_HERE') {
            // Local simulation verification
            if ($user['otp_code'] === $otp_code) {
                // Check expiration
                $now = new DateTime();
                $expires = new DateTime($user['otp_expires_at']);
                
                if ($now > $expires) {
                    echo "<script>alert('OTP has expired. Please request a new one.'); window.location.href='login.php';</script>";
                    exit();
                }
                $is_verified = true;

                // Clear the OTP so it can't be reused
                $clear_otp = $conn->prepare("UPDATE users SET otp_code = NULL, otp_expires_at = NULL WHERE id = ?");
                $clear_otp->bind_param("i", $user['id']);
                $clear_otp->execute();
            }
        } else {
            // Real Twilio Verification Check
            $url = 'https://verify.twilio.com/v2/Services/' . TWILIO_SERVICE_SID . '/VerificationCheck';
            $data = [
                'To' => '+91' . $phone,
                'Code' => $otp_code
            ];
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, TWILIO_ACCOUNT_SID . ':' . TWILIO_AUTH_TOKEN);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code == 200 || $http_code == 201) {
                $resp_data = json_decode($response, true);
                if (isset($resp_data['status']) && $resp_data['status'] === 'approved') {
                    $is_verified = true;
                }
            }
        }

        if ($is_verified) {
            // Login successful, set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect to appropriate dashboard
            if ($user['role'] == 'customer') {
                header('Location: Customer/dashboard.php');
            } elseif ($user['role'] == 'driver') {
                header('Location: Driver/dashboard.php');
            } elseif ($user['role'] == 'transporter') {
                header('Location: Transporter/dashboard.php');
            } elseif ($user['role'] == 'admin') {
                header('Location: Admin/dashboard.php');
            }
            exit();
        } else {
            echo "<script>alert('Invalid OTP code!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found or incorrect role selected.'); window.location.href='login.php';</script>";
    }
    
    $stmt->close();
    $conn->close();
} else {
    // If someone visits index.php directly without POST, send them to login
    header('Location: login.php');
    exit();
}
?>