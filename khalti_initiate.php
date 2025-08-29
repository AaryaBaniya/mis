<?php
session_name("user_session");
session_start();

// --- Sandbox Secret Key ---
define('KHALTI_SECRET_KEY', 'fbbd05a8499348c688ed2db97050b2de'); 

$total_amount = $_SESSION['checkout_total_amount'] ?? 0;
if ($total_amount <= 0) {
    $_SESSION['flash_message'] = "❌ Invalid total amount.";
    header("Location: checkout.php");
    exit;
}

$amount_paisa = intval($total_amount * 100);

$data = [
    "return_url" => "http://localhost/mis/khalti_return.php",
    "website_url" => "http://localhost/mis",
    "amount" => $amount_paisa,
    "purchase_order_id" => uniqid(),
    "purchase_order_name" => "BrassHub Order",
    "customer_info" => [
        "name"  => "Test User",            // Sandbox test name
        "email" => "test@example.com",     // Sandbox test email
        "phone" => "9800000000"            // Sandbox fixed test number
    ]
];

$ch = curl_init("https://dev.khalti.com/api/v2/epayment/initiate/");
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER => [
        "Authorization: Key " . KHALTI_SECRET_KEY,
        "Content-Type: application/json"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_RETURNTRANSFER => true
]);

$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$res_data = json_decode($response, true);

if ($http_status == 200 && isset($res_data['payment_url'])) {
    header("Location: " . $res_data['payment_url']);
    exit;
} else {
    $_SESSION['flash_message'] = "❌ Khalti initiation failed: " . htmlspecialchars($response);
    header("Location: checkout.php");
    exit;
}
?>
