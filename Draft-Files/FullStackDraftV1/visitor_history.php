<?php
session_start();
require_once 'db.php';
require_once 'header.php';

// protect page
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor History</title>
</head>
<body>

<h2>Visitor History</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Name</th>
    <th>Apartment</th>
    <th>Purpose</th>
    <th>Time In</th>
    <th>Time Out</th>
    <th>Status</th>
</tr>

<?php
$result = $conn->query(
    "SELECT * FROM visitors ORDER BY visit_time DESC"
);

while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?php echo htmlspecialchars($row['visitor_name']); ?></td>
    <td><?php echo $row['apartment_number']; ?></td>
    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
    <td><?php echo $row['visit_time']; ?></td>
    <td><?php echo $row['checkout_time']; ?></td>
    <td><?php echo $row['status']; ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
