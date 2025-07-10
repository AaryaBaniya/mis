<?php
// Start session for users only if needed (guests allowed too)
session_name("user_session");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is a customer
$isLoggedIn = isset($_SESSION['user_id']) && $_SESSION['role'] === 'user';

include 'db.php';

$isLoggedIn = isset($_SESSION['user_id']);
$category_name = 'Statue';

$sql = "SELECT p.* FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE c.name = ?
        ORDER BY p.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category_name);

if (!$stmt->execute()) {
    die("Database query failed: " . $stmt->error);
}

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Statues - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

<?php if (isset($_GET['added']) && $_GET['added'] == 1): ?>
  <div class="flash-message">Product added to cart successfully!</div>
<?php endif; ?>

<?php include 'navbar.php'; ?>

<!-- Hero Section -->
<section class="shop-hero">
  <h1>Statue Collection</h1>
  <p>Timeless handcrafted statues for your home or temple.</p>
</section>

<!-- Product Grid -->
<div class="container">
<section class="product-grid">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($product = $result->fetch_assoc()): ?>
      <div class="product-card"
           data-name="<?php echo htmlspecialchars($product['name']); ?>"
           data-price="<?php echo $product['price']; ?>"
           data-description="<?php echo htmlspecialchars($product['description']); ?>"
           data-image="image/<?php echo htmlspecialchars($product['image']); ?>"
           data-id="<?php echo $product['id']; ?>"
           onclick="openModal(this)">
           <div class="product-image-wrapper">
        <img src="image/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
        <p class="price">Rs. <?php echo number_format($product['price']); ?></p>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No statues found in this category.</p>
  <?php endif; ?>
</section>
</div>

<!-- Modal Popup -->
<div id="productModal" class="modal" style="display:none;">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="modal-body">
      <div class="modal-left">
        <img id="modalImage" src="" alt="" />
      </div>
      <div class="modal-right">
        <h2 id="modalName"></h2>
        <p id="modalDescription"></p>
        <p><strong>Price: Rs. <span id="modalPrice"></span></strong></p>

        <div class="quantity-selector">
          <button type="button" onclick="updateQuantity(-1)">-</button>
          <input type="number" id="modalQuantity" value="1" min="1" readonly />
          <button type="button" onclick="updateQuantity(1)">+</button>
        </div>

        <?php if ($isLoggedIn): ?>
          <form id="modalForm" action="add_to_cart.php" method="POST">
            <input type="hidden" name="product_id" id="modalProductId" />
            <input type="hidden" name="quantity" id="modalProductQty" />
            <input type="hidden" name="redirect_page" value="<?php echo basename($_SERVER['PHP_SELF']); ?>" />
            <input type="hidden" name="redirect_category" value="<?php echo htmlspecialchars($category_name); ?>" />
            <button type="submit">Add to Cart</button>
          </form>
        <?php else: ?>
          <a href="signin.php"><button>Login to Buy</button></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>


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

  function openModal(card) {
    document.getElementById('productModal').style.display = 'flex';
    document.getElementById('modalImage').src = card.dataset.image;
    document.getElementById('modalName').textContent = card.dataset.name;
    document.getElementById('modalDescription').textContent = card.dataset.description;
    document.getElementById('modalPrice').textContent = card.dataset.price;
    document.getElementById('modalProductId').value = card.dataset.id;
    document.getElementById('modalQuantity').value = 1;
    document.getElementById('modalProductQty').value = 1;
  }

  function closeModal() {
    document.getElementById('productModal').style.display = 'none';
  }

  function updateQuantity(change) {
    let qtyInput = document.getElementById('modalQuantity');
    let qtyHidden = document.getElementById('modalProductQty');
    let current = parseInt(qtyInput.value);
    let updated = Math.max(1, current + change);
    qtyInput.value = updated;
    qtyHidden.value = updated;
  }
</script>

<?php include 'footer.php'; ?>
</body>
</html>
