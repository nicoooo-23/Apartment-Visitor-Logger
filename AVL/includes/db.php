<?php
// ==============================
// DATABASE CONNECTION FILE
// This file is included by ALL pages
// so the database and tables are always ready
// ==============================

// database credentials
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "apartment_system";

// create connection
$conn = new mysqli($host, $user, $pass);

// check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

// select database
$conn->select_db($dbname);

// ==============================
// CREATE APARTMENTS TABLE
// ==============================
$conn->query("
    CREATE TABLE IF NOT EXISTS apartments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        apartment_number VARCHAR(50) UNIQUE NOT NULL,
        tenant_name VARCHAR(255),
        tenant_email VARCHAR(255),
        tenant_phone VARCHAR(50),
        status ENUM('occupied', 'vacant') DEFAULT 'vacant'
    )
");

// ==============================
// CREATE VISITORS TABLE
// ==============================
$conn->query("
    CREATE TABLE IF NOT EXISTS visitors (
        id INT AUTO_INCREMENT PRIMARY KEY,
        visitor_name VARCHAR(255) NOT NULL,
        contact VARCHAR(50),
        purpose TEXT,
        apartment_number VARCHAR(50),

        status ENUM('checked_in', 'checked_out') DEFAULT 'checked_in',

        visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        checkout_time TIMESTAMP NULL
    )
");

// ==============================
// CREATE ADMIN USERS TABLE
// ==============================
$conn->query("
    CREATE TABLE IF NOT EXISTS admin_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )
");

// ==============================
// AUTO-CREATE DEFAULT ADMIN USER
// ==============================

// default admin credentials
$default_username = "admin";
$default_password = "admin123"; // change if you want

// check if any admin already exists
$checkAdmin = $conn->query("SELECT id FROM admin_users LIMIT 1");

if ($checkAdmin->num_rows === 0) {

    // hash the default password
    $hashedPassword = password_hash($default_password, PASSWORD_DEFAULT);

    // insert default admin account
    $conn->query("
        INSERT INTO admin_users (username, password)
        VALUES ('$default_username', '$hashedPassword')
    ");
}

?>
