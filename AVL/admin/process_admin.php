<?php
session_start();
require_once '../includes/db.php';

// ==============================
// ADMIN LOGIN PROCESS
// ==============================

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (!empty($username) && !empty($password)) {

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        // verify hashed password
        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;

            $stmt->close();
            header("Location: admin_dashboard.php");
            exit;
        }
    }

    $stmt->close();
}

// if login fails
header("Location: admin_login.php?error=1");
exit;
?>