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
        /* Added for error styling */
        .error-message { color: red; font-size: 0.9em; margin-top: -5px; margin-bottom: 10px; display: none; }
        input:invalid:not(:placeholder-shown) { border-color: red; } /* Highlight invalid fields */
        input:valid:not(:placeholder-shown) { border-color: green; } /* Highlight valid fields */
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
        <form id="checkoutForm" class="checkout-form" method="POST" action="process_order.php" novalidate> <!-- Added novalidate to handle all validation in JS -->
            <h3>Shipping Information</h3>
            <input type="text" name="shipping_name" id="shipping_name" placeholder="Full Name (e.g., Ram Bahadur)" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required minlength="2" pattern="^[\p{L}\s',.-]+$" title="Name should contain letters, spaces, hyphens, periods, or apostrophes." autofocus>
            <p id="nameErrorMessage" class="error-message">Please enter a valid full name (letters, spaces, periods, hyphens, apostrophes only).</p>

            <input type="text" name="shipping_address" id="shipping_address" placeholder="Full Address (e.g., Kathmandu-10, Buddhanagar)" required minlength="5" pattern="^[\p{L}\p{N}\s',./\\#-]+$" title="Address should contain letters, numbers, spaces, and common punctuation.">
            <p id="addressErrorMessage" class="error-message">Please enter a valid address (min 5 characters, allows letters, numbers, spaces, and common punctuation).</p>

            <input type="text" name="shipping_phone" id="shipping_phone" placeholder="Phone Number (e.g., 98XXXXXXXX)" required pattern="^(9[678]\d{8}|[0-9]{9,10})$" title="Enter a valid Nepali phone number (e.g., 98XXXXXXXX)">
            <p id="phoneErrorMessage" class="error-message">Please enter a valid 10-digit Nepali mobile number (starting with 98, 97, or 96) or a 9-10 digit landline/other number.</p>

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
    const checkoutForm = document.getElementById('checkoutForm');
    const nameInput = document.getElementById('shipping_name');
    const addressInput = document.getElementById('shipping_address');
    const phoneInput = document.getElementById('shipping_phone');

    const nameErrorMessage = document.getElementById('nameErrorMessage');
    const addressErrorMessage = document.getElementById('addressErrorMessage');
    const phoneErrorMessage = document.getElementById('phoneErrorMessage');

    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    // const khaltiHint = document.getElementById('khalti_hint'); // Uncomment if you have this element

    // Regex patterns (should match HTML pattern attributes)
    const nameRegex = /^[\p{L}\s',.-]+$/u; // \p{L} for any unicode letter, 'u' flag for unicode
    const addressRegex = /^[\p{L}\p{N}\s',./\\#-]+$/u; // \p{N} for any unicode number
    const nepaliPhoneRegex = /^(9[678]\d{8}|[0-9]{9,10})$/;


    // Function to validate an input field
    function validateInput(inputElement, errorMessageElement, regex, customMsg) {
        const value = inputElement.value.trim();
        let isValid = true;
        let msg = customMsg;

        if (inputElement.required && value === '') {
            isValid = false;
            msg = customMsg || "This field is required."; // Use default if custom not provided
        } else if (regex && !regex.test(value)) {
            isValid = false;
            msg = customMsg || "Please enter a valid format.";
        } else if (inputElement.minLength && value.length < inputElement.minLength) {
            isValid = false;
            msg = `Please enter at least ${inputElement.minLength} characters.`;
        }

        if (isValid) {
            errorMessageElement.style.display = 'none';
            inputElement.setCustomValidity("");
        } else {
            errorMessageElement.textContent = msg;
            errorMessageElement.style.display = 'block';
            inputElement.setCustomValidity(msg); // Set custom validity for HTML5 validation pop-ups
        }
        return isValid;
    }

    // Attach input event listeners for dynamic validation
    nameInput.addEventListener('input', () => {
        validateInput(nameInput, nameErrorMessage, nameRegex, "Please enter a valid full name (letters, spaces, periods, hyphens, apostrophes only, min 2 chars).");
    });
    addressInput.addEventListener('input', () => {
        validateInput(addressInput, addressErrorMessage, addressRegex, "Please enter a valid address (min 5 characters, allows letters, numbers, spaces, and common punctuation).");
    });
    phoneInput.addEventListener('input', () => {
        validateInput(phoneInput, phoneErrorMessage, nepaliPhoneRegex, "Please enter a valid 10-digit Nepali mobile (98XXXXXXXX, 97XXXXXXXX, 96XXXXXXXX) or a 9-10 digit landline/other number.");
    });

    // Toggle Khalti Hint (if applicable)
    function toggleKhaltiHint() {
        if (document.querySelector('input[name="payment_method"][value="Khalti"]:checked')) {
            // khaltiHint.style.display = 'block';
        } else {
            // khaltiHint.style.display = 'none';
        }
    }
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', toggleKhaltiHint);
    });
    toggleKhaltiHint(); // Set on load

    // --- Form Submission Validation ---
    checkoutForm.addEventListener('submit', function(e) {
        // Run all validations on submit
        const isNameValid = validateInput(nameInput, nameErrorMessage, nameRegex, "Please enter a valid full name (letters, spaces, periods, hyphens, apostrophes only, min 2 chars).");
        const isAddressValid = validateInput(addressInput, addressErrorMessage, addressRegex, "Please enter a valid address (min 5 characters, allows letters, numbers, spaces, and common punctuation).");
        const isPhoneValid = validateInput(phoneInput, phoneErrorMessage, nepaliPhoneRegex, "Please enter a valid 10-digit Nepali mobile (98XXXXXXXX, 97XXXXXXXX, 96XXXXXXXX) or a 9-10 digit landline/other number.");

        if (!isNameValid || !isAddressValid || !isPhoneValid) {
            e.preventDefault(); // Stop form submission if any field is invalid
            alert("Please correct the errors in the form."); // General alert
        }
        // If all are valid, the form will submit normally
    });
});
</script>
</body>
</html>