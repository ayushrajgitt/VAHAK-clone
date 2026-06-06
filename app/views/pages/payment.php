<?php $u = require_user(); $load = load_with_people((int)($_GET['id'] ?? 0)); ?>
<section class="panel payment">
    <p class="eyebrow">Payment confirmation</p>
    <h1>Load #<?= h($load['id']) ?> <?= h(payment_status_label($load['status'])) ?></h1>
    <p><?= $load['status'] === 'paid' ? 'The OTP matched successfully, so the fake payment has been released.' : 'Payment is locked until the driver delivers the shipment, clicks order shipped, and enters the OTP generated for the shipper.' ?></p>
    <p><strong>Status:</strong> <span class="status-pill <?= h(payment_status_class($load['status'])) ?>"><?= h(payment_status_label($load['status'])) ?></span> | <strong>Amount:</strong> Rs <?= h($load['price']) ?></p>
    <?php if (in_array($u['id'], [(int)$load['accepted_by'], (int)$load['assigned_driver_id']], true) && $load['status'] === 'ready_to_deliver'): ?>
        <form method="post"><input type="hidden" name="action" value="mark_shipped"><input type="hidden" name="load_id" value="<?= $load['id'] ?>"><button class="button">Order shipped</button></form>
    <?php endif; ?>
    <?php if (in_array($u['id'], [(int)$load['accepted_by'], (int)$load['assigned_driver_id']], true) && $load['status'] === 'otp_sent'): ?>
        <form method="post" class="form inline"><input type="hidden" name="action" value="verify_otp"><input type="hidden" name="load_id" value="<?= $load['id'] ?>"><label>Enter OTP<input name="otp" required></label><button class="button">Release payment</button></form>
    <?php endif; ?>
    <?php if ($u['id'] == $load['shipper_id'] && $load['status'] === 'otp_sent'): ?><p class="otp">In-app OTP: <?= h($load['otp']) ?></p><?php endif; ?>
    <?php render_review_prompt($load, $u); ?>
</section>
