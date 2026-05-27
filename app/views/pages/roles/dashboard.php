<?php $u = require_user(); ?>
<section class="dash-head">
    <div><p class="eyebrow"><?= h(ucfirst($u['role'])) ?> dashboard</p><h1>Welcome, <?= h($u['name']) ?></h1></div>
    <div class="actions"><a class="button secondary" href="?page=profile&id=<?= $u['id'] ?>">View my profile</a><a class="button secondary" href="?page=edit_profile">Edit profile</a></div>
</section>
<?php
$notes = db()->prepare("SELECT * FROM notifications WHERE user_id IS NULL OR user_id=? ORDER BY id DESC LIMIT 8");
$notes->execute([$u['id']]);
?>
<section class="grid two">
    <article><h3>Notifications</h3><?php foreach ($notes as $n): ?><p class="note"><?= h($n['message']) ?><small><?= h($n['created_at']) ?></small></p><?php endforeach; ?></article>
    <article><h3>Quick actions</h3><p><?= $u['role'] === 'shipper' ? 'Post a load and confirm the best accepting driver.' : ($u['role'] === 'transporter' ? 'Find open loads and assign a fleet driver.' : 'Find open loads and accept trips.') ?></p><div class="actions"><a class="button" href="?page=loads"><?= $u['role'] === 'shipper' ? 'Find driver or transporter' : 'Find load' ?></a><a class="button secondary" href="?page=payments">Payment history</a><a class="button secondary" href="?page=support">Customer support</a></div></article>
</section>
<?php if ($u['role'] === 'shipper'): ?>
    <section class="panel">
        <h2>Add load</h2>
        <form method="post" class="form wide">
            <input type="hidden" name="action" value="add_load">
            <label>Title<input required name="title"></label><label>Pickup<input required name="pickup"></label><label>Destination<input required name="destination"></label>
            <label>Price<input required type="number" name="price"></label><label>Weight<input required name="weight"></label><label>Details<textarea required name="details"></textarea></label>
            <button class="button">Post load</button>
        </form>
    </section>
<?php elseif ($u['role'] === 'transporter'): ?>
    <section class="panel">
        <h2>Your drivers</h2>
        <div class="grid two">
        <?php
        $drivers = db()->prepare("SELECT u.* FROM users u JOIN transporter_drivers td ON td.driver_id=u.id WHERE td.transporter_id=?");
        $drivers->execute([$u['id']]);
        foreach ($drivers as $d): ?>
            <article class="person"><img src="<?= h($d['photo']) ?>" alt=""><div><strong><?= h($d['name']) ?></strong><p><?= h($d['bio']) ?></p><a href="?page=profile&id=<?= $d['id'] ?>">Profile</a></div></article>
        <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>
<section class="panel"><h2>Your shipments</h2><?php render_load_cards($u); ?></section>
