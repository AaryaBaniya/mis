<?php
/**
 * Global configuration for DB + Khalti
 * Drop this project into: C:/xampp/htdocs/mis/
 * DB name: brasshub
 */

// ---------- DATABASE ----------
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "brasshub";

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// ---------- KHALTI MODE ----------
// 'sandbox' uses https://dev.khalti.com/...
// 'live'    uses https://khalti.com/...
$KHALTI_MODE = 'sandbox';

// Put your keys here (sandbox keys for sandbox mode; live keys for live mode)
$KHALTI_SECRET_KEY = "YOUR_SANDBOX_SECRET_KEY";
$KHALTI_PUBLIC_KEY = "YOUR_SANDBOX_PUBLIC_KEY";

// If you want LIVE, change mode and keys:
// $KHALTI_MODE = 'live';
// $KHALTI_SECRET_KEY = "YOUR_LIVE_SECRET_KEY";
// $KHALTI_PUBLIC_KEY = "YOUR_LIVE_PUBLIC_KEY";

// ---------- URLS ----------
$BASE_URL   = "http://localhost/mis";
$RETURN_URL = $BASE_URL . "/khalti_verify.php";
$WEBSITE_URL= $BASE_URL;

// Helper: current user id from session (supports multiple keys)
function current_user_id() {
    if (!empty($_SESSION['user_id'])) return intval($_SESSION['user_id']);
    if (!empty($_SESSION['sid']))     return intval($_SESSION['sid']);
    if (!empty($_SESSION['id']))      return intval($_SESSION['id']);
    return 0;
}

// Helper: get API endpoints based on mode
function khalti_endpoint($path) {
    global $KHALTI_MODE;
    if ($KHALTI_MODE === 'live') {
        // (Live base for newer ePayment v2 endpoints is https://khalti.com/api/v2)
        return "https://khalti.com/api/v2" . $path;
    }
    return "https://dev.khalti.com/api/v2" . $path; // sandbox
}
?>