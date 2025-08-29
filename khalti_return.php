<?php
session_name("user_session");
session_start();
include 'db.php';

// Enable strict error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// --- Khalti Redirect Handling ---
if (!isset($_GET['pidx'])) {
    $_SESSION['flash_message'] = "❌ Missing pidx from Khalti redirect.";
    header("Location: checkout.php");
    exit();
}

$pidx = $_GET['pidx'];

// --- Verify payment with Khalti Lookup API ---
define('KHALTI_SECRET_KEY', 'fbbd05a8499348c688ed2db97050b2de');

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

if (curl_errno($ch)) {
    $error_msg = "❌ cURL Error during Khalti Lookup: " . curl_error($ch);
    error_log($error_msg);
    $_SESSION['flash_message'] = $error_msg;
    header("Location: checkout.php");
    exit();
}
curl_close($ch);

$resp = json_decode($response, true);

if ($http_status !== 200 || !isset($resp['status']) || $resp['status'] !== 'Completed') {
    $error_msg = "❌ Payment verification failed. Khalti Status: " . ($resp['status'] ?? 'N/A');
    error_log($error_msg);
    $_SESSION['flash_message'] = $error_msg;
    header("Location: checkout.php");
    exit();
}
// --- END verification ---


// --- Session data validation ---
if (!isset($_SESSION['checkout_cart_items'])) {
    $_SESSION['flash_message'] = "⚠️ Session expired or cart not found. Please try again.";
    header("Location: checkout.php");
    exit();
}

// --- Assign variables ---
$user_id         = $_SESSION['user_id'] ?? 0;
$cart_items      = $_SESSION['checkout_cart_items'];
$shipping_name   = $_SESSION['shipping_name'] ?? '';
$shipping_address= $_SESSION['shipping_address'] ?? '';
$shipping_phone  = $_SESSION['shipping_phone'] ?? '';
$khalti_idx      = $resp['pidx'];
$purchase_time   = date('Y-m-d H:i:s');
$order_ref       = uniqid("ORD_");

// --- Insert into DB ---
$conn->autocommit(FALSE);

try {
    $sql = "INSERT INTO purchases
            (user_id, product_id, quantity, price, shipping_name, shipping_address, phone, payment_method, status, khalti_idx, purchase_date, order_reference_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $payment_method = 'Khalti';
    $status = 'Pending';

    foreach ($cart_items as $item) {
        $stmt->bind_param(
            "iiidssssssss",
            $user_id,
            $item['product_id'],
            $item['quantity'],
            $item['price'],
            $shipping_name,
            $shipping_address,
            $shipping_phone,
            $payment_method,
            $status,
            $khalti_idx,
            $purchase_time,
            $order_ref
        );
        $stmt->execute();
    }
    $stmt->close();

    // Clear cart
    $clear = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $clear->bind_param("i", $user_id);
    $clear->execute();
    $clear->close();

    $conn->commit();

    // Cleanup session
    unset($_SESSION['checkout_cart_items'], $_SESSION['checkout_total_amount'],
          $_SESSION['shipping_name'], $_SESSION['shipping_address'], $_SESSION['shipping_phone']);

    // ✅ Redirect to thankyou.php with order reference
    $_SESSION['flash_message'] = "🎉 Payment verified & order placed successfully!";
    header("Location: thankyou.php?order_ref=" . urlencode($order_ref));
    exit();

} catch (Exception $e) {
    $conn->rollback();
    error_log("Khalti order save failed: " . $e->getMessage());
    $_SESSION['flash_message'] = "❌ Order save failed: " . $e->getMessage();
    header("Location: checkout.php");
    exit();
}
?>
