<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if item is already in cart
$checkSql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($checkSql);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  // Update quantity
  $row = $result->fetch_assoc();
  $newQty = $row['quantity'] + $quantity;
  $updateSql = "UPDATE cart SET quantity = ? WHERE id = ?";
  $updateStmt = $conn->prepare($updateSql);
  $updateStmt->bind_param("ii", $newQty, $row['id']);
  $updateStmt->execute();
} else {
  // Insert new item
  $insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
  $insertStmt = $conn->prepare($insertSql);
  $insertStmt->bind_param("iii", $user_id, $product_id, $quantity);
  $insertStmt->execute();
}

header("Location: cart.php");
exit();
