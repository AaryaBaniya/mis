<?php
session_name("user_session");
session_start();

include 'db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['purchase_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$purchase_id = $_POST['purchase_id'];

// Verify order belongs to user
$sql = "SELECT * FROM purchases WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $purchase_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if ($order && $order['status'] === 'Pending') {
    $placed_time = strtotime($order['purchase_date']);
    $time_diff = time() - $placed_time;
    // ...
if ($time_diff <= 14400) { 
    $update = $conn->prepare("UPDATE purchases SET status = 'Cancelled', cancelled_by = 'user' WHERE id = ?");
    $update->bind_param("i", $purchase_id);
    if ($update->execute()) {
            $_SESSION['flash_message'] = "✅ Order cancelled successfully.";
        } else {
            $_SESSION['flash_message'] = "❌ Failed to cancel the order. Please try again.";
        }
    } else {
        $_SESSION['flash_message'] = "⏰ Cancellation time window has expired.";
    }
} else {
    $_SESSION['flash_message'] = "⚠️ Invalid order or already processed.";
}

header("Location: purchase_history.php");
exit();
?>
