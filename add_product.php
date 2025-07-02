<?php include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $desc = $_POST['description'];
  $price = $_POST['price'];
  $cat = $_POST['category'];
  $image = $_FILES['image']['name'];

  $target = "uploads/" . basename($image);
  move_uploaded_file($_FILES['image']['tmp_name'], $target);

  $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category_id) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $cat);
  $stmt->execute();

  header("Location: manage_products.php");
  exit;
}

$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html>
<head><title>Add Product</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>
  <h1>Add Product</h1>
  <form method="POST" enctype="multipart/form-data">
    Name: <input type="text" name="name" required><br><br>
    Description: <textarea name="description" required></textarea><br><br>
    Price: <input type="number" step="0.01" name="price" required><br><br>
    Image: <input type="file" name="image" required><br><br>
    Category:
    <select name="category" required>
      <?php while ($cat = $categories->fetch_assoc()): ?>
        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
      <?php endwhile; ?>
    </select><br><br>
    <button type="submit">Add Product</button>
  </form>
</body>
</html>
