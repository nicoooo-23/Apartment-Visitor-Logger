<?php
require_once 'db.php';
require_once 'header.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apartment Management System</title>
</head>
<body>

<h1>Dashboard</h1>

<?php
// count occupied apartments
$occupied = $conn->query(
    "SELECT * FROM apartments WHERE status = 'occupied'"
)->num_rows;

// count active visitors
$visitors = $conn->query(
    "SELECT * FROM visitors WHERE status = 'checked_in'"
)->num_rows;
?>

<p><strong>Occupied Apartments:</strong> <?php echo $occupied; ?></p>
<p><strong>Visitors Inside:</strong> <?php echo $visitors; ?></p>

<hr>

<a href="apartment.php">Manage Apartments</a><br>
<a href="visitor.php">Visitor Log</a>

</body>
</html>
