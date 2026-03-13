<?php
session_start();
require_once '../includes/db.php';

// Block if account already exists
$check = $conn->query("SELECT admin_id FROM admin_users LIMIT 1");
if ($check->num_rows > 0) {
    header("Location: admin_login.php");
    exit;
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);
$confirm  = trim($_POST['confirm_password']);

// Validation
if (empty($username) || empty($password) || empty($confirm)) {
    header("Location: setup.php?error=empty");
    exit;
}

if (strlen($password) < 8) {
    header("Location: setup.php?error=short");
    exit;
}

if ($password !== $confirm) {
    header("Location: setup.php?error=mismatch");
    exit;
}

// Insert account
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO admin_users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed);
$stmt->execute();
$stmt->close();

header("Location: admin_login.php?setup=1");
exit;
?>