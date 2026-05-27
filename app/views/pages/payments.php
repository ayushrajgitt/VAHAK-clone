<?php $u = require_user(); ?>
<section class="dash-head"><div><p class="eyebrow">Payments</p><h1>Payment history</h1></div></section>
<section class="panel">
    <div class="grid two">
    <?php
    if ($u['role'] === 'shipper') {
        $payments = db()->prepare("SELECT * FROM loads WHERE shipper_id=? AND status IN ('ready_to_deliver','otp_sent','paid') ORDER BY id DESC");
        $payments->execute([$u['id']]);
    } else {
        $payments = db()->prepare("SELECT * FROM loads WHERE (accepted_by=? OR assigned_driver_id=?) AND status IN ('ready_to_deliver','otp_sent','paid') ORDER BY id DESC");
        $payments->execute([$u['id'], $u['id']]);
    }
    foreach ($payments as $pay): ?>
        <article class="load"><h3>#<?= h($pay['id']) ?> <?= h($pay['title']) ?></h3><p><?= h($pay['pickup']) ?> to <?= h($pay['destination']) ?></p><p><strong>Rs <?= h($pay['price']) ?></strong> | <?= $pay['status'] === 'paid' ? 'Released' : 'On hold' ?></p><div class="actions"><a class="button secondary" href="?page=payment&id=<?= h($pay['id']) ?>">Open payment</a><?php render_review_prompt($pay, $u); ?></div></article>
    <?php endforeach; ?>
    </div>
</section>
