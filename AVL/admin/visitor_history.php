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
$apartmentList = $conn->query("SELECT apt_id, apartment_number 
                               FROM apartments 
                               ORDER BY apartment_number ASC");

// Build main query (JOIN)
$sql = "SELECT visitors.*, apartments.apartment_number
        FROM visitors
        JOIN apartments 
        ON visitors.apartment_id = apartments.apt_id
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


// CSV Export of Visitors
if (isset($_GET['export']) && $_GET['export'] == 'csv') {

    if (ob_get_length()) { ob_clean(); } // clears accidental output

    $exportResult = $conn->query($sql);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="visitor_history.csv"'); // Tells the browser to download the file with this filename

    $output = fopen("php://output", "w");

    // Add the column headers for the CSV file
    fputcsv($output, ["Name","Apartment","Purpose","Time In","Time Out","Status"]);

    if ($exportResult && $exportResult->num_rows > 0) {
        while ($row = $exportResult->fetch_assoc()) {
            fputcsv($output, [
                $row['visitor_name'],
                $row['apartment_number'],
                $row['purpose'],
                $row['visit_time'],
                $row['checkout_time'],
                $row['status']
            ]);
        }
    }

    fclose($output);
    exit;
}
$result = $conn->query($sql);
require_once 'admin_includes/admin_header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visitor History</title>
    <script>
        function exportCSV() {
            // Get current form values
            const search = document.querySelector('input[name="search"]').value;
            const apartment = document.querySelector('select[name="apartment"]').value;
            const sort = document.querySelector('select[name="sort"]').value;
            
            // Build URL with current parameters plus export=csv
            let url = window.location.pathname + '?export=csv';
            if (search) url += '&search=' + encodeURIComponent(search);
            if (apartment) url += '&apartment=' + encodeURIComponent(apartment);
            if (sort) url += '&sort=' + encodeURIComponent(sort);
            
            // Navigate to the export URL
            window.location.href = url;
        }
    </script>
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
                <option value="<?php echo $apt['apt_id']; ?>"
                    <?php if ($apartment == $apt['apt_id']) echo "selected"; ?>>
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

    <div class="export-btn-container">
        <button type="button" onclick="exportCSV()" class="export-btn">
            Export Records to CSV
        </button>
    </div>

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