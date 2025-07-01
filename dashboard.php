<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: signin.php');
  exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Brass & Copper Hub</title>
   <link rel="stylesheet" href="shop.css">
</head>
<body>
<?php include 'navbar.php'; ?>
<div>
<!-- Hero Section -->
<section class="shop-hero">
  <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
  <p>HAPPY SHOPPING</p>
</section>

<!-- Product Grid -->
<section class="product-grid">
  <div class="product-card">
    <img src="image/product1.jpg" alt="Brass Vase">
    <h3>Brass Vase</h3>
    <p class="price">Rs. 1200</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/product2.jpg" alt="Copper Bowl">
    <h3>Copper Bowl</h3>
    <p class="price">Rs. 850</p>
    <button>Add to Cart</button>
  </div>
  <!-- Add more products similarly -->
</section>

<!-- User Purchase History -->
<section class="category-section">
  <h2 class="category-title">Your Shopping History</h2>
  <div class="product-grid">
    <div class="product-card">
      <h3>Brass Vase</h3>
      <p>Status: <strong>Purchased</strong></p>
      <p>Date: 2025-06-20</p>
      <p class="price">Rs. 1200</p>
    </div>
    <div class="product-card">
      <h3>Copper Bowl</h3>
      <p>Status: <strong>Cancelled</strong></p>
      <p>Date: 2025-06-18</p>
      <p class="price">Rs. 850</p>
    </div>
    <div class="product-card">
      <h3>Wooden Statue</h3>
      <p>Status: <strong>In Cart</strong></p>
      <p>Date: 2025-06-23</p>
      <p class="price">Rs. 1700</p>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer">
  <p>© 2025 Brass & Copper Hub | Handmade in Nepal</p>
  <div class="footer-links">
    <a href="#">Contact</a>
    <a href="#">Privacy</a>
    <a href="#">Terms</a>
  </div>
</footer>

</body>
</html>
