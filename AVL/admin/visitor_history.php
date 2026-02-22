<?php
session_start();
require_once '../includes/db.php';

// Protect page
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

require_once 'admin_includes/admin_header.php';

// Default values
$search = "";
$apartment = "";
$sort = "visit_time DESC";

// Get search input
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
}

// Get apartment filter
if (isset($_GET['apartment'])) {
    $apartment = $conn->real_escape_string($_GET['apartment']);
}

// Get sort option
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == "name") {
        $sort = "visitor_name ASC";
    } elseif ($_GET['sort'] == "status") {
        $sort = "status ASC";
    } elseif ($_GET['sort'] == "oldest") {
        $sort = "visit_time ASC";
    } else {
        $sort = "visit_time DESC";
    }
}

// Get apartment list for dropdown
$apartmentList = $conn->query("SELECT id, apartment_number 
                               FROM apartments 
                               ORDER BY apartment_number ASC");

// Build main query (JOIN)
$sql = "SELECT visitors.*, apartments.apartment_number
        FROM visitors
        JOIN apartments 
        ON visitors.apartment_id = apartments.id
        WHERE 1=1";

// Search filter
if (!empty($search)) {
    $sql .= " AND (visitor_name LIKE '%$search%' 
              OR purpose LIKE '%$search%')";
}

// Apartment filter
if (!empty($apartment)) {
    $sql .= " AND visitors.apartment_id = '$apartment'";
}

// Sorting
$sql .= " ORDER BY $sort";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor History</title>
</head>
<body>

<div class="container">
    <h1>Visitor History</h1>

    <!-- SEARCH + FILTER FORM -->
    <form method="GET">

        <input type="text" name="search"
               placeholder="Search name or purpose"
               value="<?php echo htmlspecialchars($search); ?>">

        <select name="apartment">
            <option value="">All Apartments</option>

            <?php while ($apt = $apartmentList->fetch_assoc()): ?>
                <option value="<?php echo $apt['id']; ?>"
                    <?php if ($apartment == $apt['id']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($apt['apartment_number']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="sort">
            <option value="latest" <?php if($sort=="visit_time DESC") echo "selected"; ?>>Latest</option>
            <option value="oldest" <?php if($sort=="visit_time ASC") echo "selected"; ?>>Oldest</option>
            <option value="name" <?php if($sort=="visitor_name ASC") echo "selected"; ?>>Name (A-Z)</option>
            <option value="status" <?php if($sort=="status ASC") echo "selected"; ?>>Status</option>
        </select>

        <button type="submit">Apply</button>
    </form>

    <br>

    <table border="1" cellpadding="5">
        <tr>
            <th>Name</th>
            <th>Apartment</th>
            <th>Purpose</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
        </tr>

        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['visitor_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                    <td><?php echo $row['visit_time']; ?></td>
                    <td><?php echo $row['checkout_time']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No visitor records found.</td>
            </tr>
        <?php endif; ?>

    </table>

</div>

</body>
</html>