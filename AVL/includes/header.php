<?php
// start session for pages that need it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Apartment Visitor Logger</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <hr>
    <nav class="nav-bar">
        <div class="title-logo">
            <div class="logo-container">
                <img src="assets/AVLLogo.png" alt="AVL Logo" class="logo-image">
            <div class="title">AVL</div>
            <div class="tagline">
                Secure. Record. Manage Every Visitor.
            </div>
        </div>
        <div class="nav-links">
            <a href="index.php"><i class="fa-solid fa-house icon"></i>Home</a>
            <a href="visitor.php"><i class="fa-solid fa-bell icon"></i> Log Visitors</a>
            <a href="admin/admin_dashboard.php"><i class="fa-solid fa-user icon"></i> Admin</a>
        </div>
    </nav>
    <hr>

</body> 
