<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}
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
        </div>
        <div class="card">
          <h2>Users</h2>
          <p><a href="users.php">Manage Users</a></p>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
