<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $product_id = $_POST['product_id'];

  // Handle redirect page
  $redirect_page = 'shop.php';
  if (!empty($_POST['redirect_page'])) {
    $redirect_page = filter_var($_POST['redirect_page'], FILTER_SANITIZE_URL);
  }

  // Optional category handling
  $redirect_query = '';
  if (!empty($_POST['redirect_category'])) {
    $category = urlencode($_POST['redirect_category']);
    $redirect_query = (strpos($redirect_page, '?') === false ? '?' : '&') . "category={$category}";
  }

  // Insert or update cart
  $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $update->bind_param("ii", $user_id, $product_id);
    $update->execute();
  } else {
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
  }

  // Final smart redirect
  $base = $redirect_page . $redirect_query;
  $separator = strpos($base, '?') !== false ? '&' : '?';
  header("Location: {$base}{$separator}added=1");
  exit();
}


