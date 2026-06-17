<?php
include("config.php");

$pageTitle = "All Products";
$pageSubtitle = "Explore our complete fashion collection";
$where = "WHERE 1";
$orderBy = "ORDER BY id DESC";

if (isset($_GET['search']) && trim($_GET['search']) != "") {
    $search = mysqli_real_escape_string($conn, trim($_GET['search']));
    $where .= " AND (name LIKE '%$search%' OR description LIKE '%$search%' OR category LIKE '%$search%')";
    $pageTitle = "Search Results";
    $pageSubtitle = "Showing results for: " . $search;
}

if (isset($_GET['category']) && trim($_GET['category']) != "") {
    $category = strtolower(mysqli_real_escape_string($conn, trim($_GET['category'])));
    $where .= " AND LOWER(category) = '$category'";

    if ($category == "men") {
        $pageTitle = "Men Fashion";
        $pageSubtitle = "Premium men's clothing and stylish essentials";
    } elseif ($category == "women") {
        $pageTitle = "Women Collection";
        $pageSubtitle = "Elegant outfits and modern women's fashion";
    } elseif ($category == "shirts") {
        $pageTitle = "Shirts Collection";
        $pageSubtitle = "Smart casual and formal shirts";
    } elseif ($category == "shoes") {
        $pageTitle = "Shoes Collection";
        $pageSubtitle = "Footwear that combines comfort with style";
    } elseif ($category == "bags") {
        $pageTitle = "Bags Collection";
        $pageSubtitle = "Carry your essentials with premium style";
    } elseif ($category == "accessories") {
        $pageTitle = "Accessories";
        $pageSubtitle = "Watches and fashion accessories";
    }
}

if (isset($_GET['sale']) && $_GET['sale'] == "1") {
    $where .= " AND is_sale = 1";
    $pageTitle = "Sale";
    $pageSubtitle = "Selected products up to 50% off";
}

if (isset($_GET['new']) && $_GET['new'] == "1") {
    $pageTitle = "New In";
    $pageSubtitle = "Fresh styles recently added to the store";
    $query = "SELECT * FROM products ORDER BY id DESC LIMIT 12";
} else {
    $query = "SELECT * FROM products $where $orderBy";
}

$result = mysqli_query($conn, $query);
if (!$result) die("Query Failed: " . mysqli_error($conn));
$totalProducts = mysqli_num_rows($result);

function encodeShopImagePath($path) {
    $parts = explode("/", str_replace("\\", "/", $path));
    $encodedParts = array_map("rawurlencode", $parts);
    return implode("/", $encodedParts);
}

function fallbackShopImage($category) {
    $category = strtolower(trim($category));
    if ($category == "women") return "https://images.pexels.com/photos/1488463/pexels-photo-1488463.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
    if ($category == "men") return "https://images.pexels.com/photos/6311392/pexels-photo-6311392.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
    if ($category == "shirts") return "https://images.pexels.com/photos/6311475/pexels-photo-6311475.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
    if ($category == "shoes") return "https://images.pexels.com/photos/2529148/pexels-photo-2529148.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
    if ($category == "bags") return "https://images.pexels.com/photos/1152077/pexels-photo-1152077.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
    if ($category == "accessories") return "https://images.pexels.com/photos/190819/pexels-photo-190819.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
    return "https://images.pexels.com/photos/5632402/pexels-photo-5632402.jpeg?auto=compress&cs=tinysrgb&w=900&h=1200&fit=crop";
}

function productImagePath($imageName, $category = "") {
    $imageName = trim((string)$imageName);
    if ($imageName == "") return fallbackShopImage($category);
    if (filter_var($imageName, FILTER_VALIDATE_URL)) return $imageName;

    $imageName = str_replace("\\", "/", $imageName);
    $possiblePaths = array($imageName, "images/products/" . $imageName, "images/" . $imageName, "uploads/" . $imageName, "assets/images/" . $imageName);
    foreach ($possiblePaths as $path) {
        if (file_exists($path)) return encodeShopImagePath($path);
    }
    return fallbackShopImage($category);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?> - ShopZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include("header.php"); ?>

<section class="k-products-page-head">
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    <p><?php echo htmlspecialchars($pageSubtitle); ?></p>
</section>

<nav class="k-filter-nav">
    <a href="products.php" class="<?php echo (!isset($_GET['sale']) && !isset($_GET['new']) && !isset($_GET['category']) && !isset($_GET['search'])) ? 'active' : ''; ?>">All</a>
    <a href="products.php?new=1" class="<?php echo (isset($_GET['new']) && $_GET['new'] == "1") ? 'active' : ''; ?>">New In</a>
    <a href="products.php?sale=1" class="<?php echo (isset($_GET['sale']) && $_GET['sale'] == "1") ? 'active' : ''; ?>">Sale</a>
    <a href="products.php?category=women" class="<?php echo (isset($_GET['category']) && strtolower($_GET['category']) == "women") ? 'active' : ''; ?>">Ready To Wear</a>
    <a href="products.php?category=men" class="<?php echo (isset($_GET['category']) && strtolower($_GET['category']) == "men") ? 'active' : ''; ?>">Men</a>
    <a href="products.php?category=shirts" class="<?php echo (isset($_GET['category']) && strtolower($_GET['category']) == "shirts") ? 'active' : ''; ?>">Shirts</a>
    <a href="products.php?category=shoes" class="<?php echo (isset($_GET['category']) && strtolower($_GET['category']) == "shoes") ? 'active' : ''; ?>">Shoes</a>
    <a href="products.php?category=bags" class="<?php echo (isset($_GET['category']) && strtolower($_GET['category']) == "bags") ? 'active' : ''; ?>">Bags</a>
    <a href="products.php?category=accessories" class="<?php echo (isset($_GET['category']) && strtolower($_GET['category']) == "accessories") ? 'active' : ''; ?>">Accessories</a>
</nav>

<div class="k-product-count-row">
    <span><?php echo $totalProducts; ?> Product(s)</span>
    <form action="products.php" method="GET" class="k-product-search">
        <input type="text" name="search" placeholder="Search products" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button type="submit">Search</button>
    </form>
</div>

<?php if (isset($_GET['sale']) && $_GET['sale'] == "1") { ?>
<section class="k-sale-page-banner">
    <h2>SALE</h2>
    <p>UP TO 50% OFF</p>
</section>
<?php } ?>

<section class="k-products-listing">
<?php if ($totalProducts > 0) { ?>
    <?php while ($row = mysqli_fetch_assoc($result)) {
        $id = (int)$row['id'];
        $name = $row['name'];
        $price = (float)$row['price'];
        $description = isset($row['description']) ? $row['description'] : "";
        $category = isset($row['category']) ? $row['category'] : "";
        $image = productImagePath(isset($row['image']) ? $row['image'] : "", $category);
        $isSale = isset($row['is_sale']) ? (int)$row['is_sale'] : 0;
        $salePrice = round($price / 2);
    ?>
    <article class="k-product-card listing-card">
        <a class="k-product-img" href="add_to_cart.php?id=<?php echo $id; ?>">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>">
            <span class="heart-btn">♡</span>
        </a>
        <div class="k-product-info">
            <span class="k-product-type"><?php echo htmlspecialchars($category == "" ? "Fashion" : $category); ?></span>
            <h3><?php echo htmlspecialchars($name); ?></h3>
            <p><?php echo htmlspecialchars($description); ?></p>
            <div class="k-price-row">
                <?php if ($isSale == 1) { ?>
                    <del>PKR <?php echo number_format($price); ?></del>
                    <strong>PKR <?php echo number_format($salePrice); ?></strong>
                    <em>50% OFF</em>
                <?php } else { ?>
                    <strong>PKR <?php echo number_format($price); ?></strong>
                    <em class="new-tag">New</em>
                <?php } ?>
            </div>
            <a class="k-add-bag" href="add_to_cart.php?id=<?php echo $id; ?>">Add To Bag</a>
        </div>
    </article>
    <?php } ?>
<?php } else { ?>
    <div class="k-empty-products">
        <h2>No Products Found</h2>
        <p>No products are available for this search or category right now.</p>
        <a href="products.php">View All Products</a>
    </div>
<?php } ?>
</section>

<a href="https://wa.me/923435247548" class="k-chat-float" target="_blank">▱</a>
<a href="#" class="k-top-float">⌃</a>

<script src="scripts.js?v=30000"></script>
</body>
</html>
