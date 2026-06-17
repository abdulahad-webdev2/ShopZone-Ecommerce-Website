<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

if (!isset($_GET['id']) || $_GET['id'] == "") {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];

if ($product_id <= 0) {
    header("Location: products.php");
    exit();
}

/* Check product exists */
$productStmt = mysqli_prepare($conn, "SELECT id FROM products WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($productStmt, "i", $product_id);
mysqli_stmt_execute($productStmt);
$productResult = mysqli_stmt_get_result($productStmt);

if (!$productResult || mysqli_num_rows($productResult) == 0) {
    header("Location: products.php");
    exit();
}

/* Check if product already exists in cart */
$cartStmt = mysqli_prepare($conn, "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ? LIMIT 1");
mysqli_stmt_bind_param($cartStmt, "ii", $user_id, $product_id);
mysqli_stmt_execute($cartStmt);
$cartResult = mysqli_stmt_get_result($cartStmt);

if ($cartResult && mysqli_num_rows($cartResult) > 0) {
    $cartItem = mysqli_fetch_assoc($cartResult);
    $cart_id = (int)$cartItem['id'];

    $updateStmt = mysqli_prepare($conn, "UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($updateStmt, "ii", $cart_id, $user_id);
    mysqli_stmt_execute($updateStmt);
} else {
    $insertStmt = mysqli_prepare($conn, "INSERT INTO cart(user_id, product_id, quantity) VALUES(?, ?, 1)");
    mysqli_stmt_bind_param($insertStmt, "ii", $user_id, $product_id);
    mysqli_stmt_execute($insertStmt);
}

header("Location: cart.php");
exit();
?>