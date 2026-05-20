<?php
$host = "localhost";
$username = "root";
$password = "root123";
$database = "vahak_db";
$port = 3306;

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for proper string handling
$conn->set_charset("utf8mb4");
?>