<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

// Fetch product price
$priceSql = "SELECT price FROM products WHERE id = ?";
$stmt = $conn->prepare($priceSql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($price);
$stmt->fetch();
$stmt->close();

$total_price = $price * $quantity;

// Insert into purchases table
$insertSql = "INSERT INTO purchases (user_id, product_id, quantity, total_price, status) 
              VALUES (?, ?, ?, ?, 'completed')";
$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param("iiid", $user_id, $product_id, $quantity, $total_price);
$insertStmt->execute();

header("Location: purchase_history.php");
exit();
