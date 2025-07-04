<?php
session_start();
include 'db.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link rel="stylesheet" href="admin.css">
</head>
<body>
  <div class="admin-container">
    <div class="admin-content">
      <div class="page-header">
        <?php include 'header.php'; ?>
        <h1>Manage Products</h1>
        <a href="add_product.php" class="button add-btn">+ Add Product</a>
      </div>

      <?php if (isset($_GET['message'])): ?>
        <p class="success-message"><?= htmlspecialchars($_GET['message']) ?></p>
      <?php endif; ?>

      <div class="table-wrapper">
        <table class="product-table">
          <thead>
            <tr>
              <th>SN</th>
              <th>Product</th>
              <th>Price</th>
              <th>Category</th>
              <th class="action-header">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT products.*, categories.name AS category FROM products LEFT JOIN categories ON products.category_id = categories.id";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
            ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td>Rs. <?= number_format($row['price'], 2) ?></td>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td class="action-buttons">
                <a href="edit_product.php?id=<?= $row['id'] ?>" class="button edit-btn">Edit</a>
                <a href="delete_product.php?id=<?= $row['id'] ?>" class="button danger-btn" onclick="return confirm('Delete this product?')">Delete</a>
              </td>
            </tr>
            <?php
              endwhile;
            else:
              echo '<tr><td colspan="5" class="no-data">No products available.</td></tr>';
            endif;
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
