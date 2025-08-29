<?php
session_name("user_session");
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$payment_method = $_POST['payment_method'];

// --- Get Cart Items (Your code is fine here) ---
$sql = "SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($cart_items)) {
    $_SESSION['flash_message'] = "⚠️ Your cart is empty.";
    header("Location: shop.php");
    exit();
}

// === FIX STARTS HERE: WRAP EVERYTHING IN A TRANSACTION ===

// 1. Disable autocommit to start the transaction
$conn->autocommit(FALSE);

try {
    // 2. Prepare the insert statement ONCE, outside the loop
    $insert_sql = "INSERT INTO purchases 
      (user_id, product_id, quantity, price, purchase_date, status, shipping_name, shipping_address, phone, payment_method) 
      VALUES (?, ?, ?, ?, NOW(), 'Pending', ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);

    // 3. Loop through cart items and execute the prepared statement
    foreach ($cart_items as $item) {
        // Bind parameters INSIDE the loop for each item's data
        $insert_stmt->bind_param("iiidssss", 
            $user_id, 
            $item['product_id'], 
            $item['quantity'], 
            $item['price'], 
            $name, $address, $phone, $payment_method
        );

        // Execute for each item
        if (!$insert_stmt->execute()) {
            // If one item fails, throw an exception to trigger the rollback
            throw new Exception("Order insert failed: " . $insert_stmt->error);
        }
    }
    $insert_stmt->close();

    $delete_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $delete_stmt->bind_param("i", $user_id);
    if (!$delete_stmt->execute()) {
        throw new Exception("Failed to clear cart: " . $delete_stmt->error);
    }
    $delete_stmt->close();
    $conn->commit();
    $_SESSION['flash_message'] = "🎉 Order placed successfully!";
    header("Location: purchase_history.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    error_log("Checkout transaction failed: " . $e->getMessage());
    $_SESSION['flash_message'] = "❌ There was a problem placing your order. Please try again.";
    header("Location: checkout.php"); 
    exit();
}
?>