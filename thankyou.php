<?php
session_name("user_session");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'db.php'; 

$user_id = $_SESSION['user_id'];
$order_ref_param = $_GET['order_ref'] ?? null; 
$order_summary = [];
$total_order_amount = 0;
$shipping_details = null; 
$payment_details = null; 

if ($order_ref_param) { 
    $sql = "SELECT p.*, prod.name as product_name_from_db 
            FROM purchases p 
            JOIN products prod ON p.product_id = prod.id 
            WHERE p.user_id = ? AND p.order_reference_id = ?
            ORDER BY p.id ASC"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $order_ref_param);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if (!$shipping_details) { 
            $shipping_details = [
                'name' => $row['shipping_name'],
                'address' => $row['shipping_address'],
                'phone' => $row['phone']
            ];
            $payment_details = [
                'method' => $row['payment_method'],
                'status' => $row['status'],
                'khalti_idx' => $row['khalti_idx'],
                'order_reference_id' => $row['order_reference_id'],
                'purchase_date' => $row['purchase_date']
            ];
        }
        $order_summary[] = [
            'product_name' => $row['product_name_from_db'], 
            'quantity' => $row['quantity'],
            'price' => $row['price']
        ];
        $total_order_amount += ($row['price'] * $row['quantity']);
    }
    $stmt->close();
} else {
    $_SESSION['flash_message'] = "⚠️ Could not load specific order details.";
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Thank You - Brass & Copper Hub</title>
  <link rel="stylesheet" href="shop.css">
  <link rel="stylesheet" href="khalti_styles.css"> 
</head>
<body>
<?php include 'navbar.php'; ?>
<?php 
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $class = '';
    if (strpos($message, '✅') !== false || strpos(strtolower($message), 'success') !== false || strpos(strtolower($message), 'placed') !== false) {
        $class = 'flash-success';
    } elseif (strpos($message, '❌') !== false || strpos(strtolower($message), 'failed') !== false || strpos(strtolower($message), 'error') !== false || strpos(strtolower($message), 'problem') !== false) {
        $class = 'flash-error';
    } elseif (strpos($message, '⚠️') !== false || strpos(strtolower($message), 'warning') !== false || strpos(strtolower($message), 'invalid') !== false || strpos(strtolower($message), 'empty') !== false) {
        $class = 'flash-warning';
    } else {
        $class = 'flash-warning';
    }
    echo '<div class="flash-message ' . $class . '">' . htmlspecialchars($message) . '</div>';
    unset($_SESSION['flash_message']);
}
?>

<section class="shop-hero thank-you-section">
  <h1>🎉 Thank You for Your Order!</h1>
  <p>Your order has been placed successfully.</p>

  <?php if (!empty($order_summary)): ?>
    <div class="order-summary-box">
      <h2>Order Reference: <?= htmlspecialchars($payment_details['order_reference_id']) ?></h2>
      <p><strong>Order Placed on:</strong> <?= htmlspecialchars(date('M d, Y H:i A', strtotime($payment_details['purchase_date']))) ?></p>
      <p><strong>Total Amount:</strong> Rs. <?= number_format($total_order_amount, 2) ?></p>
      
      <?php if ($payment_details): ?>
        <p><strong>Payment Method:</strong> <?= htmlspecialchars($payment_details['method']) ?></p>
        <p><strong>Order Status:</strong> <span class="status-<?= strtolower($payment_details['status']) ?>"><?= htmlspecialchars($payment_details['status']) ?></span></p>
        <?php if ($payment_details['method'] === 'Khalti' && $payment_details['khalti_idx']): ?>
          <p><strong>Khalti Transaction ID:</strong> <?= htmlspecialchars($payment_details['khalti_idx']) ?></p>
        <?php endif; ?>
      <?php endif; ?>

      <h3>Shipping To:</h3>
      <p><?= htmlspecialchars($shipping_details['name']) ?></p>
      <p><?= htmlspecialchars($shipping_details['address']) ?></p>
      <p><?= htmlspecialchars($shipping_details['phone']) ?></p>

      <h3>Items Purchased:</h3>
      <ul>
        <?php foreach ($order_summary as $item): ?>
          <li><?= htmlspecialchars($item['product_name']) ?> × <?= (int)$item['quantity'] ?> = Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php else: ?>
    <p>We couldn't find the details for your specific order, but rest assured it was placed!</p>
  <?php endif; ?>

  <a href="shop.php" class="btn primary-btn">Continue Shopping</a>
  <a href="purchase_history.php" class="btn secondary-btn">View My Orders</a>
</section>

</body>
</html>
