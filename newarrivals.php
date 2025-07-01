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
  <title>New Arrivals - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>New Arrivals</h1>
  <p>Explore our latest handcrafted pieces freshly added to the collection.</p>
</section>

<section class="product-grid">
  <div class="product-card">
    <img src="image/new1.jpg" alt="Copper Lamp">
    <h3>Copper Lamp</h3>
    <p class="price">Rs. 2,100</p>
    <button>Add to Cart</button>
  </div>
  <div class="product-card">
    <img src="image/new2.jpg" alt="Brass Mirror">
    <h3>Brass Mirror</h3>
    <p class="price">Rs. 1,500</p>
    <button>Add to Cart</button>
  </div>
</section>

<footer class="footer">
  <p>© 2025 Brass & Copper Hub | Handmade in Nepal</p>
  <div class="footer-links">
    <a href="#">Contact</a>
    <a href="#">Privacy</a>
    <a href="#">Terms</a>
  </div>
</footer>

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
