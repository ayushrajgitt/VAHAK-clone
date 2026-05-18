<?php

session_start();

// include database connection
include 'Includes/db.php';

// check form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // check user in database
    $sql = "SELECT * FROM users 
            WHERE username='$username' 
            AND password='$password' 
            AND role='$role'";

    $result = mysqli_query($conn, $sql);

    // if login successful
    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        // create session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // redirect based on role
        if ($role == 'admin') {
            header("Location: Admin/dashboard.php");
        }

        elseif ($role == 'driver') {
            header("Location: Driver/dashboard.php");
        }

        elseif ($role == 'customer') {
            header("Location: Customer/dashboard.php");
        }

        elseif ($role == 'transporter') {
            header("Location: Transporter/dashboard.php");
        }

        exit();

    } 
    
    else {

        echo "<script>
                alert('Invalid Login Credentials');
                window.location.href='login.php';
              </script>";

    }

}

?>