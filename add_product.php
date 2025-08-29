<?php
include 'db.php';

// --- Configuration ---
$uploadDir = 'image/'; // FIX: Standardize the directory name to match shop.php

$errors = [];
$name = '';
$desc = '';
$price = '';
$cat_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // FIX: Repopulate variables from POST to retain values on error
  $name = trim($_POST['name']);
  $desc = trim($_POST['description']);
  $price = $_POST['price']; // Keep as string for repopulating the input field
  $cat_id = intval($_POST['category']);

  // --- Validation ---
  if (!preg_match('/^[A-Za-z\s0-9].*/', $name)) {
      $errors[] = "Product name must start with a letter or number.";
  }
  if (floatval($price) <= 10) {
      $errors[] = "Price must be greater than 10.";
  }
  if (strlen($desc) > 500) {
      $errors[] = "Description must not exceed 500 characters.";
  }

  $image = '';
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $imageName = $_FILES['image']['name'];
      $imageTmpName = $_FILES['image']['tmp_name'];
      $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png', 'gif'];

      if (!in_array($imageExt, $allowed)) {
          $errors[] = "Image must be JPG, JPEG, PNG, or GIF.";
      } else {
          // FIX: Create a unique filename and prepare to move to the correct directory
          $image = uniqid('img_') . "." . $imageExt;
          $destination = $uploadDir . $image;
          
          // FIX: Ensure the upload directory exists and is writable
          if (!is_dir($uploadDir)) {
              mkdir($uploadDir, 0775, true);
          }

          if (!move_uploaded_file($imageTmpName, $destination)) {
              $errors[] = "Failed to move uploaded file. Check directory permissions.";
          }
      }
  } else {
      $errors[] = "Please upload an image.";
  }

  // --- Database Insertion ---
  if (empty($errors)) {
      $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
      // FIX: Use floatval here for the database, not before
      $db_price = floatval($price);
      $stmt->bind_param("ssdsi", $name, $desc, $db_price, $image, $cat_id);
      
      if ($stmt->execute()) {
          header("Location: manage_products.php?success=1");
          exit;
      } else {
          $errors[] = "Failed to add product to the database.";
      }
  }
}

$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<div class="admin-content">
  <h1>Add Product</h1>

  <?php if (!empty($errors)): ?>
    <div class="success-message" style="background:#fdd; color:#900; border-color:#eaa;">
      <?= implode('<br>', $errors) ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <!-- FIX: Retain value on error -->
    <input type="text" name="name" required pattern="^[A-Za-z\s0-9].*" title="Must start with a letter or number" value="<?= htmlspecialchars($name) ?>">

    <label>Description:</label>
    <!-- FIX: Retain value on error -->
    <textarea name="description" maxlength="500" required><?= htmlspecialchars($desc) ?></textarea>

    <label>Price:</label>
    <!-- FIX: Retain value on error -->
    <input type="number" step="0.01" min="10.01" name="price" required value="<?= htmlspecialchars($price) ?>">
    
    <label>Image:</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif" required>

    <label>Category:</label>
    <select name="category" required>
      <option value="">-- Select Category --</option>
      <?php while ($cat = $categories->fetch_assoc()): ?>
        <!-- FIX: Retain selected category on error -->
        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $cat_id) ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <button type="submit">Add Product</button>
  </form>
</div>

</body>
</html>