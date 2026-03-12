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
        $tenant_id = NULL;
        $status = 'vacant';

        if (!empty($tenant)) {
            $stmt = $conn->prepare("INSERT INTO tenants (tenant_name, tenant_email, tenant_phone) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $tenant, $email, $phone);
            $stmt->execute();
            $tenant_id = $conn->insert_id;
            $stmt->close();
            $status = 'occupied';
        }

        $stmt = $conn->prepare("INSERT IGNORE INTO apartments (apartment_number, tenant_id, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $apt, $tenant_id, $status);
        $stmt->execute();
        $stmt->close();
    }
}

// UPDATE existing apartment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = intval($_POST['id']);
    $tenant = trim($_POST['tenant']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // Get current tenant_id
    $stmt = $conn->prepare("SELECT tenant_id FROM apartments WHERE apt_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $current = $stmt->get_result()->fetch_assoc();
    $current_tenant_id = $current['tenant_id'];
    $stmt->close();

    $tenant_id = $current_tenant_id;
    $status = 'vacant';

    if (!empty($tenant)) {
        if ($current_tenant_id) {
            // Update existing tenant
            $stmt = $conn->prepare("UPDATE tenants SET tenant_name = ?, tenant_email = ?, tenant_phone = ? WHERE t_id = ?");
            $stmt->bind_param("sssi", $tenant, $email, $phone, $current_tenant_id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insert new tenant
            $stmt = $conn->prepare("INSERT INTO tenants (tenant_name, tenant_email, tenant_phone) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $tenant, $email, $phone);
            $stmt->execute();
            $tenant_id = $conn->insert_id;
            $stmt->close();
        }
        $status = 'occupied';
    } else {
        // If tenant empty, set tenant_id to NULL
        $tenant_id = NULL;
    }

    $stmt = $conn->prepare("UPDATE apartments SET tenant_id = ?, status = ? WHERE apt_id = ?");
    $stmt->bind_param("isi", $tenant_id, $status, $id);
    $stmt->execute();
    $stmt->close();
}

// DELETE apartment
$delete_error = '';
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Check if apartment has any visitor records
    $stmt = $conn->prepare("SELECT COUNT(*) as visitor_count FROM visitors WHERE apartment_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $visitor_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($visitor_data['visitor_count'] > 0) {
        $delete_error = "Cannot delete apartment with existing visitor records. Please check out or remove all visitor records first.";
    } else {
        $stmt = $conn->prepare("DELETE FROM apartments WHERE apt_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
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
<title>Apartments</title>
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