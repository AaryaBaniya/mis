<?php
session_name("user_session");
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// --- Fetch cart items ---
$sql = "SELECT c.id as cart_id, c.product_id, c.quantity, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_amount = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['price'] * $row['quantity'];
}
$stmt->close();

// Save cart details for later
$_SESSION['checkout_cart_items']   = $cart_items;
$_SESSION['checkout_total_amount'] = $total_amount;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Brass & Copper Hub</title>
    <link rel="stylesheet" href="shop.css">
    <link rel="stylesheet" href="khalti_styles.css">
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .checkout-section { max-width: 600px; margin: auto; }
        .checkout-form input[type=text] { width: 100%; padding: 8px; margin: 8px 0; }
        .checkout-form label { display: block; margin-top: 10px; }
        .checkout-btn { margin-top: 15px; padding: 10px; background: purple; color: white; border: none; cursor: pointer; width: 100%; }
        .checkout-btn:hover { background: darkmagenta; }
        .flash-message { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .flash-success { background: #d4edda; color: #155724; }
        .flash-error { background: #f8d7da; color: #721c24; }
        .flash-warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<?php 
// Flash messages
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    $class = 'flash-warning';
    if (strpos($message, '✅') !== false || stripos($message, 'success') !== false) $class = 'flash-success';
    if (strpos($message, '❌') !== false || stripos($message, 'failed') !== false) $class = 'flash-error';

    echo '<div class="flash-message ' . $class . '">' . htmlspecialchars($message) . '</div>';
    unset($_SESSION['flash_message']);
}
?>

<section class="checkout-section">
    <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <form id="checkoutForm" class="checkout-form" method="POST" action="process_order.php">
            <h3>Shipping Information</h3>
            <input type="text" name="shipping_name" placeholder="Full Name" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required>
            <input type="text" name="shipping_address" placeholder="Full Address" required>
            <input type="text" name="shipping_phone" placeholder="Phone Number" required>

            <h3>Payment Method</h3>
            <label><input type="radio" name="payment_method" value="COD" checked> Cash on Delivery</label>
            <label><input type="radio" name="payment_method" value="Khalti"> Khalti</label>
            <h3>Order Summary</h3>
            <ul>
                <?php foreach ($cart_items as $item): ?>
                    <li><?= htmlspecialchars($item['name']) ?> × <?= (int)$item['quantity'] ?> = Rs. <?= number_format($item['price'] * $item['quantity'], 2) ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Total: Rs. <?= number_format($total_amount, 2) ?></strong></p>

            <button type="submit" class="checkout-btn">Place Order ✅</button>
        </form>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const khaltiHint = document.getElementById('khalti_hint');

    function toggleKhaltiHint() {
        if (document.querySelector('input[name="payment_method"][value="Khalti"]:checked')) {
            khaltiHint.style.display = 'block';
        } else {
            khaltiHint.style.display = 'none';
        }
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', toggleKhaltiHint);
    });
    toggleKhaltiHint(); // set on load
});

// Basic shipping validation
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const name = document.querySelector('input[name="shipping_name"]').value;
    const address = document.querySelector('input[name="shipping_address"]').value;
    const phone = document.querySelector('input[name="shipping_phone"]').value;

    if (!name || !address || !phone) {
        alert("Please fill in all shipping details.");
        e.preventDefault();
    }
});
</script>
</body>
</html>
