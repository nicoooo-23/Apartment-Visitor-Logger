<?php
require_once 'db.php';

// handle apartment insert
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apt = trim($_POST['apt']);
    $tenant = trim($_POST['tenant']);

    if (!empty($apt)) {
        $apt = $conn->real_escape_string($apt);
        $tenant = $conn->real_escape_string($tenant);

        $status = empty($tenant) ? 'vacant' : 'occupied';

        $conn->query("
            INSERT IGNORE INTO apartments (apartment_number, tenant_name, status)
            VALUES ('$apt', '$tenant', '$status')
        ");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apartments</title>
</head>
<body>

<h2>Add Apartment</h2>

<form method="POST">
    Apartment Number:
    <input type="text" name="apt" required><br><br>

    Tenant Name:
    <input type="text" name="tenant"><br><br>

    <button type="submit">Save</button>
</form>

<hr>

<h2>Apartment List</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Apartment</th>
    <th>Tenant</th>
    <th>Status</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM apartments");

while ($row = $result->fetch_assoc()):
?>
<tr>
    <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>
    <td><?php echo htmlspecialchars($row['tenant_name']); ?></td>
    <td><?php echo $row['status']; ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
