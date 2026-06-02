<?php
session_start();
include "db.php";

if (!isset($_GET['product_id'])) {
    header("Location: index.php");
    exit();
}

$product_id = intval($_GET['product_id']);

$sql = "SELECT id, stock FROM products WHERE id = '$product_id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    header("Location: index.php");
    exit();
}

$row = mysqli_fetch_assoc($result);
$current_stock = intval($row['stock']);

$cart = $_SESSION['cart'] ?? [];
$current_qty = isset($cart[$product_id]) ? intval($cart[$product_id]) : 0;

if ($current_qty + 1 > $current_stock) {
    // If requested quantity exceeds stock, do not add more.
    header("Location: cart.php");
    exit();
}

$cart[$product_id] = $current_qty + 1;
$_SESSION['cart'] = $cart;

$redirect = 'index.php';
if (isset($_GET['redirect'])) {
    $allowed = ['index.php', 'productdetails.php', 'cart.php'];
    $candidate = basename($_GET['redirect']);
    if (in_array($candidate, $allowed)) {
        $redirect = $candidate;
    }
}

header("Location: $redirect");
exit();
?>