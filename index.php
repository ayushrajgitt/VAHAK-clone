<?php

session_start();

// include database connection
include './Includes/db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './Includes/header.php';

// signup logic
if(isset($_POST['signup'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // convert password into encrypted format
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $checkEmail);

    if(mysqli_num_rows($result) > 0){

        echo "<h3>Email already exists</h3>";

    } else {

        // insert new user
        $query = "INSERT INTO users(full_name,email,password,role)
                  VALUES('$name','$email','$hashedPassword','$role')";

        if(mysqli_query($conn, $query)){

         //get newly created user id
         $user_id = mysqli_insert_id($conn);

         //create session
         $_SESSION['user_id']= $user_id;
         $_SESSION['user_name'] = $name; 
         $_SESSION['role'] = $role;

         // redirect according to role
          if($role == 'admin'){

            header("Location: Admin/dashboard.php");

            }

            elseif($role == 'driver'){ 

                header("Location: Driver/dashboard.php");

            }

            elseif($role == 'transporter'){

                header("Location: Transporter/dashboard.php");

            }

            else{

                header("Location: Customer/dashboard.php");

            }
                  exit();

        } else {

            echo "<h3>Error in signup</h3>";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>

    <style>

        body{
            font-family: Arial;
            background: #f4f4f4;
        }

        .container{
            width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
        }

        h2{
            text-align: center;
            margin-bottom: 20px;
        }

        input, select{
            width: 100%;
            padding: 12px;
            margin-top: 10px;
        }

        button{
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            background: #ff6b35;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover{
            background: #e85a2a;
        }

        a{
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>Create Account</h2>

    <form method="POST">

        <input type="text"
               name="name"
               placeholder="Enter Name"
               required>

        <input type="email"
               name="email"
               placeholder="Enter Email"
               required>

        <input type="password"
               name="password"
               placeholder="Enter Password"
               required>

        <select name="role">

            <option value="customer">Customer</option>

            <option value="driver">Driver</option>

            <option value="transporter">Transporter</option>

            <option value="admin">Admin</option>

        </select>

        <button type="submit" name="signup">
            Signup
        </button>

    </form>

    <br>

    <center>
        <a href="login.php">
            Already have account? Login
        </a>
    </center>

</div>

</body>
</html>

