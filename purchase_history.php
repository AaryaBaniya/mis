<?php
session_name("user_session");
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];
$sql = "SELECT p.id, pr.name, pr.price, pr.image, p.quantity, p.purchase_date, p.status, p.cancelled_by 
        FROM purchases p 
        JOIN products pr ON p.product_id = pr.id 
        WHERE p.user_id = ? ORDER BY p.purchase_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Orders</title>
  <link rel="stylesheet" href="shop.css">
</head>
<body>

<?php
if (isset($_SESSION['flash_message'])) {
    echo "<div class='flash-message'>" . $_SESSION['flash_message'] . "</div>";
    unset($_SESSION['flash_message']);
}
?>

<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>📦 My Orders</h1>
</section>

<section class="product-grid">
  <?php if ($result->num_rows > 0): ?>
    <?php while ($order = $result->fetch_assoc()): ?>
      <div class="product-card">
         <div class="product-image-wrapper">
        <img src="image/<?php echo htmlspecialchars($order['image']); ?>" alt="<?php echo htmlspecialchars($order['name']); ?>">
    </div>
        <h3><?php echo htmlspecialchars($order['name']); ?></h3>
        <p>Quantity: <?php echo $order['quantity']; ?></p>
        <p>Total: Rs. <?php echo $order['price'] * $order['quantity']; ?></p>
      <p>Status: 
  <strong>
    <?php
      if ($order['status'] === 'Cancelled') {
          if ($order['cancelled_by'] === 'user') {
              echo '<span style="color: red;">You Cancelled This Order</span>';
          } else { // Can be 'admin' or NULL (for old orders before the fix)
              echo '<span style="color: orange;">Cancelled by Admin</span>';
          }
      } elseif ($order['status'] === 'Dispatched') {
          echo '<span style="color: green;">Dispatched for Delivery</span>';
      } else {
          echo htmlspecialchars($order['status']);
      }
    ?>
  </strong>
</p>
        <p>Ordered at: <?php echo $order['purchase_date']; ?></p>

        <?php
          $placed_time = strtotime($order['purchase_date']);
          $current_time = time();
          $hours_since = ($current_time - $placed_time) / 3600;

          if ($order['status'] === 'Pending' && $hours_since <= 4) {
        ?>
          <form method="POST" action="cancel_order.php" onsubmit="return confirmCancel();">
            <input type="hidden" name="purchase_id" value="<?php echo $order['id']; ?>">
            <button type="submit" class="remove-btn">❌ Cancel Order</button>
          </form>
           <p >You can cancel this order within 4 hours.</p>
        <?php } elseif ($order['status'] === 'Pending') { ?>
          <p class="warning-note">Cancellation window expired.</p>
        <?php } ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>You haven't placed any orders yet.</p>
  <?php endif; ?>
</section>

<footer class="footer">
  <p>© 2025 Brass & Copper Hub</p>
</footer>

<script>
function confirmCancel() {
  return confirm("⚠️ Are you sure you want to cancel this order? This action cannot be undone.");
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
