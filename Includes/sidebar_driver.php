<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2>VAHAK</h2>
    <h3>Driver Panel</h3>
    <ul>
        <li class="<?= $current_page == 'dashboard.php' ? 'active-link' : '' ?>" onclick="window.location.href='dashboard.php'">🏠 Home</li>
        <li class="<?= $current_page == 'available_loads.php' ? 'active-link' : '' ?>" onclick="window.location.href='available_loads.php'">📦 Available Loads</li>
        <li class="<?= $current_page == 'trips.php' ? 'active-link' : '' ?>" onclick="window.location.href='trips.php'">🚚 My Trips</li>
        <li class="<?= $current_page == 'earnings.php' ? 'active-link' : '' ?>" onclick="window.location.href='earnings.php'">💰 Earnings</li>
        <li class="<?= $current_page == 'vehicle.php' ? 'active-link' : '' ?>" onclick="window.location.href='vehicle.php'">🚛 Vehicle Details</li>
        <li class="<?= $current_page == 'routes.php' ? 'active-link' : '' ?>" onclick="window.location.href='routes.php'">🗺️ Route Information</li>
        <li class="<?= $current_page == 'ratings.php' ? 'active-link' : '' ?>" onclick="window.location.href='ratings.php'">⭐ Ratings</li>
        <li class="<?= $current_page == 'notifications.php' ? 'active-link' : '' ?>" onclick="window.location.href='notifications.php'">🔔 Notifications</li>
        <li class="<?= $current_page == 'profile.php' ? 'active-link' : '' ?>" onclick="window.location.href='profile.php'">👤 Profile</li>
    </ul>
</div>
<style>
    .sidebar ul li.active-link {
        background: #ff6b35 !important;
    }
</style>
