<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2>VAHAK</h2>
    <h3>Admin Panel</h3>
    <ul>
        <li class="<?= $current_page == 'dashboard.php' ? 'active-link' : '' ?>" onclick="window.location.href='dashboard.php'">📊 Dashboard</li>
        <li class="<?= $current_page == 'users.php' ? 'active-link' : '' ?>" onclick="window.location.href='users.php'">👥 Users</li>
        <li class="<?= $current_page == 'shipments.php' ? 'active-link' : '' ?>" onclick="window.location.href='shipments.php'">📦 Shipments</li>
        <li class="<?= $current_page == 'payments.php' ? 'active-link' : '' ?>" onclick="window.location.href='payments.php'">💳 Payments</li>
        <li class="<?= $current_page == 'reports.php' ? 'active-link' : '' ?>" onclick="window.location.href='reports.php'">📈 Reports</li>
        <li class="<?= $current_page == 'settings.php' ? 'active-link' : '' ?>" onclick="window.location.href='settings.php'">⚙️ Settings</li>
    </ul>
</div>
<style>
    .sidebar ul li.active-link {
        background: #ff6b35 !important;
    }
</style>
