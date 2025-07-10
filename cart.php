<?php
session_name("user_session");
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: signin.php");
    exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];

// Fetch user's cart items
$sql = "SELECT p.id AS product_id, p.name, p.image, p.price, c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Cart - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>🛒 My Cart</h1>
</section>

<section class="product-grid">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($item = $result->fetch_assoc()): ?>
      <div class="product-card">
         <div class="product-image-wrapper">
        <img src="image/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
    </div>
        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
        <p class="price">Rs. <?php echo number_format($item['price']); ?></p>
        <p>Quantity: <?php echo $item['quantity']; ?></p>

        <form action="remove_from_cart.php" method="POST">
          <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
          <button type="submit" class="remove-btn">Remove</button>
        </form>
      </div>
    <?php endwhile; ?>
  </section>

  <!-- Checkout button outside loop -->
  <form action="checkout.php" method="POST" style="text-align: center; margin-top: 30px;">
    <button type="submit" class="checkout-btn">Checkout</button>
  </form>

  <?php else: ?>
    </section>
    <p style="text-align:center;">Your cart is empty.</p>
  <?php endif; ?>

<footer class="footer">
  <p>© 2025 Brass & Copper Hub | Handmade in Nepal</p>
</footer>

</body>
</html>
