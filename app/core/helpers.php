<?php

function user_by_email(string $email): ?array
{
    $stmt = db()->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function current_user(): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $stmt = db()->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function require_user(): array
{
    $user = current_user();
    if (!$user) {
        redirect('?page=login');
    }
    return $user;
}

function redirect(string $to): void
{
    header("Location: $to");
    exit;
}

function h(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function notify(?int $userId, string $message): void
{
    db()->prepare("INSERT INTO notifications(user_id,message) VALUES(?,?)")->execute([$userId, $message]);
}

function flash(string $message): void
{
    $_SESSION['flash'] = $message;
}

function average_rating(int $userId): string
{
    $stmt = db()->prepare("SELECT AVG(rating) FROM reviews WHERE reviewed_id = ?");
    $stmt->execute([$userId]);
    $avg = $stmt->fetchColumn();
    return $avg ? number_format((float)$avg, 1) . '/5' : 'No reviews yet';
}

function load_with_people(int $id): ?array
{
    $stmt = db()->prepare("
        SELECT l.*, s.name shipper_name, s.photo shipper_photo,
               a.name accepted_name, a.role accepted_role, a.photo accepted_photo,
               d.name assigned_driver_name, d.photo assigned_driver_photo
        FROM loads l
        JOIN users s ON s.id = l.shipper_id
        LEFT JOIN users a ON a.id = l.accepted_by
        LEFT JOIN users d ON d.id = l.assigned_driver_id
        WHERE l.id = ?
    ");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function can_review(int $loadId, int $reviewerId, int $reviewedId): bool
{
    $stmt = db()->prepare("SELECT COUNT(*) FROM reviews WHERE load_id = ? AND reviewer_id = ? AND reviewed_id = ?");
    $stmt->execute([$loadId, $reviewerId, $reviewedId]);
    return (int)$stmt->fetchColumn() === 0;
}

function review_target(array $load, array $user): ?int
{
    if ((int)$user['id'] === (int)$load['shipper_id']) {
        return (int)($load['assigned_driver_id'] ?: $load['accepted_by']);
    }
    if (in_array((int)$user['id'], [(int)$load['accepted_by'], (int)$load['assigned_driver_id']], true)) {
        return (int)$load['shipper_id'];
    }
    return null;
}

function render_review_prompt(array $load, array $user): void
{
    if ($load['status'] !== 'paid') {
        return;
    }
    $target = review_target($load, $user);
    if ($target && can_review((int)$load['id'], (int)$user['id'], $target)) {
        echo '<a class="button secondary" href="?page=review&id=' . h($load['id']) . '">Review this shipment</a>';
    }
}

function payment_status_label(?string $status): string
{
    return match (trim((string)$status)) {
        'paid' => 'Payment released',
        'otp_sent' => 'Waiting for OTP confirmation',
        'ready_to_deliver' => 'Payment on hold',
        default => 'Payment not started',
    };
}

function payment_status_class(?string $status): string
{
    return match (trim((string)$status)) {
        'paid' => 'status-paid',
        'otp_sent' => 'status-waiting',
        'ready_to_deliver' => 'status-hold',
        default => 'status-open',
    };
}
