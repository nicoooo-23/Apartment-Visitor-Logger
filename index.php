<?php
// connect mysql
$databaseHost = 'localhost';
$databaseUsername = 'root';
$databasePassword = '';
$databaseName = 'apartment_db';

// connection
$connection = mysqli_connect($databaseHost, $databaseUsername, $databasePassword);

// check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// create database (use if not exists to avoid error if it already exists)
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
// query to create database (I think you're supposed to pass the connection then the query)
mysqli_query($connection, "CREATE DATABASE IF NOT EXISTS $databaseName");
// query to select database (same thing here)
mysqli_select_db($connection, $databaseName);

// create table
$visitorTableQuery = "CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    apartment VARCHAR(50),
    reason_for_visit VARCHAR(255),
    time_in TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    time_out TIMESTAMP NULL,
    status ENUM('checked_in', 'checked_out') DEFAULT 'checked_in'
)";
// execute query to create table
mysqli_query($connection, $visitorTableQuery);

// form submission handling
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'check_out' && isset($_POST['visitor_id'])) {
        // for checking out
        $visitorId = intval($_POST['visitor_id']);
        // query to update status in table
        $checkOutQuery = "UPDATE visitors SET time_out = NOW(), status = 'checked_out' WHERE id = $visitorId";
        // exec query
        mysqli_query($connection, $checkOutQuery);
    } elseif (isset($_POST['visitor_name'])) {
        // log new
        $name = mysqli_real_escape_string($connection, $_POST['visitor_name']);
        $phone = mysqli_real_escape_string($connection, $_POST['visitor_phone']);
        $apartment = mysqli_real_escape_string($connection, $_POST['visitor_apartment']);
        $reason = mysqli_real_escape_string($connection, $_POST['reason_for_visit']);

        if (!empty($name) && !empty($apartment)) {
            // query to insert new visitor
            $insertQuery = "INSERT INTO visitors (name, phone, apartment, reason_for_visit) VALUES ('$name', '$phone', '$apartment', '$reason')";
            // exec query
            mysqli_query($connection, $insertQuery);

            if(mysqli_affected_rows($connection) > 0){
                // success
                echo "<script>alert('Visitor logged successfully.');</script>";
            } else {
                // failure
                echo "<script>alert('Error logging visitor. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('Name and Apartment are required fields.');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Visitor Logger</title>
    <style>

    </style>
</head>
<body>
    <!-- navigation bar -->
    <nav>
        <div class="nav-title">Apartment Management System</div>
        <div class="nav-links">
            <a href="index.php">Log Visitors (Security)</a>
            <a href="apartments.php">Manage Apartments (Admin)</a>
        </div>
    </nav>

    <div class="main-content">
        <!-- header -->
        <div class="container">
            <h1>Visitor Logger</h1>
            <div class="status">Total Visitors: <span id="count"><?php echo mysqli_num_rows(mysqli_query($connection, "SELECT * FROM visitors WHERE status = 'checked_in'")); ?></span></div>
        </div>

        <!-- form area -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="visitor_name">Visitor Name:</label>
                <input 
                    type="text" 
                    id="visitor_name" 
                    name="visitor_name" 
                    placeholder="Enter your name" 
                    required
                    autofocus
                >
            </div>
            <div class="form-group">
                <label for="visitor_phone">Phone (Optional):</label>
                <input 
                    type="tel" 
                    id="visitor_phone" 
                    name="visitor_phone" 
                    placeholder="Enter your phone number"
                >
            </div>
            <div class="form-group">
                <label for="visitor_apartment">Apartment:</label>
                <select 
                    id="visitor_apartment" 
                    name="visitor_apartment" 
                    required
                >
                    <option value="">-- Select Apartment --</option>
                    <!-- only show occupied apartments -->
                    <?php foreach ($apartments as $apt): ?>
                        <option value="<?php echo htmlspecialchars($apt); ?>">Apartment <?php echo htmlspecialchars($apt); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="reason_for_visit">Reason for Visit:</label>
                <textarea 
                    id="reason_for_visit" 
                    name="reason_for_visit" 
                    placeholder="Enter reason for visit (e.g., Delivery, Meeting, Friend visit, Repair, etc.)"
                    rows="3"
                ></textarea>
            </div>
            <button type="submit">Log Visitor</button>
        </form>
    </div>
</body>
</html>