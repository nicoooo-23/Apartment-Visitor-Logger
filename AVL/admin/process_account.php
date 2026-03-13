<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$action = $_POST['action'] ?? '';

// Fetch current admin record
$username = $_SESSION['admin_username'];
$stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

// ==============================
// CHANGE PASSWORD
// ==============================
if ($action === 'change_password') {

    $current  = trim($_POST['current_password']);
    $new      = trim($_POST['new_password']);
    $confirm  = trim($_POST['confirm_password']);

    if (empty($current) || empty($new) || empty($confirm)) {
        header("Location: account.php?error=empty");
        exit;
    }

    if (!password_verify($current, $admin['password'])) {
        header("Location: account.php?error=wrong");
        exit;
    }

    if (strlen($new) < 8) {
        header("Location: account.php?error=short");
        exit;
    }

    if ($new !== $confirm) {
        header("Location: account.php?error=mismatch");
        exit;
    }

    $hashed = password_hash($new, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed, $username);
    $stmt->execute();
    $stmt->close();

    header("Location: account.php?success=1");
    exit;
}

// ==============================
// DELETE ACCOUNT
// ==============================
if ($action === 'delete_account') {

    $current = trim($_POST['current_password']);

    if (!password_verify($current, $admin['password'])) {
        header("Location: account.php?error=wrong");
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM admin_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->close();

    session_destroy();
    header("Location: setup.php");
    exit;
}

header("Location: account.php");
exit;
?>