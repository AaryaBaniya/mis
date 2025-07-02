<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php'; // your database connection

// Fetch products in the "statue" category (assuming category_id or category field)
$category_name = 'Statue'; // Adjust as needed

// Example assuming categories table and products have category_id
$sql = "SELECT p.* FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE c.name = ?
        ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category_name);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Statues - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>Statue Collection</h1>
  <p>Timeless handcrafted statues for your home or temple.</p>
</section>
<section class="product-grid">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($product = $result->fetch_assoc()): ?>
      <div class="product-card">
        <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p class="price">Rs. <?php echo number_format($product['price']); ?></p>
        <button>Add to Cart</button>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No statues found in this category.</p>
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

<script>
  const searchInput = document.querySelector('.search-bar');
  const productCards = document.querySelectorAll('.product-card');

  searchInput.addEventListener('input', () => {
    const filter = searchInput.value.toLowerCase();
    productCards.forEach(card => {
      const productName = card.querySelector('h3').textContent.toLowerCase();
      card.style.display = productName.includes(filter) ? 'inline-block' : 'none';
    });
  });
</script>

</body>
</html>
