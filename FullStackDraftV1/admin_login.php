<?php
// start session for admin login
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

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

</body>
</html>
