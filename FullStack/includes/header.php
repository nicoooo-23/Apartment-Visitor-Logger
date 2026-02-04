<?php
// start session for pages that need it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apartment Visitor Logger</title>
</head>
<body>

<hr>
<nav>
    <a href="index.php">Home</a> |
    <a href="visitor.php">Log Visitors</a> |
    <a href="admin/admin_dashboard.php">Admin</a>
</nav>
<hr>
