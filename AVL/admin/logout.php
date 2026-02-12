<?php
session_start();

// destroy session and redirect
session_destroy();
header("Location: admin_login.php");
exit;
