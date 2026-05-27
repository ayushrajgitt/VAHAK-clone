<?php

function db(): PDO
{
    static $db = null;
    if ($db === null) {
        $dbPath = __DIR__ . '/../../data/vahak.sqlite';
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec('PRAGMA foreign_keys = ON');
    }
    return $db;
}

function init_db(PDO $db): void
{
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT NOT NULL CHECK(role IN ('shipper','driver','transporter','admin')),
            photo TEXT NOT NULL DEFAULT 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?auto=format&fit=crop&w=240&q=80',
            bio TEXT NOT NULL DEFAULT '',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS transporter_drivers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            transporter_id INTEGER NOT NULL,
            driver_id INTEGER NOT NULL,
            FOREIGN KEY(transporter_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(driver_id) REFERENCES users(id) ON DELETE CASCADE
        );
        CREATE TABLE IF NOT EXISTS loads (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            shipper_id INTEGER NOT NULL,
            title TEXT NOT NULL,
            pickup TEXT NOT NULL,
            destination TEXT NOT NULL,
            price INTEGER NOT NULL,
            weight TEXT NOT NULL,
            details TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'open',
            accepted_by INTEGER,
            transporter_id INTEGER,
            assigned_driver_id INTEGER,
            otp TEXT,
            rejected_drivers TEXT NOT NULL DEFAULT '[]',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(shipper_id) REFERENCES users(id) ON DELETE CASCADE
        );
        CREATE TABLE IF NOT EXISTS notifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER,
            message TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS reviews (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            load_id INTEGER NOT NULL,
            reviewer_id INTEGER NOT NULL,
            reviewed_id INTEGER NOT NULL,
            rating INTEGER NOT NULL,
            comment TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS support_tickets (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            question TEXT NOT NULL,
            answer TEXT,
            status TEXT NOT NULL DEFAULT 'open',
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            answered_at TEXT,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        );
    ");

    $count = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    if ($count > 0) {
        return;
    }

    $users = [
        ['Admin', 'admin@vahak.test', 'admin123', 'admin', 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=240&q=80', 'Platform administrator.'],
        ['Neha Shipper', 'shipper@vahak.test', 'shipper123', 'shipper', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=240&q=80', 'Ships packaged goods across North India.'],
        ['Ravi Driver', 'driver@vahak.test', 'driver123', 'driver', 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=240&q=80', 'Independent driver with refrigerated vehicle.'],
        ['Kiran Transporter', 'transporter@vahak.test', 'transport123', 'transporter', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=240&q=80', 'Fleet owner managing reliable regional drivers.'],
        ['Aman Fleet Driver', 'aman@vahak.test', 'driver123', 'driver', 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?auto=format&fit=crop&w=240&q=80', 'Transporter assigned driver, 4.5 ton truck.'],
        ['Sara Fleet Driver', 'sara@vahak.test', 'driver123', 'driver', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=240&q=80', 'Transporter assigned driver, container specialist.'],
    ];
    $stmt = $db->prepare("INSERT INTO users(name,email,password,role,photo,bio) VALUES(?,?,?,?,?,?)");
    foreach ($users as $u) {
        $stmt->execute([$u[0], $u[1], password_hash($u[2], PASSWORD_DEFAULT), $u[3], $u[4], $u[5]]);
    }

    $transporter = user_by_email('transporter@vahak.test');
    foreach (['aman@vahak.test', 'sara@vahak.test'] as $email) {
        $driver = user_by_email($email);
        $db->prepare("INSERT INTO transporter_drivers(transporter_id, driver_id) VALUES(?,?)")->execute([$transporter['id'], $driver['id']]);
    }

    $shipper = user_by_email('shipper@vahak.test');
    $load = $db->prepare("INSERT INTO loads(shipper_id,title,pickup,destination,price,weight,details) VALUES(?,?,?,?,?,?,?)");
    $load->execute([$shipper['id'], 'Electronics cartons', 'Delhi', 'Jaipur', 18000, '2.2 tons', 'Fragile cartons, covered vehicle required.']);
    $load->execute([$shipper['id'], 'Textile bundles', 'Surat', 'Mumbai', 12000, '1.6 tons', 'Pickup before 5 PM, unload at warehouse gate 3.']);
    notify(null, 'Seed data created with demo accounts and open shipments.');
}
