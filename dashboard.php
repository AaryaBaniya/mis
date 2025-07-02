<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}
if ($_SESSION['role'] === 'admin') {
  header("Location: admin.php");
  exit();
}

include 'db.php'; 
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
// Fetch purchase history for the logged-in user
$historySql = "SELECT p.*, pr.name, pr.price FROM purchases p
               JOIN products pr ON p.product_id = pr.id
               WHERE p.user_id = ?
                ORDER BY p.purchase_date DESC";

$stmt = $conn->prepare($historySql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$historyResult = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css" />
</head>
<body>
<?php include 'navbar.php'; ?>
<!-- Hero Section -->
<section class="shop-hero">
  <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
</section>

<!-- Dashboard Links Section -->
<section class="user-dashboard">
  <div class="dashboard-cards">
    <a href="purchase_history.php" class="dashboard-card">
      <h3>🛒 My Purchases</h3>
      <p>See all your successful orders</p>
    </a>
    
    <a href="cart.php" class="dashboard-card">
      <h3>🛍️ My Cart</h3>
      <p>View and update items in your cart</p>
    </a>

    <a href="cancelled_orders.php" class="dashboard-card">
      <h3>❌ Cancelled Orders</h3>
      <p>Track your cancelled items</p>
    </a>
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

</body>
</html>
