<?php
session_name("user_session");
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$shipping_name    = $_POST['shipping_name'];
$shipping_address = $_POST['shipping_address'];
$shipping_phone   = $_POST['shipping_phone'];
$payment_method   = $_POST['payment_method'] ?? 'COD';

// Fetch cart items
$sql = "SELECT c.product_id, c.quantity, p.price, p.name
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

if (empty($cart_items)) {
    $_SESSION['flash_message'] = "❌ Your cart is empty.";
    header("Location: checkout.php");
    exit;
}

if ($payment_method === "COD") {
    // ✅ Save COD orders directly in DB
    $purchase_time = date('Y-m-d H:i:s');
    $order_ref = uniqid("ORD_");

    foreach ($cart_items as $item) {
        $sql = "INSERT INTO purchases 
                (user_id, product_id, quantity, price, shipping_name, shipping_address, phone, payment_method, status, khalti_idx, purchase_date, order_reference_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'COD', 'Pending', NULL, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iiiisssss",
            $user_id,
            $item['product_id'],
            $item['quantity'],
            $item['price'],
            $shipping_name,
            $shipping_address,
            $shipping_phone,
            $purchase_time,
            $order_ref
        );
        $stmt->execute();
        $stmt->close();
    }

    // 🧹 Clear cart
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    header("Location: thankyou.php?purchase_time=" . urlencode($purchase_time) . "&order_ref=" . urlencode($order_ref));
    exit;

} else {
    // 🚀 Save details to session for Khalti flow
    $_SESSION['checkout_cart_items']   = $cart_items;
    $_SESSION['checkout_total_amount'] = $total_amount;
    $_SESSION['shipping_name']         = $shipping_name;
    $_SESSION['shipping_address']      = $shipping_address;
    $_SESSION['shipping_phone']        = $shipping_phone;

    header("Location: khalti_initiate.php");
    exit;
}
?>
