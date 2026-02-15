<?php
// Apartment Visitor Logger
require_once 'db.php'; // include database connection

// get apartments for dropdown
$apartments = [];
$apartment_query = "SELECT apartment_number FROM apartments ORDER BY apartment_number ASC"; //select all apts logged in db
$result = $conn->query($apartment_query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $apartments[] = $row['apartment_number']; // add to array
    }
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // get form inputs
    $visitor_name = trim($_POST['visitor_name']);
    $contact = trim($_POST['contact']);
    $purpose = trim($_POST['purpose']);
    $apartment_number = trim($_POST['apartment_number']);

    // basic validation
    if (!empty($visitor_name) && !empty($apartment_number)) {

        // escape inputs
        // prevent SQL injection
        // might destroy your database
        // most common web hacking techniques
        $visitor_name = $conn->real_escape_string($visitor_name);
        $contact = $conn->real_escape_string($contact);
        $purpose = $conn->real_escape_string($purpose);
        $apartment_number = $conn->real_escape_string($apartment_number);

        // insert visitor log
        $insert_sql = "INSERT INTO visitors (visitor_name, contact, purpose, apartment_number)
                       VALUES ('$visitor_name', '$contact', '$purpose', '$apartment_number')";

        if ($conn->query($insert_sql) === TRUE) {
            $success_message = "✓ Visitor logged successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }

    } else {
        $error_message = "Error: Visitor name and apartment number are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apartment Visitor Logger</title>
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
            <div class="status">Total Visitors: <span><?php echo mysqli_num_rows($conn->query("SELECT * FROM visitors WHERE status = 'checked_in'")) ?></span></div>
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