<?php
session_start();
require_once '../includes/db.php';

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
<p>This is the admin dashboard.</p>
<p>
    <a href="../index.php">Home</a> |
    <a href="../admin/apartment.php">Manage Apartments</a> | 
    <a href="../admin/visitor_history.php">View Visitor History</a> | 
    <a href="../admin/logout.php" style="color:red;">Logout</a>
</p>

</body>
</html>
