<?php
session_name("user_session");
session_start();

// 🚨 Force user to sign in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['flash_message'] = "⚠️ Please sign in to continue with checkout.";
    header("Location: signin.php");
    exit();
}

include 'db.php';

define('KHALTI_SECRET_KEY', 'fbbd05a8499348c688ed2db97050b2de'); 

// 🚨 Validate request
if (!isset($_GET['pidx'])) {
    $_SESSION['flash_message'] = "❌ Invalid request from Khalti.";
    header("Location: checkout.php");
    exit();
}

$pidx = $_GET['pidx'];

// --- Verify Payment with Khalti ---
$ch = curl_init("https://dev.khalti.com/api/v2/epayment/lookup/");
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        "Authorization: Key " . KHALTI_SECRET_KEY,
        "Content-Type: application/json"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(["pidx" => $pidx]),
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$res_data = json_decode($response, true);

if ($http_status == 200 && isset($res_data['status']) && $res_data['status'] === "Completed") {
    // ✅ Payment Successful
    $user_id = $_SESSION['user_id'];
    $cart_items = $_SESSION['checkout_cart_items'] ?? [];
    $total_amount = $_SESSION['checkout_total_amount'] ?? 0;
    $shipping_name = $_SESSION['shipping_name'] ?? '';
    $shipping_address = $_SESSION['shipping_address'] ?? '';
    $shipping_phone = $_SESSION['shipping_phone'] ?? '';

    $purchase_time = date('Y-m-d H:i:s');
    $order_ref = uniqid("ORD_");

    // --- Save purchases into DB ---
    foreach ($cart_items as $item) {
        $sql = "INSERT INTO purchases 
                (user_id, product_id, quantity, price, shipping_name, shipping_address, phone, payment_method, status, khalti_idx, purchase_date, order_reference_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Khalti', 'Success', ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // ⚠️ Adjust bind types based on your DB schema
        $stmt->bind_param(
            "iiiissssss",   // use "d" for price if it's DECIMAL
            $user_id,
            $item['product_id'],
            $item['quantity'],
            $item['price'],
            $shipping_name,
            $shipping_address,
            $shipping_phone,
            $pidx,
            $purchase_time,
            $order_ref
        );

        if (!$stmt->execute()) {
            die("Insert failed: " . $stmt->error);
        }
        $stmt->close();
    }

    // 🧹 Clear cart table
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // 🧹 Clear checkout session
    unset($_SESSION['checkout_cart_items'], $_SESSION['checkout_total_amount'], $_SESSION['shipping_name'], $_SESSION['shipping_address'], $_SESSION['shipping_phone']);

    // 🎉 Redirect to Thank You page
    header("Location: thankyou.php?purchase_time=" . urlencode($purchase_time) . "&order_ref=" . urlencode($order_ref));
    exit();

} else {
    $_SESSION['flash_message'] = "❌ Payment verification failed! Please try again.";
    header("Location: checkout.php");
    exit();
}
?>
