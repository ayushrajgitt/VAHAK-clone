<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<?php

// handle login form submission and redirect based on role
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $role = $_POST['role'];

    if ($role == 'customer') {
        header('Location: customer/dashboard.php');
    }

    elseif ($role == 'driver') {
        header('Location: driver/dashboard.php');
    }

    elseif ($role == 'admin') {
        header('Location: admin/dashboard.php');
    }
}

?>