<?php $profileId = (int)($_GET['id'] ?? 0); $stmt = db()->prepare("SELECT * FROM users WHERE id=?"); $stmt->execute([$profileId]); $p = $stmt->fetch(PDO::FETCH_ASSOC); ?>
<?php if ($p): ?>
<section class="profile">
    <img src="<?= h($p['photo']) ?>" alt="">
    <div><p class="eyebrow"><?= h(ucfirst($p['role'])) ?> profile</p><h1><?= h($p['name']) ?></h1><p><?= h($p['bio']) ?></p><strong><?= average_rating($p['id']) ?></strong><?php if ($user && (int)$user['id'] === (int)$p['id']): ?><p><a class="button secondary" href="?page=edit_profile">Edit profile</a></p><?php endif; ?></div>
</section>
<section class="panel"><h2>Reviews</h2>
    <?php $rs = db()->prepare("SELECT r.*, u.name reviewer FROM reviews r JOIN users u ON u.id=r.reviewer_id WHERE reviewed_id=? ORDER BY r.id DESC"); $rs->execute([$p['id']]); foreach ($rs as $r): ?>
        <p class="note"><strong><?= h($r['rating']) ?>/5 by <?= h($r['reviewer']) ?></strong><?= h($r['comment']) ?></p>
    <?php endforeach; ?>
</section>
<?php endif; ?>
