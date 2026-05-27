<?php $u = require_user(); if ($u['role'] !== 'admin') redirect('?page=dashboard'); ?>
<section class="dash-head"><div><p class="eyebrow">Admin</p><h1>Manage platform</h1></div></section>
<section class="grid two">
    <article><h3>All users</h3><?php foreach (db()->query("SELECT * FROM users ORDER BY id DESC") as $x): ?><p class="row"><?= h($x['name']) ?> | <?= h($x['role']) ?><form method="post"><input type="hidden" name="action" value="admin_delete_user"><input type="hidden" name="user_id" value="<?= $x['id'] ?>"><button class="danger">Delete</button></form></p><?php endforeach; ?></article>
    <article><h3>All actions</h3><?php foreach (db()->query("SELECT * FROM notifications ORDER BY id DESC LIMIT 20") as $n): ?><p class="note"><?= h($n['message']) ?><small><?= h($n['created_at']) ?></small></p><?php endforeach; ?></article>
</section>
<section class="panel"><h2>Support questions</h2>
    <?php foreach (db()->query("SELECT st.*, u.name, u.role FROM support_tickets st JOIN users u ON u.id=st.user_id ORDER BY st.id DESC") as $t): ?>
        <article class="ticket">
            <h3>#<?= h($t['id']) ?> from <?= h($t['name']) ?> (<?= h($t['role']) ?>)</h3>
            <p><?= h($t['question']) ?></p>
            <?php if ($t['answer']): ?><p class="answer"><strong>Answer:</strong> <?= h($t['answer']) ?></p><?php else: ?>
                <form method="post" class="form inline"><input type="hidden" name="action" value="admin_answer_support"><input type="hidden" name="ticket_id" value="<?= h($t['id']) ?>"><label>Admin answer<input required name="answer"></label><button class="button">Answer</button></form>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</section>
<section class="panel"><h2>All shipments</h2><?php foreach (db()->query("SELECT * FROM loads ORDER BY id DESC") as $l): ?><p class="row">#<?= h($l['id']) ?> <?= h($l['title']) ?> | <?= h($l['status']) ?><form method="post"><input type="hidden" name="action" value="admin_delete_load"><input type="hidden" name="load_id" value="<?= $l['id'] ?>"><button class="danger">Delete</button></form></p><?php endforeach; ?></section>
