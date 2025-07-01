<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Antiques - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>

<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="shop-hero">
  <h1>Antique Collection</h1>
  <p>Add elegance to your space with handcrafted decor items.</p>
</section>

<!-- Product Grid -->
<section class="product-grid">
  <div class="product-card">
    <img src="image/wallart.jpg" alt="Wall Art">
    <h3>Wall Art</h3>
    <p class="price">Rs. 1,300</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/vase.jpg" alt="Brass Vase">
    <h3>Brass Vase</h3>
    <p class="price">Rs. 1,200</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/candleset.jpg" alt="Decorative Candle">
    <h3>Decorative Candle</h3>
    <p class="price">Rs. 800</p>
    <button>Add to Cart</button>
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

<!-- Search Filter -->
<script>
  const searchInput = document.querySelector('.search-bar');
  const productCards = document.querySelectorAll('.product-card');

  searchInput.addEventListener('input', () => {
    const filter = searchInput.value.toLowerCase();
    productCards.forEach(card => {
      const productName = card.querySelector('h3').textContent.toLowerCase();
      card.style.display = productName.includes(filter) ? 'block' : 'none';
    });
  });
</script>

</body>
</html>
