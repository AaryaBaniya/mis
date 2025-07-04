<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);

$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Brass & Copper Hub - Shop</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>Discover Handcrafted Brass & Copper Treasures</h1>
  <p>Explore our full collection of premium traditional items.</p>
</section>

<section class="product-grid">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($product = $result->fetch_assoc()): ?>
      <div class="product-card">
        <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p class="price">Rs. <?php echo number_format($product['price']); ?></p>
        
        <?php if ($isLoggedIn): ?>
          <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <button type="submit">Add to Cart</button>
          </form>
        <?php else: ?>
          <a href="signin.php">
            <button>Login to Buy</button>
          </a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No products available.</p>
  <?php endif; ?>
</section>

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
