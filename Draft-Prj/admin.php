<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php 
    $pageTitle = "Apartment Visitor Management"; 
    include 'header.php'; 
?>

<div class="container">

    <div class="card">
        <h3>Visitor Log</h3>
        <input type="text" placeholder="Search visitors...">
        <table></table>
    </div>

    <div class="card">
        <h3>Register New Apartment</h3>

        <div class="grid-2">
            <div class="input-group">
                <label>Apartment Number</label>
                <input type="text">
            </div>

            <div class="input-group">
                <label>Status</label>
                <input type="text">
            </div>
        </div>

        <div class="grid-2">
            <div class="input-group">
                <label>Owner Name</label>
                <input type="text">
            </div>

            <div class="input-group">
                <label>Owner Phone Number</label>
                <input type="text">
            </div>
        </div>

        <div class="input-group">
            <label>Owner Email</label>
            <input type="email">
        </div>

        <button class="btn-primary">Add Apartment</button>
    </div>

</div>

</body>
</html>
