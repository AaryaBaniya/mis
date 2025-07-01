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

<!-- Product Grid -->
<section class="product-grid">
  <div class="product-card">
    <img src="image/pot.webp" alt="Brass Vase">
    <h3>Brass Vase</h3>
    <p class="price">Rs. 1,200</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/product2.jpg" alt="Copper Bowl">
    <h3>Copper Bowl</h3>
    <p class="price">Rs. 850</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/wallart.jpg" alt="Wall Art">
    <h3>Wall Art</h3>
    <p class="price">Rs. 1,300</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/metalstatue.jpg" alt="Metal Statue">
    <h3>Metal Statue</h3>
    <p class="price">Rs. 2,000</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/antique1.jpg" alt="Antique Pot">
    <h3>Antique Pot</h3>
    <p class="price">Rs. 3,000</p>
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
