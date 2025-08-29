<?php
include 'db.php'; 
session_name("admin_session");
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: signin.php");
    exit();
}

// Fetch total sales and revenue for Dispatched orders
$salesQuery = "
    SELECT COUNT(id) AS total_sales, SUM(quantity * price) AS total_revenue 
    FROM purchases 
    WHERE LOWER(TRIM(status)) = 'dispatched'
";
$salesResult = mysqli_query($conn, $salesQuery);

if (!$salesResult) {
    die("Query failed: " . mysqli_error($conn));
}

$salesData = mysqli_fetch_assoc($salesResult);
$total_sales = $salesData['total_sales'] ?? 0;
$total_revenue = $salesData['total_revenue'] ?? 0;

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Summary - Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <?php include 'header.php'; ?>
        <main class="admin-content">
            <h1>Sales Summary</h1>
            <div class="admin-cards">
                <div class="card sales-summary">
                    <h2>Total Sales</h2>
                    <p><strong><?= $total_sales ?> Orders</strong></p>
                    <p><small class="muted">Orders with status: Dispatched</small></p>
                </div>
                <div class="card revenue-summary">
                    <h2>Total Revenue</h2>
                    <p><strong>Rs.<?= number_format($total_revenue, 2) ?></strong></p>
                    <p><small class="muted">Revenue from Dispatched orders</small></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
