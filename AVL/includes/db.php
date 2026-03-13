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
?>