<?php
// admin access guard
session_start();

// if not logged in as admin, block access
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
