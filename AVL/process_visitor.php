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

        $stmt = $conn->prepare("
            INSERT INTO visitors
            (visitor_name, contact, purpose, apartment_id, status)
            VALUES
            (?, ?, ?, ?, 'checked_in')
        ");

        $stmt->bind_param("sssi", $name, $phone, $purpose, $apt);
        $stmt->execute();
        $stmt->close();
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
            WHERE v_id = ? AND status = 'checked_in'
        ");

        $stmt->bind_param("i", $visitor_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: visitor.php");
    exit;
}