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

// Lines starting from this point are to make sure the database exists even without importing an sql dump file
// Database will be created along with the tables needed (IF NOT EXISTS used to reduce errors if database already exists beforehand

// create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");

// select database
$conn->select_db($dbname);

// ==============================
// CREATE TENANTS TABLE
// ==============================
$conn->query("
    CREATE TABLE IF NOT EXISTS tenants (
        t_id INT AUTO_INCREMENT PRIMARY KEY,
        tenant_name VARCHAR(255) NOT NULL,
        tenant_email VARCHAR(255),
        tenant_phone VARCHAR(50)
    )
");

// ==============================
// CREATE APARTMENTS TABLE
// ==============================
$conn->query("
    CREATE TABLE IF NOT EXISTS apartments (
        apt_id INT AUTO_INCREMENT PRIMARY KEY,
        apartment_number VARCHAR(50) UNIQUE NOT NULL,
        tenant_id INT,
        status ENUM('occupied', 'vacant') DEFAULT 'vacant',
        FOREIGN KEY (tenant_id) 
            REFERENCES tenants(t_id)
            ON DELETE SET NULL
    )
");

// ================================
// CREATE VISITORS TABLE
// ================================
$conn->query("
    CREATE TABLE IF NOT EXISTS visitors (
        v_id INT AUTO_INCREMENT PRIMARY KEY,
        visitor_name VARCHAR(255) NOT NULL,
        contact VARCHAR(100),
        purpose TEXT,

        apartment_id INT NOT NULL,

        status ENUM('checked_in', 'checked_out') DEFAULT 'checked_in',
        visit_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        checkout_time TIMESTAMP NULL,

        FOREIGN KEY (apartment_id) 
            REFERENCES apartments(apt_id)
            ON DELETE RESTRICT
    )
");

// ==============================
// CREATE ADMIN USERS TABLE
// ==============================
$conn->query("
    CREATE TABLE IF NOT EXISTS admin_users (
        admin_id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )
");

// ==============================
// AUTO-CREATE DEFAULT ADMIN USER
// ==============================

// For testing purposes, default admin user is created (In actual deployment/implementation, this will be omitted)
// default admin credentials
$default_username = "admin";
$default_password = "admin123"; // change if you want

// check if any admin already exists
$checkAdmin = $conn->query("SELECT admin_id FROM admin_users LIMIT 1");

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
