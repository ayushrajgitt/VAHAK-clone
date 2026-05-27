<nav class="nav">
    <a class="brand" href="<?= $user ? '?page=dashboard' : '?page=home' ?>">Vahak</a>
    <div class="nav-links">
        <?php if (!$user): ?>
            <a href="?page=home">Home</a>
            <a href="?page=login">Login</a>
            <a class="button small" href="?page=signup">Signup</a>
        <?php else: ?>
            <a href="?page=dashboard">Dashboard</a>
            <a href="?page=loads">Loads</a>
            <a href="?page=payments">Payments</a>
            <a href="?page=support">Support</a>
            <?php if ($user['role'] === 'admin'): ?><a href="?page=admin">Admin</a><?php endif; ?>
            <form method="post"><input type="hidden" name="action" value="logout"><button class="link-btn">Logout</button></form>
        <?php endif; ?>
    </div>
</nav>
