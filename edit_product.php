<?php
include 'db.php';
$id = $_GET['id'];

$product = $conn->query("SELECT * FROM products WHERE id = $id")->fetch_assoc();
$categories = $conn->query("SELECT * FROM categories");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  $cat = $_POST['category'];
  $image = $product['image'];

  if ($_FILES['image']['name']) {
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image);
  }

  $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category_id=? WHERE id=?");
  $stmt->bind_param("ssdsii", $name, $desc, $price, $image, $cat, $id);
  $stmt->execute();

  header("Location: manage_products.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit Product</title></head>
<link rel="stylesheet" href="admin.css">
<body>
  <h1>Edit Product</h1>
  <form method="POST" enctype="multipart/form-data">
    Name: <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"><br><br>
    Description: <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br><br>
    Price: <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>"><br><br>
    Current Image: <img src="uploads/<?= $product['image'] ?>" width="100"><br>
    New Image: <input type="file" name="image"><br><br>
    Category:
    <select name="category">
      <?php while ($cat = $categories->fetch_assoc()): ?>
        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']) ?>
        </option>
      <?php endwhile; ?>
    </select><br><br>
    <button type="submit">Update Product</button>
  </form>
</body>
</html>
