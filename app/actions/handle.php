<?php

if ($action === 'signup') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    if (user_by_email($email)) {
        flash('This Gmail/email is already registered with one role. Please log in with that account.');
        redirect('?page=signup');
    }
    $role = $_POST['role'] ?? '';
    if (!in_array($role, ['shipper', 'driver', 'transporter'], true)) {
        flash('Choose a valid account type.');
        redirect('?page=signup');
    }
    db()->prepare("INSERT INTO users(name,email,password,role,photo,bio) VALUES(?,?,?,?,?,?)")
        ->execute([
            trim($_POST['name']),
            $email,
            password_hash($_POST['password'], PASSWORD_DEFAULT),
            $role,
            'https://images.unsplash.com/photo-1633332755192-727a05c4013d?auto=format&fit=crop&w=240&q=80',
            'New Vahak member.'
        ]);
    $_SESSION['user_id'] = (int)db()->lastInsertId();
    notify(null, ucfirst($role) . " signed up: " . trim($_POST['name']));
    redirect('?page=dashboard');
}

if ($action === 'login') {
    $user = user_by_email(strtolower(trim($_POST['email'] ?? '')));
    if (!$user || !password_verify($_POST['password'] ?? '', $user['password'])) {
        flash('Invalid login details.');
        redirect('?page=login');
    }
    $_SESSION['user_id'] = $user['id'];
    redirect('?page=dashboard');
}

if ($action === 'logout') {
    session_destroy();
    redirect('?page=home');
}

if ($action === 'add_load') {
    $u = require_user();
    if ($u['role'] !== 'shipper') {
        redirect('?page=dashboard');
    }
    db()->prepare("INSERT INTO loads(shipper_id,title,pickup,destination,price,weight,details) VALUES(?,?,?,?,?,?,?)")
        ->execute([$u['id'], $_POST['title'], $_POST['pickup'], $_POST['destination'], (int)$_POST['price'], $_POST['weight'], $_POST['details']]);
    notify(null, "New load posted by {$u['name']}: {$_POST['title']}");
    flash('Load posted and visible to drivers and transporters.');
    redirect('?page=dashboard');
}

if ($action === 'accept_load') {
    $u = require_user();
    $load = load_with_people((int)$_POST['load_id']);
    $rejected = json_decode($load['rejected_drivers'], true) ?: [];
    if (!$load || $load['status'] !== 'open' || in_array($u['id'], $rejected, true)) {
        flash('This load is not available for you.');
        redirect('?page=loads');
    }
    if ($u['role'] === 'driver') {
        db()->prepare("UPDATE loads SET status='pending_shipper', accepted_by=?, assigned_driver_id=? WHERE id=?")
            ->execute([$u['id'], $u['id'], $load['id']]);
        notify($load['shipper_id'], "{$u['name']} accepted your load. Review their profile and confirm or reject.");
        notify($u['id'], "You accepted load #{$load['id']}. Waiting for shipper confirmation.");
    } elseif ($u['role'] === 'transporter') {
        db()->prepare("UPDATE loads SET status='pending_shipper', accepted_by=?, transporter_id=?, assigned_driver_id=? WHERE id=?")
            ->execute([$u['id'], $u['id'], (int)$_POST['assigned_driver_id'], $load['id']]);
        notify($load['shipper_id'], "{$u['name']} accepted your load and assigned a driver. Review and confirm.");
        notify($u['id'], "You accepted load #{$load['id']}. Waiting for shipper confirmation.");
    }
    notify(null, "{$u['name']} accepted load #{$load['id']}.");
    redirect('?page=load&id=' . $load['id']);
}

if ($action === 'shipper_decision') {
    $u = require_user();
    $load = load_with_people((int)$_POST['load_id']);
    if (!$load || $load['shipper_id'] != $u['id']) {
        redirect('?page=dashboard');
    }
    if ($_POST['decision'] === 'confirm') {
        db()->prepare("UPDATE loads SET status='ready_to_deliver' WHERE id=?")->execute([$load['id']]);
        notify($load['accepted_by'], "Shipper confirmed load #{$load['id']}. Shipment is ready to be delivered.");
        if (!empty($load['assigned_driver_id']) && (int)$load['assigned_driver_id'] !== (int)$load['accepted_by']) {
            notify((int)$load['assigned_driver_id'], "You were assigned to load #{$load['id']}. Shipment is ready to be delivered.");
        }
        notify($u['id'], "You confirmed load #{$load['id']}. Shipment is ready to be delivered.");
        notify(null, "Load #{$load['id']} confirmed for delivery.");
        redirect('?page=payment&id=' . $load['id']);
    }
    $rejected = json_decode($load['rejected_drivers'], true) ?: [];
    $rejected[] = (int)$load['accepted_by'];
    if (!empty($load['assigned_driver_id'])) {
        $rejected[] = (int)$load['assigned_driver_id'];
    }
    db()->prepare("UPDATE loads SET status='open', accepted_by=NULL, transporter_id=NULL, assigned_driver_id=NULL, rejected_drivers=? WHERE id=?")
        ->execute([json_encode(array_values(array_unique($rejected))), $load['id']]);
    notify($load['accepted_by'], "Shipper rejected your acceptance for load #{$load['id']}. You cannot accept this load again.");
    notify(null, "Load #{$load['id']} was reopened after shipper rejection.");
    redirect('?page=dashboard');
}

if ($action === 'mark_shipped') {
    $u = require_user();
    $load = load_with_people((int)$_POST['load_id']);
    $otp = (string)random_int(100000, 999999);
    db()->prepare("UPDATE loads SET status='otp_sent', otp=? WHERE id=?")->execute([$otp, $load['id']]);
    notify($load['shipper_id'], "OTP for load #{$load['id']} is {$otp}. Share it with the driver only after delivery.");
    notify($u['id'], "OTP request sent to shipper for load #{$load['id']}.");
    redirect('?page=payment&id=' . $load['id']);
}

if ($action === 'verify_otp') {
    $u = require_user();
    $load = load_with_people((int)$_POST['load_id']);
    if ($load && hash_equals($load['otp'] ?? '', trim($_POST['otp'] ?? ''))) {
        db()->prepare("UPDATE loads SET status='paid' WHERE id=?")->execute([$load['id']]);
        notify($load['shipper_id'], "Load #{$load['id']} delivered. Fake payment released after OTP confirmation.");
        notify($load['accepted_by'], "Payment released for load #{$load['id']}.");
        if (!empty($load['assigned_driver_id']) && (int)$load['assigned_driver_id'] !== (int)$load['accepted_by']) {
            notify((int)$load['assigned_driver_id'], "Payment released for load #{$load['id']}.");
        }
        notify(null, "Load #{$load['id']} completed and fake payment released.");
        redirect('?page=review&id=' . $load['id']);
    }
    flash('OTP did not match. Try again.');
    redirect('?page=payment&id=' . $load['id']);
}

if ($action === 'review') {
    $u = require_user();
    if (can_review((int)$_POST['load_id'], $u['id'], (int)$_POST['reviewed_id'])) {
        db()->prepare("INSERT INTO reviews(load_id,reviewer_id,reviewed_id,rating,comment) VALUES(?,?,?,?,?)")
            ->execute([(int)$_POST['load_id'], $u['id'], (int)$_POST['reviewed_id'], (int)$_POST['rating'], trim($_POST['comment'])]);
        notify((int)$_POST['reviewed_id'], "{$u['name']} reviewed your completed shipment.");
    }
    redirect('?page=profile&id=' . (int)$_POST['reviewed_id']);
}

if ($action === 'update_profile') {
    $u = require_user();
    db()->prepare("UPDATE users SET name=?, photo=?, bio=? WHERE id=?")
        ->execute([trim($_POST['name']), trim($_POST['photo']), trim($_POST['bio']), $u['id']]);
    notify(null, "{$u['name']} updated their profile.");
    flash('Profile updated.');
    redirect('?page=profile&id=' . $u['id']);
}

if ($action === 'support_question') {
    $u = require_user();
    if (in_array($u['role'], ['shipper', 'driver', 'transporter'], true)) {
        db()->prepare("INSERT INTO support_tickets(user_id,question) VALUES(?,?)")->execute([$u['id'], trim($_POST['question'])]);
        notify(null, "{$u['name']} asked a support question.");
        flash('Your question was sent to admin support.');
    }
    redirect('?page=support');
}

if ($action === 'admin_answer_support') {
    $u = require_user();
    if ($u['role'] === 'admin') {
        db()->prepare("UPDATE support_tickets SET answer=?, status='answered', answered_at=CURRENT_TIMESTAMP WHERE id=?")
            ->execute([trim($_POST['answer']), (int)$_POST['ticket_id']]);
        $stmt = db()->prepare("SELECT user_id FROM support_tickets WHERE id=?");
        $stmt->execute([(int)$_POST['ticket_id']]);
        $ticketUser = (int)$stmt->fetchColumn();
        notify($ticketUser, 'Admin answered your support question.');
        notify(null, "Admin answered support ticket #{$_POST['ticket_id']}.");
    }
    redirect('?page=admin');
}

if ($action === 'admin_delete_load') {
    $u = require_user();
    if ($u['role'] === 'admin') {
        db()->prepare("DELETE FROM loads WHERE id=?")->execute([(int)$_POST['load_id']]);
        notify(null, "Admin deleted load #{$_POST['load_id']}.");
    }
    redirect('?page=admin');
}

if ($action === 'admin_delete_user') {
    $u = require_user();
    if ($u['role'] === 'admin' && (int)$_POST['user_id'] !== (int)$u['id']) {
        db()->prepare("DELETE FROM users WHERE id=?")->execute([(int)$_POST['user_id']]);
        notify(null, "Admin deleted user #{$_POST['user_id']}.");
    }
    redirect('?page=admin');
}
