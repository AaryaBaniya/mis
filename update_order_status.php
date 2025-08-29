<?php
session_name("admin_session");
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

if (isset($_GET['id'], $_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    // Use a switch statement for clarity
    switch ($status) {
        case 'Dispatched':
            // Only update the status. Do not touch the cancelled_by column.
            $stmt = $conn->prepare("UPDATE purchases SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            break;

        case 'Cancelled':
            // Update status AND record that the admin did it.
            $stmt = $conn->prepare("UPDATE purchases SET status = ?, cancelled_by = 'admin' WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            break;
        
        default:
            // If status is something unexpected, do nothing and exit.
            header("Location: orders.php?error=invalid_status");
            exit();
    }

    if ($stmt) {
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: orders.php");
exit();
?>