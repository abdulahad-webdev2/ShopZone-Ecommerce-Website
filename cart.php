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

/* Remove item */
if (isset($_GET['remove'])) {
    $remove_id = mysqli_real_escape_string($conn, $_GET['remove']);
    mysqli_query($conn, "DELETE FROM cart WHERE id='$remove_id' AND user_id='$user_id'");
    header("Location: cart.php");
    exit();
}

/* Update quantity */
if (isset($_POST['update_cart'])) {
    if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
        foreach ($_POST['quantity'] as $cart_id => $qty) {
            $cart_id = mysqli_real_escape_string($conn, $cart_id);
            $qty = (int)$qty;

            if ($qty < 1) {
                $qty = 1;
            }

            mysqli_query($conn, "UPDATE cart SET quantity='$qty' WHERE id='$cart_id' AND user_id='$user_id'");
        }
    }

    header("Location: cart.php");
    exit();
}

$query = "
    SELECT 
        cart.id AS cart_id,
        cart.quantity,
        products.id AS product_id,
        products.name,
        products.price,
        products.image,
        products.category,
        products.is_sale
    FROM cart
    INNER JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = '$user_id'
    ORDER BY cart.id DESC
";

$result = mysqli_query($conn, $query);

$totalAmount = 0;
$totalItems = 0;

function cartImagePath($imageName, $category = "") {
    $imageName = trim((string)$imageName);

    if ($imageName == "") {
        return "img5.jpg";
    }

    if (filter_var($imageName, FILTER_VALIDATE_URL)) {
        return $imageName;
    }

    $imageName = str_replace("\\", "/", $imageName);

    $possiblePaths = array(
        $imageName,
        "images/products/" . $imageName,
        "images/" . $imageName,
        "uploads/" . $imageName,
        "assets/images/" . $imageName
    );

    foreach ($possiblePaths as $path) {
        if (file_exists($path)) {
            $parts = explode("/", str_replace("\\", "/", $path));
            $encodedParts = array_map("rawurlencode", $parts);
            return implode("/", $encodedParts);
        }
    }

    return "img5.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bag - ShopZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include("header.php"); ?>

<main class="premium-cart-page">

    <section class="cart-hero-premium">
        <span>Home / Bag</span>
        <h1>My Bag</h1>
        <p>Review your selected products before checkout and update quantities easily.</p>
    </section>

    <?php if ($result && mysqli_num_rows($result) > 0) { ?>

        <form method="POST" action="cart.php" class="premium-cart-layout">

            <section class="premium-cart-list">
                <div class="cart-list-head">
                    <h2>Shopping Bag</h2>
                    <p>Your selected items are ready for checkout.</p>
                </div>

                <?php while ($row = mysqli_fetch_assoc($result)) { 
                    $price = (float)$row['price'];

                    if ((int)$row['is_sale'] == 1) {
                        $finalPrice = round($price / 2);
                    } else {
                        $finalPrice = $price;
                    }

                    $quantity = (int)$row['quantity'];
                    $subtotal = $finalPrice * $quantity;
                    $totalAmount += $subtotal;
                    $totalItems += $quantity;
                    $image = cartImagePath($row['image'], $row['category']);
                ?>

                    <article class="premium-cart-item">
                        <div class="cart-item-image">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        </div>

                        <div class="cart-item-details">
                            <span class="cart-category">
                                <?php echo htmlspecialchars($row['category'] == "" ? "ShopZone Product" : $row['category']); ?>
                            </span>

                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>

                            <?php if ((int)$row['is_sale'] == 1) { ?>
                                <span class="sale-pill">50% OFF Sale Item</span>
                            <?php } else { ?>
                                <span class="new-pill">Premium Product</span>
                            <?php } ?>

                            <div class="cart-mobile-price">
                                <?php if ((int)$row['is_sale'] == 1) { ?>
                                    <del>Rs <?php echo number_format($price); ?></del>
                                <?php } ?>
                                <strong>Rs <?php echo number_format($finalPrice); ?></strong>
                            </div>
                        </div>

                        <div class="cart-price-box">
                            <span>Price</span>
                            <?php if ((int)$row['is_sale'] == 1) { ?>
                                <del>Rs <?php echo number_format($price); ?></del>
                            <?php } ?>
                            <strong>Rs <?php echo number_format($finalPrice); ?></strong>
                        </div>

                        <div class="cart-qty-box">
                            <span>Quantity</span>
                            <input 
                                type="number" 
                                name="quantity[<?php echo $row['cart_id']; ?>]" 
                                value="<?php echo $quantity; ?>" 
                                min="1" 
                                class="cart-qty"
                            >
                        </div>

                        <div class="cart-subtotal-box">
                            <span>Subtotal</span>
                            <strong>Rs <?php echo number_format($subtotal); ?></strong>
                        </div>

                        <a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="cart-remove-btn">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </article>

                <?php } ?>
            </section>

            <aside class="premium-cart-summary">
                <div class="summary-card">
                    <span class="summary-label">Order Summary</span>
                    <h2>Checkout Details</h2>

                    <div class="summary-row">
                        <span>Total Items</span>
                        <strong><?php echo $totalItems; ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Subtotal</span>
                        <strong>Rs <?php echo number_format($totalAmount); ?></strong>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <strong>Starting Rs 130</strong>
                    </div>

                    <div class="summary-total">
                        <span>Total Amount</span>
                        <strong>Rs <?php echo number_format($totalAmount); ?></strong>
                    </div>

                    <button type="submit" name="update_cart" class="summary-btn secondary">
                        <i class="fa-solid fa-rotate"></i>
                        Update Bag
                    </button>

                    <a href="checkout.php" class="summary-btn primary">
                        <i class="fa-solid fa-credit-card"></i>
                        Checkout
                    </a>

                    <a href="products.php" class="continue-link">
                        Continue Shopping
                    </a>
                </div>
            </aside>

        </form>

    <?php } else { ?>

        <section class="premium-empty-cart">
            <div class="empty-cart-icon">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>

            <h2>Your Bag Is Empty</h2>
            <p>Go to products page and add some premium items to your shopping bag.</p>

            <a href="products.php">
                <i class="fa-solid fa-store"></i>
                Shop Products
            </a>
        </section>

    <?php } ?>

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