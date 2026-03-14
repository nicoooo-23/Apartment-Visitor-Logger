<?php
// start session for admin login
session_start();
// redirect to admin dashboard if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}
// Check if admin account exists, if not redirect to setup page
require_once '../includes/db.php';
$check = $conn->query("SELECT 1 FROM admin_users LIMIT 1");
if (!$check || $check->num_rows === 0) {
    header("Location: setup.php");
    exit;
}
require_once 'admin_includes/admin_header.php';
?>

<div class="container">
    <h2>Admin Login</h2>

    <form action="process_admin.php" method="POST">

        Username:
        <input type="text" name="username" required><br><br>

        Password:
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: red; padding: 10px; margin-top: 20px; border: 1px solid red; background-color: #ffe6e6; border-radius: 4px;">
            <?php if ($_GET['error'] === 'invalid'): ?>
                <strong>Error:</strong> Invalid username or password.
            <?php elseif ($_GET['error'] === 'empty'): ?>
                <strong>Error:</strong> Both fields are required.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['setup'])): ?>
        <div style="color: green; padding: 10px; margin-bottom: 20px; border: 1px solid green; background-color: #e6ffe6; border-radius: 4px;">
            <strong>Success:</strong> Account created. You can now log in.
        </div>
    <?php endif; ?>
</div>
</body>
</html>
