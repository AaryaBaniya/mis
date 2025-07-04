<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $user_id = $_SESSION['user_id'];
  $product_id = $_POST['product_id'];

  // Check current quantity
  $check = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $result = $check->get_result();

  if ($row = $result->fetch_assoc()) {
    if ($row['quantity'] > 1) {
      // Decrease quantity
      $update = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE user_id = ? AND product_id = ?");
      $update->bind_param("ii", $user_id, $product_id);
      $update->execute();
    } else {
      // Remove product completely if quantity is 1
      $delete = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
      $delete->bind_param("ii", $user_id, $product_id);
      $delete->execute();
    }
  }
}

header("Location: cart.php");
exit();
