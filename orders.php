<?php
session_name("admin_session");
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location:signin.php");
    exit();
}
include 'db.php';

// Order stats
$total_orders = $conn->query("SELECT COUNT(*) as total FROM purchases")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'Pending'")->fetch_assoc()['total'];
$dispatched_orders = $conn->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'Dispatched'")->fetch_assoc()['total'];
$cancelled_orders = $conn->query("SELECT COUNT(*) as total FROM purchases WHERE status = 'Cancelled'")->fetch_assoc()['total'];

// Fetch all orders
$sql = "SELECT p.*, p.cancelled_by, pr.name AS product_name, u.username AS user_name, u.email AS user_email 
        FROM purchases p 
        JOIN products pr ON p.product_id = pr.id 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.purchase_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Orders</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-container">
    <?php include 'header.php'; ?>
    <div class="admin-content">
      <h1>All Orders</h1>

      <div class="order-stats">
        <div>Total Orders: <strong><?= $total_orders ?></strong></div>
        <div>Pending: <strong><?= $pending_orders ?></strong></div>
        <div>Dispatched: <strong><?= $dispatched_orders ?></strong></div>
        <div>Cancelled: <strong><?= $cancelled_orders ?></strong></div>
      </div>

      <table class="product-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>User</th>
            <th>Email</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Ordered On</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['user_email']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= (int)$row['quantity'] ?></td>
                <td><?= htmlspecialchars($row['payment_method']) ?></td>
                <td>
                  <?php 
                    if ($row['status'] === 'Cancelled') {
                      echo ($row['cancelled_by'] === 'user') ? 'Cancelled by User' : 'Cancelled by Admin';
                    } elseif ($row['status'] === 'Dispatched') {
                      echo 'Dispatched for Delivery';
                    } else {
                      echo htmlspecialchars($row['status']);
                    }
                  ?>
                </td>
                <td><?= htmlspecialchars($row['purchase_date']) ?></td>
                <td class="action-buttons">
                  <?php if ($row['status'] === 'Pending'): ?>
                    <div class="button-group">
                      <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Dispatched" class="button approve-btn">Dispatch</a>
                      <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Cancelled" class="button danger-btn" onclick="return confirm('Are you sure you want to decline this order?')">Decline</a>
                    </div>
                  <?php else: ?>
                    <span class="muted">No Action</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="9" class="no-data">No orders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
