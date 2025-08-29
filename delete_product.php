<?php
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id = $id");
header("Location: manage_products.php");
exit;
?>
