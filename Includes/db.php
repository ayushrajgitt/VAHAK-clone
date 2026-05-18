<?php

// connect project to database

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "vahak_db",
    3307
);

// check database connection

if (!$conn) {
    die("Database connection failed");
}

?>