<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$category_name = 'Decor';

// Fetch products from 'Decor' category
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
  <title>Decor - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>

<?php
if (isset($_GET['added']) && $_GET['added'] == 1) {
    echo '<div class="flash-message">Product added to cart successfully!</div>';
}
?>

<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="shop-hero">
  <h1>Decor Collection</h1>
  <p>Add elegance to your space with handcrafted decor items.</p>
</section>

<!-- Product Grid -->
<div class="container">
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
            <input type="hidden" name="redirect_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">
            <input type="hidden" name="redirect_category" value="<?php echo htmlspecialchars($category_name); ?>">
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
    <p>No decor items found in this category.</p>
  <?php endif; ?>
</section>
</div>
<?php include 'footer.php'; ?>
<script>
  const searchInput = document.querySelector('.search-bar');
  const productCards = document.querySelectorAll('.product-card');

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const filter = searchInput.value.toLowerCase();
      productCards.forEach(card => {
        const productName = card.querySelector('h3').textContent.toLowerCase();
        card.style.display = productName.includes(filter) ? 'inline-block' : 'none';
      });
    });
  }
   setTimeout(() => {
    const flash = document.querySelector('.flash-message');
    if (flash) {
      flash.style.display = 'none';
    }
  }, 4000);
</script>

</body>
</html>
