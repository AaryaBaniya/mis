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

// Orders list
$sql = "SELECT p.*,
               COALESCE(pr.name, 'N/A (Deleted Product)') AS product_name,  -- Handle missing product gracefully
               COALESCE(u.username, 'Guest User') AS user_name,            -- Handle missing user gracefully
               COALESCE(u.email, 'N/A') AS user_email,                     -- Handle missing user email gracefully
               p.shipping_name, p.shipping_address, p.phone
        FROM purchases p
        LEFT JOIN products pr ON p.product_id = pr.id  -- <--- CHANGED TO LEFT JOIN
        LEFT JOIN users u ON p.user_id = u.id          -- <--- CHANGED TO LEFT JOIN
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
            <th>Customer Info</th>
            <th>Shipping Details</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?><br><small><?= date("M j, Y", strtotime($row['purchase_date'])) ?></small></td>
                <td>
                  <strong><?= htmlspecialchars($row['user_name']) ?></strong><br>
                  <small><?= htmlspecialchars($row['user_email']) ?></small>
                </td>
                <td>
                  <strong><?= htmlspecialchars($row['shipping_name']) ?></strong><br>
                  <small>Location: <?= htmlspecialchars($row['shipping_address']) ?></small><br>
                  <small>Tel: <?= htmlspecialchars($row['phone']) ?></small>
                </td>

                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= (int)$row['quantity'] ?></td>
                <td><?= htmlspecialchars($row['payment_method']) ?></td>
                <td>
                  <span class="status-badge status-<?= strtolower(htmlspecialchars($row['status'])) ?>">
                    <?php
                      if ($row['status'] === 'Cancelled') {
                        echo 'Cancelled by ' . htmlspecialchars($row['cancelled_by'] ?? 'N/A'); // Add null coalescing for cancelled_by
                      } else {
                        echo htmlspecialchars($row['status']);
                      }
                    ?>
                  </span>
                </td>
                <td class="action-buttons">
                  <?php if ($row['status'] === 'Pending'): ?>
                    <div class="button-group">
                      <!-- Dispatch button always available -->
                      <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Dispatched" class="button approve-btn">Dispatch</a>

                      <!-- Decline only for COD -->
                      <?php if ($row['payment_method'] === 'COD'): ?>
                        <a href="update_order_status.php?id=<?= $row['id'] ?>&status=Cancelled" class="button danger-btn" onclick="return confirm('Are you sure you want to decline this order?')">Decline</a>
                      <?php endif; ?>
                    </div>
                  <?php else: ?>
                    <span class="muted">No Action</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="8" class="no-data">No orders found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>