<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2>VAHAK</h2>
    <h3>Transporter Panel</h3>
    <ul>
        <li class="<?= $current_page == 'dashboard.php' ? 'active-link' : '' ?>" onclick="window.location.href='dashboard.php'">📊 Dashboard</li>
        <li class="<?= $current_page == 'manage_vehicles.php' ? 'active-link' : '' ?>" onclick="window.location.href='manage_vehicles.php'">🚛 Fleet Management</li>
        <li class="<?= $current_page == 'manage_drivers.php' ? 'active-link' : '' ?>" onclick="window.location.href='manage_drivers.php'">👥 Manage Drivers</li>
        <li class="<?= $current_page == 'available_loads.php' ? 'active-link' : '' ?>" onclick="window.location.href='available_loads.php'">📦 Find Loads</li>
        <li class="<?= $current_page == 'shipments.php' ? 'active-link' : '' ?>" onclick="window.location.href='shipments.php'">🚚 Active Shipments</li>
        <li class="<?= $current_page == 'earnings.php' ? 'active-link' : '' ?>" onclick="window.location.href='earnings.php'">💰 Earnings</li>
        <li class="<?= $current_page == 'reports.php' ? 'active-link' : '' ?>" onclick="window.location.href='reports.php'">📑 Reports</li>
        <li class="<?= $current_page == 'profile.php' ? 'active-link' : '' ?>" onclick="window.location.href='profile.php'">👤 Company Profile</li>
    </ul>
</div>
<style>
    .sidebar ul li.active-link {
        background: #ff6b35 !important;
    }
</style>
