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

  // Optional: get redirect page or category from POST
  $redirect_page = 'shop.php'; // default fallback
  if (!empty($_POST['redirect_page'])) {
    // sanitize the page name (allow only letters, numbers, underscores, hyphens)
    $redirect_page = preg_replace("/[^a-zA-Z0-9_\-\.]/", "", $_POST['redirect_page']);
  }

  $redirect_query = '';
  if (!empty($_POST['redirect_category'])) {
    // sanitize and add category to query string
    $category = urlencode($_POST['redirect_category']);
    $redirect_query = (strpos($redirect_page, '?') === false ? '?' : '&') . "category={$category}";
  }

  // Check if item already exists in cart
  $check = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    // Update quantity
    $update = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
    $update->bind_param("ii", $user_id, $product_id);
    $update->execute();
  } else {
    // Insert new
    $insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
  }

  // Redirect back to the originating page with flash message
  header("Location: {$redirect_page}{$redirect_query}&added=1");
  exit();
}
?>
