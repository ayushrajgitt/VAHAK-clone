<?php

function render_load_cards(array $u, bool $market = false): void
{
    if ($u['role'] === 'shipper') {
        $stmt = db()->prepare("SELECT * FROM loads WHERE shipper_id=? ORDER BY id DESC");
        $stmt->execute([$u['id']]);
    } elseif ($market) {
        $stmt = db()->query("SELECT * FROM loads WHERE status='open' ORDER BY id DESC");
    } else {
        $stmt = db()->prepare("SELECT * FROM loads WHERE accepted_by=? OR assigned_driver_id=? ORDER BY id DESC");
        $stmt->execute([$u['id'], $u['id']]);
    }
    echo '<div class="grid two">';
    foreach ($stmt as $load) {
        echo '<article class="load"><h3>' . h($load['title']) . '</h3><p>' . h($load['pickup']) . ' to ' . h($load['destination']) . '</p><p><strong>Rs ' . h($load['price']) . '</strong> | ' . h($load['weight']) . ' | ' . h($load['status']) . '</p><a class="button secondary" href="?page=load&id=' . h($load['id']) . '">Open order</a></article>';
    }
    echo '</div>';
}

function render_load_detail(array $load, array $u): void
{
    $rejected = json_decode($load['rejected_drivers'], true) ?: [];
    echo '<section class="panel"><p class="eyebrow">Order #' . h($load['id']) . '</p><h1>' . h($load['title']) . '</h1><p>' . h($load['pickup']) . ' to ' . h($load['destination']) . '</p><p>' . h($load['details']) . '</p><p><strong>Rs ' . h($load['price']) . '</strong> | ' . h($load['status']) . '</p>';
    echo '<div class="actions"><a class="button secondary" href="?page=profile&id=' . h($load['shipper_id']) . '">View shipper profile</a>';
    if ($load['accepted_by']) {
        echo '<a class="button secondary" href="?page=profile&id=' . h($load['accepted_by']) . '">View accepter profile</a>';
    }
    echo '</div>';
    if ($load['status'] === 'open' && in_array($u['role'], ['driver', 'transporter'], true) && !in_array($u['id'], $rejected, true)) {
        echo '<form method="post" class="form inline"><input type="hidden" name="action" value="accept_load"><input type="hidden" name="load_id" value="' . h($load['id']) . '">';
        if ($u['role'] === 'transporter') {
            echo '<label>Assign driver<select name="assigned_driver_id">';
            $drivers = db()->prepare("SELECT u.* FROM users u JOIN transporter_drivers td ON td.driver_id=u.id WHERE td.transporter_id=?");
            $drivers->execute([$u['id']]);
            foreach ($drivers as $d) {
                echo '<option value="' . h($d['id']) . '">' . h($d['name']) . '</option>';
            }
            echo '</select></label>';
        }
        echo '<button class="button">Accept order</button></form>';
    }
    if ($u['id'] == $load['shipper_id'] && $load['status'] === 'pending_shipper') {
        echo '<form method="post" class="actions"><input type="hidden" name="action" value="shipper_decision"><input type="hidden" name="load_id" value="' . h($load['id']) . '"><button class="button" name="decision" value="confirm">Confirm driver</button><button class="danger" name="decision" value="reject">Reject driver</button></form>';
    }
    if (in_array($load['status'], ['ready_to_deliver', 'otp_sent', 'paid'], true)) {
        echo '<a class="button" href="?page=payment&id=' . h($load['id']) . '">Payment confirmation</a>';
    }
    render_review_prompt($load, $u);
    echo '</section>';
}
