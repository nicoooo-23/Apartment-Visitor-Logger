<?php
require_once 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor Log</title>
</head>
<body>

<h2>Visitor Check-In</h2>

<form action="process_visitor.php" method="POST">
    <!-- identifies this request as CHECK-IN -->
    <input type="hidden" name="check_in" value="1">

    Visitor Name:
    <input type="text" name="visitor_name" required><br><br>

    Contact:
    <input type="text" name="phone"><br><br>

    Apartment:
    <select name="apt" required>
        <option value="">Select Apartment</option>
        <?php
        // only occupied apartments should receive visitors
        $apts = $conn->query(
            "SELECT apartment_number FROM apartments WHERE status = 'occupied'"
        );

        while ($a = $apts->fetch_assoc()):
        ?>
            <option value="<?php echo $a['apartment_number']; ?>">
                <?php echo $a['apartment_number']; ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    Purpose:
    <textarea name="purpose"></textarea><br><br>

    <button type="submit">Check In</button>
</form>

<hr>

<h2>Visitor Check-Out</h2>

<form action="process_visitor.php" method="POST">
    <!-- identifies this request as CHECK-OUT -->
    <button type="submit" name="check_out">
        Check Out Latest Visitor
    </button>
</form>

<hr>

<h2>Currently Inside</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Name</th>
    <th>Apartment</th>
    <th>Time In</th>
</tr>

<?php
$inside = $conn->query(
    "SELECT * FROM visitors WHERE status = 'checked_in'"
);

while ($v = $inside->fetch_assoc()):
?>
<tr>
    <td><?php echo htmlspecialchars($v['visitor_name']); ?></td>
    <td><?php echo $v['apartment_number']; ?></td>
    <td><?php echo $v['visit_time']; ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
