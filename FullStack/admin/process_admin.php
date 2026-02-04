<?php
session_start();
require_once '../includes/db.php';

// ==============================
// ADMIN LOGIN PROCESS
// ==============================

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (!empty($username) && !empty($password)) {

    $username = $conn->real_escape_string($username);

    $result = $conn->query(
        "SELECT * FROM admin_users WHERE username = '$username'"
    );

    if ($result && $result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        // verify hashed password
        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            header("Location: admin_dashboard.php");
            exit;
        }
    }
}

// if login fails
header("Location: admin_login.php?error=1");
exit;
?>