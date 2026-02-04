<?php
require_once 'db.php';

// ==============================
// VISITOR CHECK-IN
// ==============================
if (isset($_POST['check_in'])) {

    $name = trim($_POST['visitor_name']);
    $phone = trim($_POST['phone']);
    $apt = trim($_POST['apt']);
    $purpose = trim($_POST['purpose']);

    if (!empty($name) && !empty($apt)) {

        $name = $conn->real_escape_string($name);
        $phone = $conn->real_escape_string($phone);
        $apt = $conn->real_escape_string($apt);
        $purpose = $conn->real_escape_string($purpose);

        $conn->query("
            INSERT INTO visitors
            (visitor_name, contact, purpose, apartment_number, status)
            VALUES
            ('$name', '$phone', '$purpose', '$apt', 'checked_in')
        ");
    }

    header("Location: visitor.php");
    exit;
}

// ==============================
// VISITOR CHECK-OUT
// ==============================
if (isset($_POST['check_out'])) {

    // check out the most recent checked-in visitor
    $conn->query("
        UPDATE visitors
        SET status = 'checked_out',
            checkout_time = NOW()
        WHERE status = 'checked_in'
        ORDER BY visit_time DESC
        LIMIT 1
    ");

    header("Location: visitor.php");
    exit;
}
