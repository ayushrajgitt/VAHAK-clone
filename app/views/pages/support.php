<?php $u = require_user(); ?>
<section class="dash-head"><div><p class="eyebrow">Customer support</p><h1>Ask admin support</h1></div></section>
<?php if ($u['role'] !== 'admin'): ?>
<section class="panel">
    <form method="post" class="form">
        <input type="hidden" name="action" value="support_question">
        <label>Your question<textarea required name="question"></textarea></label>
        <button class="button">Send question</button>
    </form>
</section>
<?php endif; ?>
<section class="panel"><h2><?= $u['role'] === 'admin' ? 'All support tickets' : 'Your support tickets' ?></h2>
    <?php
    if ($u['role'] === 'admin') {
        $tickets = db()->query("SELECT st.*, u.name, u.role FROM support_tickets st JOIN users u ON u.id=st.user_id ORDER BY st.id DESC");
    } else {
        $tickets = db()->prepare("SELECT st.*, u.name, u.role FROM support_tickets st JOIN users u ON u.id=st.user_id WHERE st.user_id=? ORDER BY st.id DESC");
        $tickets->execute([$u['id']]);
    }
    foreach ($tickets as $t): ?>
        <article class="ticket"><h3>#<?= h($t['id']) ?> <?= h($t['status']) ?></h3><p><strong><?= h($t['name']) ?>:</strong> <?= h($t['question']) ?></p><?php if ($t['answer']): ?><p class="answer"><strong>Admin answer:</strong> <?= h($t['answer']) ?></p><?php endif; ?></article>
    <?php endforeach; ?>
</section>
