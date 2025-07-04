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
  <style>
    .product-card {
      display: inline-block;
      vertical-align: top;
    }
    .flash-message {
      background-color: #4BB543;
      color: white;
      padding: 10px 20px;
      margin: 15px auto;
      text-align: center;
      border-radius: 5px;
      max-width: 400px;
      font-weight: bold;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

<?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
  <div class="flash-message">Product added to cart successfully!</div>
<?php endif; ?>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>Discover Handcrafted Brass & Copper Treasures</h1>
  <p>Explore our full collection of premium traditional items.</p>
</section>

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
            <input type="hidden" name="redirect_category" value="all">
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
</div>

<?php include 'footer.php'; ?>
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
<script>
  setTimeout(() => {
    const flash = document.querySelector('.flash-message');
    if (flash) {
      flash.style.display = 'none';
    }
  }, 4000); // hides after 4 seconds
</script>
</body>
</html>
