<?php
session_name("user_session");
session_start();

include 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $product_id = intval($_POST['product_id']);
  $quantity_to_add = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

  if ($quantity_to_add < 1) {
    $quantity_to_add = 1;
  }

  $stmt = $conn->prepare("
    INSERT INTO cart (user_id, product_id, quantity)
    VALUES (?, ?, ?)
    ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)
  ");

  $stmt->bind_param("iii", $user_id, $product_id, $quantity_to_add);
  $stmt->execute();
  $redirect_page = 'shop.php';
  if (!empty($_POST['redirect_page'])) {
    $redirect_page = filter_var($_POST['redirect_page'], FILTER_SANITIZE_URL);
  }
  $redirect_query = '';
  if (!empty($_POST['redirect_category'])) {
    $category = urlencode($_POST['redirect_category']);
    $redirect_query .= (strpos($redirect_page, '?') === false ? '?' : '&') . "category={$category}";
  }
  $final_redirect = $redirect_page . $redirect_query;
  $final_redirect .= (strpos($final_redirect, '?') === false ? '?' : '&') . "added=1";
  header("Location: $final_redirect");
  exit();
}
?>