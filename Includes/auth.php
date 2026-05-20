<?php
session_start();

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Require login (redirect to login page if not logged in)
function require_login() {
    if (!is_logged_in()) {
        header('Location: ../login.php');
        exit();
    }
}

// Require specific role (redirect if role doesn't match)
function require_role($role) {
    require_login();
    if ($_SESSION['role'] !== $role) {
        // Redirect to their actual dashboard
        $actual_role = $_SESSION['role'];
        if ($actual_role == 'customer') header('Location: ../Customer/dashboard.php');
        elseif ($actual_role == 'driver') header('Location: ../Driver/dashboard.php');
        elseif ($actual_role == 'transporter') header('Location: ../Transporter/dashboard.php');
        elseif ($actual_role == 'admin') header('Location: ../Admin/dashboard.php');
        else header('Location: ../login.php');
        exit();
    }
}

// Get current logged-in user data
function current_user() {
    if (!is_logged_in()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['name'],
        'role' => $_SESSION['role']
    ];
}
?>
