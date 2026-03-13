<?php
session_start();
require_once '../includes/db.php';
require_once 'admin_includes/admin_header.php';

// If an account already exists, block access entirely
$check = $conn->query("SELECT admin_id FROM admin_users LIMIT 1");
if ($check->num_rows > 0) {
    header("Location: admin_login.php");
    exit;
}
?>

<title>Setup Admin Account</title>
<div class="container">
    <h1>Setup Admin Account</h1>
    <p>No admin account exists yet. Create one to get started.</p>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: red; padding: 10px; margin-bottom: 20px; border: 1px solid red; background-color: #ffe6e6; border-radius: 4px;">
            <?php if ($_GET['error'] === 'mismatch'): ?>
                <strong>Error:</strong> Passwords do not match.
            <?php elseif ($_GET['error'] === 'short'): ?>
                <strong>Error:</strong> Password must be at least 8 characters.
            <?php elseif ($_GET['error'] === 'empty'): ?>
                <strong>Error:</strong> All fields are required.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="process_setup.php">
        Username:
        <input type="text" name="username" required><br><br>

        Password:
        <input type="password" name="password" required><br><br>

        Confirm Password:
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Create Account</button>
    </form>
</div>

</body>
</html>