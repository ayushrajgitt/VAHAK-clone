<?php

session_start();

// clear everything from the session
session_unset();
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout | Vahak Transport Portal</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
            overflow: hidden;
        }

        /* logout card */
        .logout-box {
            width: 400px;
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease;
        }

        .logout-box h1 {
            color: #1a1a2e;
            margin-bottom: 15px;
            font-size: 32px;
        }

        .logout-box p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* checkmark icon */
        .icon {
            width: 90px;
            height: 90px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #ff6b35;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            color: white;
        }

        /* login again button */
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: #ff6b35;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn:hover {
            background: #e85a2a;
            transform: translateY(-3px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* mobile */
        @media (max-width: 500px) {
            .logout-box {
                width: 90%;
                padding: 30px 20px;
            }

            .logout-box h1 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <div class="logout-box">
        <div class="icon">✓</div>
        <h1>Logged Out</h1>
        <p>You have successfully logged out from the Vahak Transport Portal. Thank you for visiting.</p>
        <a href="login.php" class="btn">Login Again</a>
    </div>

</body>
</html>