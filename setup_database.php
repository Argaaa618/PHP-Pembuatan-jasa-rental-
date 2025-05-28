<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sewa_drone_db';

// Connect to MySQL server
$mysqli = new mysqli($host, $user, $pass);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!$mysqli->query($sql)) {
    die("Database creation failed: (" . $mysqli->errno . ") " . $mysqli->error);
}

// Select the database
if (!$mysqli->select_db($dbname)) {
    die("Failed to select database: (" . $mysqli->errno . ") " . $mysqli->error);
}

// Create users table if not exists
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$mysqli->query($sql)) {
    die("Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error);
}

echo "Database and users table setup completed successfully.";

$mysqli->close();
?>
