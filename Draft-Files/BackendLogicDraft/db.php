<?php
// Centralized database connection and initialization
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'apartment_db';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $db_name");

// Select database
$conn->select_db($db_name);

// Create apartments table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS apartments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apartment_number VARCHAR(50) UNIQUE NOT NULL,
    status ENUM('available', 'occupied') DEFAULT 'available',
    owner_name VARCHAR(255),
    owner_phone VARCHAR(20),
    owner_email VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Create visitors table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(255) NOT NULL,
    contact VARCHAR(50),
    purpose TEXT,
    apartment_number VARCHAR(50),
    visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create admin users table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
?>
