<?php
// Apartment Management System - Admin Only

session_start();

require_once 'db.php'; // include database connection

// Insert default admin if not exists
$check_admin = $conn->query("SELECT * FROM admin_users WHERE username = 'admin'");
if ($check_admin->num_rows === 0) {
    $default_pass = password_hash('admin123', PASSWORD_DEFAULT);
    $conn->query("INSERT INTO admin_users (username, password) VALUES ('admin', '$default_pass')");
}

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $result = $conn->query("SELECT * FROM admin_users WHERE username = '$username'");
    
    if ($result && $result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $login_message = "✓ Login successful!";
        } else {
            $login_error = "Error: Invalid credentials!";
        }
    } else {
        $login_error = "Error: User not found!";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: apartments.php");
    exit;
}

// Handle apartment operations (only if logged in)
if (isset($_SESSION['admin_logged_in'])) {
    // Add new apartment
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
        $apt_number = trim($_POST['apartment_number']);
        $status = trim($_POST['status']);
        $owner_name = trim($_POST['owner_name'] ?? '');
        $owner_phone = trim($_POST['owner_phone'] ?? '');
        $owner_email = trim($_POST['owner_email'] ?? '');
        
        if (!empty($apt_number)) {
            $apt_number = $conn->real_escape_string($apt_number);
            $owner_name = $conn->real_escape_string($owner_name);
            $owner_phone = $conn->real_escape_string($owner_phone);
            $owner_email = $conn->real_escape_string($owner_email);
            
            $insert_sql = "INSERT INTO apartments (apartment_number, status, owner_name, owner_phone, owner_email) 
                        VALUES ('$apt_number', '$status', '$owner_name', '$owner_phone', '$owner_email')";
            
            if ($conn->query($insert_sql) === TRUE) {
                $success_message = "✓ Apartment added successfully!";
            } else {
                if (strpos($conn->error, 'Duplicate entry') !== false) {
                    $error_message = "Error: Apartment already exists!";
                } else {
                    $error_message = "Error: " . $conn->error;
                }
            }
        } else {
            $error_message = "Error: Please enter apartment number!";
        }
    }
    
    // Update apartment
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
        $apt_id = intval($_POST['apartment_id']);
        $status = trim($_POST['status']);
        $owner_name = trim($_POST['owner_name'] ?? '');
        $owner_phone = trim($_POST['owner_phone'] ?? '');
        $owner_email = trim($_POST['owner_email'] ?? '');
        
        $owner_name = $conn->real_escape_string($owner_name);
        $owner_phone = $conn->real_escape_string($owner_phone);
        $owner_email = $conn->real_escape_string($owner_email);
        
        $update_sql = "UPDATE apartments SET status = '$status', owner_name = '$owner_name', 
                    owner_phone = '$owner_phone', owner_email = '$owner_email' WHERE id = $apt_id";
        
        if ($conn->query($update_sql) === TRUE) {
            $success_message = "✓ Apartment updated successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
    
    // Delete apartment
    if (isset($_GET['delete'])) {
        $apt_id = intval($_GET['delete']);
        $delete_sql = "DELETE FROM apartments WHERE id = $apt_id";
        
        if ($conn->query($delete_sql) === TRUE) {
            $success_message = "✓ Apartment deleted successfully!";
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Fetch all apartments
$apartments = [];
$result = $conn->query("SELECT * FROM apartments ORDER BY apartment_number ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $apartments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Apartment Management</title>
</head>
<body>
    <nav>
        <div class="nav-brand">Visitor & Apartment System</div>
        <div class="nav-links">
            <a href="index.php">Visitor Logger</a>
            <a href="apartments.php" class="active">Admin Panel</a>
        </div>
    </nav>
    
    <?php if (!isset($_SESSION['admin_logged_in'])): ?>
        <!-- LOGIN FORM -->
        <div class="login-container">
            <div class="login-box">
                        <h1>Admin Login</h1>
                
                <?php if (isset($login_error)): ?>
                    <div class="message error"><?php echo $login_error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($login_message)): ?>
                    <div class="message success"><?php echo $login_message; ?></div>
                    <p style="text-align: center; color: #666; margin-bottom: 20px;">Redirecting...</p>
                    <script>
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    </script>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Enter username" 
                            required
                            autofocus
                        >
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter password" 
                            required
                        >
                    </div>
                    <input type="hidden" name="login" value="1">
                    <button type="submit">Login</button>
                </form>
                
                <p style="text-align: center; color: #999; margin-top: 20px; font-size: 12px;">
                    Default credentials:<br>
                    Username: <strong>admin</strong><br>
                    Password: <strong>admin123</strong>
                </p>
            </div>
        </div>
    <?php else: ?>
        <!-- ADMIN DASHBOARD -->
        <div class="main-content">
            <div class="container">
            <div class="admin-header">
                <h1>Apartment Management</h1>
                <div class="admin-info">
                    <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></p>
                    <a href="?logout=1" style="color: #dc3545; text-decoration: none; font-weight: bold;">Logout</a>
                </div>
            </div>
            
            <?php if (isset($success_message)): ?>
                <div class="message success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="message error"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <!-- Add/Edit Apartment Form -->
            <h2>Add New Apartment</h2>
            <div class="edit-form">
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="apartment_number">Apartment Number:</label>
                            <input 
                                type="text" 
                                id="apartment_number" 
                                name="apartment_number" 
                                placeholder="e.g., 101" 
                                required
                            >
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select id="status" name="status" required>
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="owner_name">Owner Name (Optional):</label>
                            <input 
                                type="text" 
                                id="owner_name" 
                                name="owner_name" 
                                placeholder="e.g., John Doe"
                            >
                        </div>
                        <div class="form-group">
                            <label for="owner_phone">Owner Phone (Optional):</label>
                            <input 
                                type="tel" 
                                id="owner_phone" 
                                name="owner_phone" 
                                placeholder="e.g., 555-1234"
                            >
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="owner_email">Owner Email (Optional):</label>
                        <input 
                            type="email" 
                            id="owner_email" 
                            name="owner_email" 
                            placeholder="e.g., john@example.com"
                        >
                    </div>
                    
                    <button type="submit">Add Apartment</button>
                </form>
            </div>
            
            <!-- Apartments List -->
            <h2>Apartment List (<?php echo count($apartments); ?> total)</h2>
            
            <?php if (count($apartments) > 0): ?>
                <div class="apartments-grid">
                    <?php foreach ($apartments as $apt): ?>
                        <div class="apartment-card">
                            <div class="apt-number">Apt <?php echo htmlspecialchars($apt['apartment_number']); ?></div>
                            <span class="apt-status <?php echo $apt['status'] === 'available' ? 'status-available' : 'status-occupied'; ?>">
                                <?php echo strtoupper($apt['status']); ?>
                            </span>
                            
                            <?php if (!empty($apt['owner_name'])): ?>
                                <div class="apt-detail">
                                    <strong>Owner:</strong> <?php echo htmlspecialchars($apt['owner_name']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($apt['owner_phone'])): ?>
                                <div class="apt-detail">
                                    <strong>Phone:</strong> <?php echo htmlspecialchars($apt['owner_phone']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($apt['owner_email'])): ?>
                                <div class="apt-detail">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($apt['owner_email']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="apt-detail" style="color: #999; font-size: 12px; margin-top: 10px;">
                                Updated: <?php echo htmlspecialchars($apt['updated_at']); ?>
                            </div>
                            
                            <div class="apt-actions">
                                <form method="POST" style="flex: 1;">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="apartment_id" value="<?php echo $apt['id']; ?>">
                                    <input type="hidden" name="status" value="<?php echo $apt['status'] === 'available' ? 'occupied' : 'available'; ?>">
                                    <input type="hidden" name="owner_name" value="<?php echo htmlspecialchars($apt['owner_name']); ?>">
                                    <input type="hidden" name="owner_phone" value="<?php echo htmlspecialchars($apt['owner_phone']); ?>">
                                    <input type="hidden" name="owner_email" value="<?php echo htmlspecialchars($apt['owner_email']); ?>">
                                    <button type="submit" class="btn-edit">
                                        Toggle: <?php echo $apt['status'] === 'available' ? 'Occupy' : 'Release'; ?>
                                    </button>
                                </form>
                                <a href="?delete=<?php echo $apt['id']; ?>" onclick="return confirm('Delete this apartment?');" class="btn-delete" style="display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; color: #999; padding: 40px;">No apartments added yet. Create one above!</p>
            <?php endif; ?>
            </div> 
        </div>
    <?php endif; ?>
</body>
</html>

