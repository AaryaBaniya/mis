<?php
session_start();
include 'db.php';

// Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}

if (isset($_GET['id'], $_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    if ($status === 'Cancelled') {
        // Admin is cancelling
        $cancelledBy = 'admin';
        $stmt = $conn->prepare("UPDATE purchases SET status = ?, cancelled_by = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $cancelledBy, $id);
    } elseif ($status === 'Dispatched') {
        // Dispatching — no cancelled_by
        $stmt = $conn->prepare("UPDATE purchases SET status = ?, cancelled_by = NULL WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
    } else {
        // Optional: handle unknown status values (security)
        header("Location: orders.php?error=invalid_status");
        exit();
    }

    $stmt->execute();
}

header("Location: orders.php");
exit();
