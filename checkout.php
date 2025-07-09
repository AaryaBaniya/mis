<?php
session_name("user_session");
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: signin.php");
  exit();
}

include 'db.php';
$user_id = $_SESSION['user_id'];

// Fetch cart items
$sql = "SELECT p.id, p.name, p.price, c.quantity 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total
$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
  $subtotal = $row['price'] * $row['quantity'];
  $total += $subtotal;
  $items[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Checkout - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<section class="shop-hero">
  <h1>🧾 Checkout</h1>
</section>
<section class="checkout-section">
  <form class="checkout-form" action="place_order.php" method="POST" onsubmit="return confirmPlaceOrder();">
    <h3>Shipping Information</h3>
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="text" name="address" placeholder="Full Address" required />
    <input type="text" name="phone" placeholder="Phone Number" required />

    <h3>Payment Method</h3>
    <label><input type="radio" name="payment_method" value="COD" checked /> Cash on Delivery</label><br>
    <label><input type="radio" name="payment_method" value="Khalti" /> Khalti</label>

    <h3>Order Summary</h3>
    <ul>
      <?php foreach ($items as $item): ?>
        <li><?php echo $item['name']; ?> × <?php echo $item['quantity']; ?> = Rs. <?php echo $item['price'] * $item['quantity']; ?></li>
      <?php endforeach; ?>
    </ul>
    <p><strong>Total: Rs. <?php echo $total; ?></strong></p>
    <button type="submit" class="checkout-btn">Place Order ✅</button>
  </form>
</section>
