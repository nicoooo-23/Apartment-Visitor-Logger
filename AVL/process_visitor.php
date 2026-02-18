<?php
require_once 'includes/db.php';

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
            (visitor_name, contact, purpose, apartment_id, status)
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

    if (!empty($_POST['checkout_visitor'])) {

        $visitor_id = (int) $_POST['checkout_visitor'];

        $stmt = $conn->prepare("
            UPDATE visitors
            SET status = 'checked_out',
                checkout_time = NOW()
            WHERE id = ? AND status = 'checked_in'
        ");

        $stmt->bind_param("i", $visitor_id);
        $stmt->execute();
    }

    header("Location: visitor.php");
    exit;
}
