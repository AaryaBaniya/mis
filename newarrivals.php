<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$category_name = 'New Arrivals'; 

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
  <title>New Arrivals - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
  <style>
    .product-card {
      display: inline-block;
      vertical-align: top;
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>New Arrivals</h1>
  <p>Explore our latest handcrafted pieces freshly added to the collection.</p>
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
    <p>No new arrivals found.</p>
  <?php endif; ?>
</section>
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
