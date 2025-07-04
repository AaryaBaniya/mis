<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}
include'db.php'; // make sure path is correct

// Total products
$product_count = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];

// Total categories
$category_count = $conn->query("SELECT COUNT(*) as total FROM categories")->fetch_assoc()['total'];

// Total orders
$order_count = $conn->query("SELECT COUNT(*) as total FROM purchases")->fetch_assoc()['total'];

// Pending orders
$pending_count = $conn->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'Pending'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin.css" />
</head>
<body>
  <div class="admin-container">
    <?php include 'header.php'; ?>
    <main class="admin-content">
      <h1>Welcome, Admin 👋</h1>
      <div class="admin-cards">
        <div class="card">
          <h2>Products</h2>
          <p><a href="manage_products.php">Manage Products</a></p>
        </div>
        <div class="card">
          <h2>Orders</h2>
          <p><a href="orders.php">View Orders</a></p>
          <section class="admin-stats">
  <div class="stat-box">
    <h3>Total Products</h3>
    <p><?php echo $product_count; ?></p>
  </div>
  <div class="stat-box">
    <h3>Total Categories</h3>
    <p><?php echo $category_count; ?></p>
  </div>
  <div class="stat-box">
    <h3>Total Orders</h3>
    <p><?php echo $order_count; ?></p>
  </div>
  <div class="stat-box">
    <h3>Pending Orders</h3>
    <p><?php echo $pending_count; ?></p>
  </div>
</section>

        </div>
      </div>
    </main>
  </div>
</body>
</html>
