<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vahak Logistics</title>
   <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php require __DIR__ . '/partials/nav.php'; ?>
<main>
<?php if (!empty($_SESSION['flash'])): ?>
    <div class="flash"><?= h($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
<?php endif; ?>

<?php
$routes = [
    'home' => __DIR__ . '/pages/home.php',
    'signup' => __DIR__ . '/pages/auth/signup.php',
    'login' => __DIR__ . '/pages/auth/login.php',
    'dashboard' => __DIR__ . '/pages/roles/dashboard.php',
    'loads' => __DIR__ . '/pages/loads.php',
    'load' => __DIR__ . '/pages/load.php',
    'payment' => __DIR__ . '/pages/payment.php',
    'payments' => __DIR__ . '/pages/payments.php',
    'profile' => __DIR__ . '/pages/profile.php',
    'edit_profile' => __DIR__ . '/pages/edit_profile.php',
    'support' => __DIR__ . '/pages/support.php',
    'review' => __DIR__ . '/pages/review.php',
    'admin' => __DIR__ . '/pages/roles/admin.php',
];

require $routes[$page] ?? $routes['home'];
?>
</main>
</body>
</html>
