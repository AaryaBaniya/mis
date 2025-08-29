<?php
include 'db.php';

// --- Configuration ---
$uploadDir = 'image/'; // FIX: Standardize the directory name to match shop.php

// FIX: Secure initial data fetching with a prepared statement
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Invalid Product ID.");
}
$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Product not found.");
}
$product = $result->fetch_assoc();
$stmt->close();

$categories = $conn->query("SELECT * FROM categories");
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $desc = trim($_POST['description']);
  $price = floatval($_POST['price']);
  $cat = intval($_POST['category']);
  $image = $product['image']; // Start with the existing image filename

  // --- Validation ---
  if (!preg_match('/^[A-Za-z\s0-9].*/', $name)) {
      $errors[] = "Product name must start with a letter or number.";
  }
  if ($price <= 10) {
      $errors[] = "Price must be greater than 10.";
  }
  if (strlen($desc) > 500) {
      $errors[] = "Description must not exceed 500 characters.";
  }

  // --- Image Upload Handling (if a new image is provided) ---
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['name'])) {
      $imageName = $_FILES['image']['name'];
      $imageTmpName = $_FILES['image']['tmp_name'];
      $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];

      if (!in_array($imageExt, $allowed)) {
          $errors[] = "Image must be JPG, JPEG, PNG, or GIF.";
      } else {
          $newImageName = uniqid('img_') . "." . $imageExt;
          $destination = $uploadDir . $newImageName;

          if (!is_dir($uploadDir)) {
              mkdir($uploadDir, 0775, true);
          }

          if (move_uploaded_file($imageTmpName, $destination)) {
              // FIX: New image uploaded successfully, so delete the old one
              $oldImagePath = $uploadDir . $product['image'];
              if (file_exists($oldImagePath) && !empty($product['image'])) {
                  unlink($oldImagePath);
              }
              $image = $newImageName; // Update the image variable to the new filename
          } else {
              $errors[] = "Failed to move uploaded file. Check directory permissions.";
          }
      }
  }

  // --- Database Update ---
  if (empty($errors)) {
      $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category_id=? WHERE id=?");
      $stmt->bind_param("ssdsii", $name, $desc, $price, $image, $cat, $id);
      
      if ($stmt->execute()) {
          header("Location: manage_products.php?success=2"); // Different code for update
          exit;
      } else {
          $errors[] = "Failed to update product in the database.";
      }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Product</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-content">
  <h1>Edit Product</h1>

  <?php if (!empty($errors)): ?>
    <div class="success-message" style="background:#fdd; color:#900; border-color:#eaa;">
      <?= implode('<br>', $errors) ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required pattern="^[A-Za-z\s0-9].*" title="Must start with a letter or number">

    <label>Description:</label>
    <textarea name="description" maxlength="500" required><?= htmlspecialchars($product['description']) ?></textarea>

    <label>Price:</label>
    <input type="number" step="0.01" min="10.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Current Image:</label>
    <!-- FIX: Use the correct directory path and add a check for existence -->
    <?php if(!empty($product['image']) && file_exists($uploadDir . $product['image'])): ?>
      <img src="<?= $uploadDir . htmlspecialchars($product['image']) ?>" width="120" style="display:block;margin-bottom:10px;" alt="Current Image">
    <?php else: ?>
      <p>No image available.</p>
    <?php endif; ?>

    <label>New Image (optional):</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif">

    <label>Category:</label>
    <select name="category" required>
      <?php mysqli_data_seek($categories, 0); // Reset pointer for the loop ?>
      <?php while ($cat = $categories->fetch_assoc()): ?>
        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Update Product</button>
  </form>
</div>

</body>
</html>