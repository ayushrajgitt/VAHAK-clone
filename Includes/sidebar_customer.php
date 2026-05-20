<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2>VAHAK</h2>
    <h3>Customer Panel</h3>
    <ul>
        <li class="<?= $current_page == 'dashboard.php' ? 'active-link' : '' ?>" onclick="window.location.href='dashboard.php'">🏠 Home</li>
        <li class="<?= $current_page == 'book_load.php' ? 'active-link' : '' ?>" onclick="window.location.href='book_load.php'">📦 Book Load</li>
        <li class="<?= $current_page == 'track.php' ? 'active-link' : '' ?>" onclick="window.location.href='track.php'">🚚 Track Shipment</li>
        <li class="<?= $current_page == 'orders.php' ? 'active-link' : '' ?>" onclick="window.location.href='orders.php'">📋 My Orders</li>
        <li class="<?= $current_page == 'payments.php' ? 'active-link' : '' ?>" onclick="window.location.href='payments.php'">💳 Payment History</li>
        <li class="<?= $current_page == 'notifications.php' ? 'active-link' : '' ?>" onclick="window.location.href='notifications.php'">🔔 Notifications</li>
        <li class="<?= $current_page == 'support.php' ? 'active-link' : '' ?>" onclick="window.location.href='support.php'">📞 Support</li>
        <li class="<?= $current_page == 'profile.php' ? 'active-link' : '' ?>" onclick="window.location.href='profile.php'">👤 Profile</li>
    </ul>
</div>
<style>
    .sidebar ul li.active-link {
        background: #ff6b35 !important;
    }
</style>
