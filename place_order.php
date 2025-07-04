<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$payment_method = $_POST['payment_method'];

// Get cart items
$sql = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Save orders
while ($row = $result->fetch_assoc()) {
  $insert = $conn->prepare("INSERT INTO purchases 
    (user_id, product_id, quantity, purchase_date, status, shipping_name, shipping_address, phone, payment_method) 
    VALUES (?, ?, ?, NOW(), 'Pending', ?, ?, ?, ?)");
  $insert->bind_param("iiissss", $user_id, $row['product_id'], $row['quantity'], $name, $address, $phone, $payment_method);
  $insert->execute();
}

// Clear cart
$delete = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$delete->bind_param("i", $user_id);
$delete->execute();

$_SESSION['flash_message'] = "🎉 Order placed successfully!";
header("Location: dashboard.php");
exit();
