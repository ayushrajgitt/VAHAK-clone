<?php 
require_once __DIR__ . '/auth.php'; 
$user = current_user();
?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* top navigation bar */
    .topbar {
        width: 100%;
        background: #1a1a2e;
        padding: 18px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    /* logo + title group */
    .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo {
        width: 45px;
        height: 45px;
        background: #ff6b35;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 20px;
        font-weight: bold;
    }

    .portal-text h2 {
        color: white;
        font-size: 24px;
        margin-bottom: 3px;
    }

    .portal-text p {
        color: #cfcfcf;
        font-size: 13px;
    }

    /* logout button */
    .logout-btn {
        text-decoration: none;
        background: #ff6b35;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: bold;
        transition: 0.3s;
    }

    .logout-btn:hover {
        background: #e85a2a;
        transform: translateY(-2px);
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .user-name {
        color: white;
        font-weight: bold;
    }
    
    .notification-bell {
        color: white;
        text-decoration: none;
        font-size: 20px;
        position: relative;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -10px;
        background: red;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 10px;
        font-weight: bold;
    }

    /* mobile */
    @media (max-width: 600px) {
        .topbar {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .portal-text h2 {
            font-size: 20px;
        }
    }
</style>

<div class="topbar">

    <!-- brand logo and title -->
    <div class="logo-section">
        <div class="logo">V</div>
        <div class="portal-text">
            <h2>Vahak Logistics Portal</h2>
            <p>Smart Transport & Shipment Management System</p>
        </div>
    </div>

    <!-- User Info & logout -->
    <div class="user-info">
        <?php if ($user): ?>
            <a href="notifications.php" class="notification-bell">
                🔔 <span class="notification-badge">3</span>
            </a>
            <span class="user-name">Hi, <?= htmlspecialchars($user['name']) ?></span>
            <a href="../logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="../login.php" class="logout-btn">Login</a>
        <?php endif; ?>
    </div>

</div>