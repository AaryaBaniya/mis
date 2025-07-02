<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php'; // DB connection

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Fetch all products
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

<!-- Hero -->
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
        <button class="add-to-cart-btn" data-logged-in="<?php echo $isLoggedIn ? 'yes' : 'no'; ?>">Add to Cart</button>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No products available.</p>
  <?php endif; ?>
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

<!-- Search Filter -->
<script>
  const searchInput = document.querySelector('.search-bar');
  const productCards = document.querySelectorAll('.product-card');

  if(searchInput) {
    searchInput.addEventListener('input', () => {
      const filter = searchInput.value.toLowerCase();
      productCards.forEach(card => {
        const productName = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = productName.includes(filter) ? 'inline-block' : 'none';
      });
    });
  }

  // Add to Cart button behavior
  document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', () => {
      const loggedIn = button.getAttribute('data-logged-in');
      if (loggedIn !== 'yes') {
        // Redirect to signin page if not logged in
        window.location.href = 'signin.php';
      } else {
        // TODO: Add your add to cart logic here
        alert('Product added to cart!');
      }
    });
  });
</script>

</body>
</html>
