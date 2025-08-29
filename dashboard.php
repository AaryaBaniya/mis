<?php
session_name("user_session");
session_start();

//  only users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: signin.php");
    exit();
}

include 'db.php'; 
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];


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

<section class="shop-hero">
  <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
</section>

<section class="user-dashboard">
  <div class="dashboard-cards">
    <a href="purchase_history.php" class="dashboard-card">
      <h3>🛒 My Purchases</h3>
      <p>See all your orders and track their progress.</p>
    </a>
    <a href="cart.php" class="dashboard-card">
      <h3>🛍️ My Cart</h3>
      <p>View and update items in your cart</p>
    </a>
  </div>
</section>
</body>
</html>
