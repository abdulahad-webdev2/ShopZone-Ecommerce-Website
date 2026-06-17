<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orderTotal = 0;
$totalItems = 0;

/* Make sure total_amount column exists */
$checkColumn = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'total_amount'");
if ($checkColumn && mysqli_num_rows($checkColumn) == 0) {
    mysqli_query($conn, "ALTER TABLE orders ADD COLUMN total_amount DECIMAL(10,2) NOT NULL DEFAULT 0");
}

/* Make sure created_at column exists */
$checkCreatedAt = mysqli_query($conn, "SHOW COLUMNS FROM orders LIKE 'created_at'");
if ($checkCreatedAt && mysqli_num_rows($checkCreatedAt) == 0) {
    mysqli_query($conn, "ALTER TABLE orders ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
}

/* Get cart total */
$cartQuery = "
    SELECT 
        cart.id AS cart_id,
        cart.quantity,
        products.price,
        products.is_sale
    FROM cart
    INNER JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = '$user_id'
";

$cartResult = mysqli_query($conn, $cartQuery);

if (!$cartResult || mysqli_num_rows($cartResult) == 0) {
    header("Location: cart.php");
    exit();
}

while ($row = mysqli_fetch_assoc($cartResult)) {
    $price = (float)$row['price'];

    if ((int)$row['is_sale'] == 1) {
        $finalPrice = round($price / 2);
    } else {
        $finalPrice = $price;
    }

    $qty = (int)$row['quantity'];
    $totalItems += $qty;
    $orderTotal += $finalPrice * $qty;
}

/* Insert order */
$orderInsert = mysqli_query($conn, "
    INSERT INTO orders(user_id, total_amount)
    VALUES('$user_id', '$orderTotal')
");

/* Empty cart after successful order */
if ($orderInsert) {
    mysqli_query($conn, "DELETE FROM cart WHERE user_id='$user_id'");
} else {
    die("Order Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Successful - ShopZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include("header.php"); ?>

<main class="premium-checkout-page">

    <section class="premium-success-card">
        <div class="success-glow"></div>

        <div class="premium-success-icon">
            <i class="fa-solid fa-check"></i>
        </div>

        <span class="checkout-label">Order Confirmed</span>

        <h1>Order Successful</h1>

        <p>
            Thank you for shopping with <strong>ShopZone</strong>. 
            Your order has been placed successfully. Our team will contact you soon for confirmation.
        </p>

        <div class="premium-order-details">
            <div>
                <span>Total Items</span>
                <strong><?php echo $totalItems; ?></strong>
            </div>

            <div>
                <span>Order Total</span>
                <strong>Rs <?php echo number_format($orderTotal); ?></strong>
            </div>
        </div>

        <div class="premium-checkout-actions">
            <a href="products.php" class="checkout-main-btn">
                <i class="fa-solid fa-store"></i>
                Continue Shopping
            </a>

            <a href="index.php" class="checkout-light-btn">
                <i class="fa-solid fa-house"></i>
                Back To Home
            </a>
        </div>
    </section>

</main>

<a href="https://wa.me/923435247548" class="k-chat-float" target="_blank">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<a href="#" class="k-top-float">
    <i class="fa-solid fa-chevron-up"></i>
</a>

<script src="scripts.js?v=<?php echo time(); ?>"></script>
</body>
</html>