<?php
session_start();
require_once '../includes/db.php';
require_once 'admin_includes/admin_header.php';

// protect page — only admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// -------------------------------
// HANDLE INSERT / UPDATE
// -------------------------------

// INSERT new apartment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $apt = trim($_POST['apt']);
    $tenant = trim($_POST['tenant']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    if (!empty($apt)) {
        $apt = $conn->real_escape_string($apt);
        $tenant_id = NULL;
        $status = 'vacant';

        if (!empty($tenant)) {
            $tenant = $conn->real_escape_string($tenant);
            $email = $conn->real_escape_string($email);
            $phone = $conn->real_escape_string($phone);

            $conn->query("INSERT INTO tenants (tenant_name, tenant_email, tenant_phone) VALUES ('$tenant', '$email', '$phone')");
            $tenant_id = $conn->insert_id;
            $status = 'occupied';
        }

        $conn->query("INSERT IGNORE INTO apartments (apartment_number, tenant_id, status) VALUES ('$apt', " . ($tenant_id ? $tenant_id : 'NULL') . ", '$status')");
    }
}

// UPDATE existing apartment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $tenant = trim($_POST['tenant']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Get current tenant_id
    $current = $conn->query("SELECT tenant_id FROM apartments WHERE apt_id = $id");
    $current_row = $current->fetch_assoc();
    $current_tenant_id = $current_row['tenant_id'];

    $tenant_id = $current_tenant_id;
    $status = 'vacant';

    if (!empty($tenant)) {
        $tenant = $conn->real_escape_string($tenant);
        $email = $conn->real_escape_string($email);
        $phone = $conn->real_escape_string($phone);

        if ($current_tenant_id) {
            // Update existing tenant
            $conn->query("UPDATE tenants SET tenant_name = '$tenant', tenant_email = '$email', tenant_phone = '$phone' WHERE t_id = $current_tenant_id");
        } else {
            // Insert new tenant
            $conn->query("INSERT INTO tenants (tenant_name, tenant_email, tenant_phone) VALUES ('$tenant', '$email', '$phone')");
            $tenant_id = $conn->insert_id;
        }
        $status = 'occupied';
    } else {
        // If tenant empty, set tenant_id to NULL
        $tenant_id = NULL;
    }

    $conn->query("UPDATE apartments SET tenant_id = " . ($tenant_id ? $tenant_id : 'NULL') . ", status = '$status' WHERE apt_id = $id");
}

// DELETE apartment
$delete_error = '';
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); // sanitize input
    
    // Check if apartment has any visitor records
    $check_visitors = $conn->query("SELECT COUNT(*) as visitor_count FROM visitors WHERE apartment_id = $id");
    $visitor_data = $check_visitors->fetch_assoc();
    
    if ($visitor_data['visitor_count'] > 0) {
        $delete_error = "Cannot delete apartment with existing visitor records. Please check out or remove all visitor records first.";
    } else {
        $conn->query("DELETE FROM apartments WHERE apt_id = $id");
    }
}

// -------------------------------
// FETCH ALL APARTMENTS
// -------------------------------
$apartments = [];
$result = $conn->query("SELECT a.apt_id as id, a.apartment_number, a.status, t.tenant_name, t.tenant_email, t.tenant_phone FROM apartments a LEFT JOIN tenants t ON a.tenant_id = t.t_id ORDER BY a.apartment_number ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $apartments[] = $row;
    }
}
?>

<!-- HTML START -->
<!DOCTYPE html>
<html>
<head>
    <title>Apartments</title>
</head>
<body>
<div class="container">
    <?php if (!empty($delete_error)): ?>
    <div style="color: red; padding: 10px; margin-bottom: 20px; border: 1px solid red; background-color: #ffe6e6; border-radius: 4px;">
        <strong>Error:</strong> <?php echo htmlspecialchars($delete_error); ?>
    </div>
    <?php endif; ?>
    
    <h1>Apartment Management</h1>
    <h2>Add Apartment</h2>

    <form method="POST">
        <input type="hidden" name="action" value="add">
        Apartment Number:
        <input type="text" name="apt" required><br><br>

        Tenant Name:
        <input type="text" name="tenant"><br><br>

        Tenant Email:
        <input type="email" name="email"><br><br>

        Tenant Phone:
        <input type="text" name="phone"><br><br>

        <button type="submit">Save</button>
    </form>

    <hr>

    <h2>Apartment List</h2>

    <table border="1" cellpadding="5">
    <tr>
        <th>Apartment</th>
        <th>Tenant</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($apartments as $row): ?>
    <tr>
        <!-- Display apartment info -->
        <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>

        <!-- Editable form for tenant, contact -->
        <form method="POST">
            <td>
                <input type="text" name="tenant" value="<?php echo htmlspecialchars($row['tenant_name'] ?? ''); ?>">
            </td>
            <td>
                <input type="email" name="email" value="<?php echo htmlspecialchars($row['tenant_email'] ?? ''); ?>">
            </td>
            <td>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($row['tenant_phone'] ?? ''); ?>">
            </td>
            <td>
                <?php echo htmlspecialchars($row['status']); ?>
            </td>
            <td>
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="action" value="edit">
                <div class="button-container">
                    <!-- Update button -->
                    <button type="submit">Update</button>
                    <!-- Delete button -->
                    <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this apartment?');">
                        Delete
                    </a>
                </div>
            </td>
        </form>
    </tr>
    <?php endforeach; ?>
    </table>
</div>

</body>
</html>