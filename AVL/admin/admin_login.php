<?php
// start session for admin login
session_start();
// redirect to admin dashboard if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}
require_once 'admin_includes/admin_header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
<div class="container">
    <h2>Admin Login</h2>

    <form action="process_admin.php" method="POST">

        Username:
        <input type="text" name="username" required><br><br>

        Password:
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>

    <?php
    // display error message if login fails
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>Invalid login</p>";
    }
    ?>
</div>
</body>
</html>
