<?php
session_start();
require_once '../includes/db.php';
require_once 'admin_includes/admin_header.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<title>Account Settings</title>
<div class="container">
    <h1>Account Settings</h1>
    <p>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong></p>

    <?php if (isset($_GET['error'])): ?>
        <div style="color: red; padding: 10px; margin-bottom: 20px; border: 1px solid red; background-color: #ffe6e6; border-radius: 4px;">
            <?php if ($_GET['error'] === 'mismatch'): ?>
                <strong>Error:</strong> New passwords do not match.
            <?php elseif ($_GET['error'] === 'short'): ?>
                <strong>Error:</strong> Password must be at least 8 characters.
            <?php elseif ($_GET['error'] === 'wrong'): ?>
                <strong>Error:</strong> Current password is incorrect.
            <?php elseif ($_GET['error'] === 'empty'): ?>
                <strong>Error:</strong> All fields are required.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <div style="color: green; padding: 10px; margin-bottom: 20px; border: 1px solid green; background-color: #e6ffe6; border-radius: 4px;">
            <strong>Success:</strong> Password updated.
        </div>
    <?php endif; ?>

    <h2>Change Password</h2>
    <form method="POST" action="process_account.php">
        <input type="hidden" name="action" value="change_password">

        Current Password:
        <input type="password" name="current_password" required><br><br>

        New Password:
        <input type="password" name="new_password" required><br><br>

        Confirm New Password:
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Update Password</button>
    </form>

    <hr>

    <h2>Delete Account</h2>
    <p style="color: red;">Warning: This will permanently delete the admin account. You will need to set up a new one to access the system again.</p>

    <form method="POST" action="process_account.php" onsubmit="return confirm('Are you sure? You will be logged out and the account will be deleted.');">
        <input type="hidden" name="action" value="delete_account">

        Confirm Current Password:
        <input type="password" name="current_password" required><br><br>

        <button type="submit" style="background-color: red; color: white;">Delete Account</button>
    </form>
</div>

</body>
</html>