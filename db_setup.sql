-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS vahak_db;
USE vahak_db;

-- 1. Users table (Handles all 4 roles)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL UNIQUE,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'driver', 'transporter', 'admin') NOT NULL,
    status ENUM('active', 'inactive', 'blocked') DEFAULT 'active',
    otp_code VARCHAR(10) DEFAULT NULL,
    otp_expires_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Transporter Profiles
CREATE TABLE IF NOT EXISTS transporter_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    gst_number VARCHAR(15) UNIQUE,
    pan_number VARCHAR(10) UNIQUE,
    fleet_size INT DEFAULT 0,
    address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 3. Vehicles (Owned by drivers or transporters)
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id INT NOT NULL, -- Links to users.id (driver or transporter)
    type VARCHAR(50) NOT NULL, -- e.g., Open Half Body, Container
    capacity DECIMAL(10,2) NOT NULL, -- in tons
    number_plate VARCHAR(20) NOT NULL UNIQUE,
    status ENUM('available', 'on_trip', 'maintenance') DEFAULT 'available',
    insurance_expiry DATE,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Driver-Transporter Link (Drivers working under a transporter)
CREATE TABLE IF NOT EXISTS driver_transporter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    transporter_id INT NOT NULL,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (transporter_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 5. Shipments (Loads)
CREATE TABLE IF NOT EXISTS shipments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    driver_id INT DEFAULT NULL,
    transporter_id INT DEFAULT NULL,
    vehicle_id INT DEFAULT NULL,
    pickup_city VARCHAR(100) NOT NULL,
    drop_city VARCHAR(100) NOT NULL,
    goods_type VARCHAR(100) NOT NULL,
    weight DECIMAL(10,2) NOT NULL, -- in tons
    truck_type VARCHAR(50) NOT NULL,
    status ENUM('pending', 'active', 'in_transit', 'delivered', 'cancelled') DEFAULT 'pending',
    price DECIMAL(10,2) DEFAULT NULL,
    pickup_date DATE,
    delivery_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (transporter_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL
);

-- 6. Bids (Drivers/Transporters bidding on pending shipments)
CREATE TABLE IF NOT EXISTS bids (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    bidder_id INT NOT NULL, -- user_id of driver or transporter
    amount DECIMAL(10,2) NOT NULL,
    message TEXT,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE,
    FOREIGN KEY (bidder_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 7. Payments
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    method ENUM('card', 'upi', 'bank_transfer', 'cash') NOT NULL,
    status ENUM('pending', 'paid', 'settled', 'failed') DEFAULT 'pending',
    paid_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);

-- 8. Ratings
CREATE TABLE IF NOT EXISTS ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    shipment_id INT NOT NULL,
    from_user_id INT NOT NULL,
    to_user_id INT NOT NULL,
    rating INT NOT NULL CHECK(rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE,
    FOREIGN KEY (from_user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (to_user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 9. Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 10. Messages (Chat)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    shipment_id INT DEFAULT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE
);

-- 11. Routes (Popular routes data)
CREATE TABLE IF NOT EXISTS routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    from_city VARCHAR(100) NOT NULL,
    to_city VARCHAR(100) NOT NULL,
    distance_km DECIMAL(10,2) NOT NULL,
    estimated_price DECIMAL(10,2),
    popularity INT DEFAULT 0
);

-- ==========================================
-- INSERT DUMMY DATA
-- Passwords are hashed versions of '123456'
-- ==========================================

-- Insert Admin
INSERT INTO users (name, phone, email, password, role) VALUES 
('Admin User', '9999999999', 'admin@vahak.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert Customer
INSERT INTO users (name, phone, email, password, role) VALUES 
('Rahul Sharma', '9876543210', 'customer@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer');

-- Insert Driver
INSERT INTO users (name, phone, email, password, role) VALUES 
('Rakesh Kumar', '9876543211', 'driver@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'driver');

-- Insert Transporter
INSERT INTO users (name, phone, email, password, role) VALUES 
('Balaji Logistics', '9876543212', 'transporter@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'transporter');

-- Insert Transporter Profile
INSERT INTO transporter_profiles (user_id, company_name, gst_number, pan_number, fleet_size, address) VALUES
(4, 'Balaji Logistics Pvt Ltd', '22AAAAA0000A1Z5', 'AAAAA0000A', 5, 'Navi Mumbai, Maharashtra');

-- Insert Vehicles
INSERT INTO vehicles (owner_id, type, capacity, number_plate, status) VALUES
(3, 'Open Half Body', 9.00, 'MH 04 AB 1234', 'available'),
(4, 'Container', 14.00, 'MH 43 XY 9876', 'on_trip');

-- Insert Shipment
INSERT INTO shipments (customer_id, pickup_city, drop_city, goods_type, weight, truck_type, status) VALUES
(2, 'Mumbai', 'Delhi', 'Electronics', 5.00, 'Container', 'pending');
