<?php
session_start();
require_once '../includes/db.php';
require_once 'admin_includes/admin_header.php';

// ==============================
// PROTECT ADMIN PAGE
// ==============================
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// ==============================
// SUMMARY QUERIES
// ==============================
// Total apartments
$apartment_count = 0;
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM apartments");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $apartment_count = $row['total'];
}

// Current visitors (not checked out)
$current_visitors = 0;
$result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM visitors WHERE checkout_time IS NULL");
if ($result && $row = mysqli_fetch_assoc($result)) {
    $current_visitors = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>Welcome, <?php echo $_SESSION['admin_username']; ?></h2>
        <p>This is the admin dashboard.</p>
        <br>
        <hr>
        <!-- Summary Section -->
        <div class="summary">
            <h2>Summary</h2>
            <div class="card-container">
                <div class="card">
                    <h3>Apartments Overview</h3>
                    <!-- Display apartment info -->
                    <table>
                        <tr>
                            <th>Apartment Number</th>
                            <th>Tenant Name</th>
                        </tr>
                        <?php 
                        // -------------------------------
                        // FETCH 3 APARTMENTS
                        // -------------------------------
                        $apartments = [];
                        $result = $conn->query("SELECT * FROM apartments ORDER BY apartment_number ASC LIMIT 3");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $apartments[] = $row;
                            }
                        }
                        foreach ($apartments as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['tenant_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="card">
                    <h3>Visitor History Overview</h3>
                    <table>
                        <tr>
                            <th>Visitor ID</th>
                            <th>Visitor Name</th>
                            <th>Apartment Number</th>
                        </tr>
                        <?php
                        // -------------------------------
                        // FETCH VISITOR HISTORY
                        // -------------------------------
                        $visitor_history = [];
                        $result = $conn->query("SELECT * FROM visitors ORDER BY visit_time DESC LIMIT 3");
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                $visitor_history[] = $row;
                            }
                        }
                        foreach ($visitor_history as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['visitor_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
