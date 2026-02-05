<?php
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apartment Management System</title>
</head>
<body>

<div class="container">
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

    <div class="card-container">
        <p class="card"><strong>Occupied Apartments: </strong> <?php echo $occupied; ?></p>
        <p class="card"><strong>Visitors Inside: </strong> <?php echo $visitors; ?></p>
    </div>
    <hr>
</div>

</body>
</html>
