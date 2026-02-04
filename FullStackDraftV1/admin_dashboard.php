<?php
session_start();
require_once 'db.php';

// ==============================
// PROTECT ADMIN PAGE
// ==============================
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Welcome, <?php echo $_SESSION['admin_username']; ?></h1>

<ul>
    <li><a href="apartment.php">Manage Apartments</a></li>
    <li><a href="visitor.php">View Visitors</a></li>
    <li><a href="visitor_history.php">Visitor History</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>

</body>
</html>
