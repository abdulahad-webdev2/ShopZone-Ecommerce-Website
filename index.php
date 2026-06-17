<?php
include("config.php");

function khaadiImagePath($imageName, $category = "") {
    $imageName = trim((string)$imageName);
    $category = strtolower(trim((string)$category));

    $fallbacks = array(
        "women" => "img5.jpg",
        "men" => "img6.jpg",
        "shirts" => "img7.jpg",
        "shoes" => "img5.jpg",
        "bags" => "img6.jpg",
        "accessories" => "img7.jpg"
    );

    $fallback = isset($fallbacks[$category]) ? $fallbacks[$category] : "img5.jpg";

    if ($imageName == "") {
        return $fallback;
    }

    if (filter_var($imageName, FILTER_VALIDATE_URL)) {
        return $imageName;
    }

    $imageName = str_replace("\\", "/", $imageName);

    $paths = array(
        $imageName,
        "images/products/" . $imageName,
        "images/" . $imageName,
        "uploads/" . $imageName,
        "assets/images/" . $imageName
    );

    foreach ($paths as $path) {
        if (file_exists($path)) {
            $parts = explode("/", $path);
            $parts = array_map("rawurlencode", $parts);
            return implode("/", $parts);
        }
    }

    return $fallback;
}

function renderHomeProductCard($row) {
    $id = (int)$row['id'];
    $name = $row['name'];
    $price = (float)$row['price'];
    $category = isset($row['category']) ? $row['category'] : "";
    $image = khaadiImagePath(isset($row['image']) ? $row['image'] : "", $category);
    $isSale = isset($row['is_sale']) ? (int)$row['is_sale'] : 0;
    $salePrice = round($price / 2);
    ?>
    <article class="k-product-card">
        <a class="k-product-img" href="add_to_cart.php?id=<?php echo $id; ?>">
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>">
            <span class="heart-btn">♡</span>
        </a>

        <div class="k-product-info">
            <span class="k-product-type">
                <?php echo htmlspecialchars($category == "" ? "Fashion" : $category); ?>
            </span>

            <h3><?php echo htmlspecialchars($name); ?></h3>

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
        </div>
    </article>
    <?php
}

$topProducts = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 5");
$bestProducts = mysqli_query($conn, "SELECT * FROM products ORDER BY id ASC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ShopZone - Home</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Cache Clear -->
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include("header.php"); ?>

<!-- HERO SECTION -->
<section class="k-hero-sale">
    <img src="img1.jpg" alt="Sale Collection">

    <div class="k-hero-overlay"></div>

    <div class="k-hero-text">
        <span>Festive Collection</span>
        <h1>SALE</h1>
        <h2>UP TO 50% OFF</h2>
        <a href="products.php?sale=1">Shop Sale</a>
    </div>
</section>

<!-- TOP PICKS HEADING -->
<section class="k-section-head">
    <h2>Top Picks for You</h2>
    <p>We've handpicked the styles we know you'll love. Explore what's trending now.</p>
</section>

<!-- TOP PICKS IMAGES -->
<section class="k-editorial-grid">
    <a href="products.php?category=women" class="k-editorial-card">
        <img src="img5.jpg" alt="Ready To Wear">
        <span>Ready To Wear</span>
    </a>

    <a href="products.php?new=1" class="k-editorial-card">
        <img src="img6.jpg" alt="New Arrivals">
        <span>New Arrivals</span>
    </a>

    <a href="products.php?sale=1" class="k-editorial-card">
        <img src="img7.jpg" alt="Sale Picks">
        <span>Sale Picks</span>
    </a>
</section>

<!-- TOP PRODUCTS -->
<section class="k-products-strip">
    <?php if ($topProducts && mysqli_num_rows($topProducts) > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($topProducts)) { renderHomeProductCard($row); } ?>
    <?php } else { ?>
        <div class="k-empty-home">No products found. Add products from admin panel.</div>
    <?php } ?>
</section>

<!-- BESTSELLERS HEADING -->
<section class="k-section-head best-head">
    <h2>Bestsellers</h2>
    <p>Discover this season’s favorites and refresh your style with looks you’ll wear on repeat.</p>
</section>

<!-- WIDE BANNER -->
<section class="k-wide-banner">
    <div class="k-wide-img">
        <img src="img7.jpg" alt="Premium Picks">

        <div class="k-wide-content">
            <span>Premium Picks</span>
            <h2>Wear Your Best Look</h2>
            <a href="products.php">Explore Collection</a>
        </div>
    </div>
</section>

<!-- BEST PRODUCTS -->
<section class="k-products-strip best-products">
    <?php if ($bestProducts && mysqli_num_rows($bestProducts) > 0) { ?>
        <?php while ($row = mysqli_fetch_assoc($bestProducts)) { renderHomeProductCard($row); } ?>
    <?php } else { ?>
        <div class="k-empty-home">No products found. Add products from admin panel.</div>
    <?php } ?>
</section>

<!-- SERVICE STRIP -->
<section class="k-service-strip">
    <div onclick="showFooterInfo('shipping')">
        <span><i class="fa-solid fa-truck"></i></span>
        <strong>SHIPPING CHARGES</strong>
        <p>Starting from Rs. 130</p>
    </div>

    <div onclick="showFooterInfo('track')">
        <span><i class="fa-solid fa-bag-shopping"></i></span>
        <strong>TRACK YOUR ORDER</strong>
        <p>Check status of your order.</p>
    </div>

    <div onclick="showFooterInfo('stores')">
        <span><i class="fa-solid fa-location-dot"></i></span>
        <strong>FIND STORES</strong>
        <p>Stores countrywide across Pakistan.</p>
    </div>
</section>

<!-- FOOTER INFO BOX -->
<section class="footer-info-box" id="footerInfoBox">
    <button type="button" class="footer-info-close" onclick="closeFooterInfo()">×</button>
    <h2 id="footerInfoTitle">Welcome to ShopZone</h2>
    <p id="footerInfoText">
        Welcome to ShopZone, your trusted destination for quality products at unbeatable prices.
    </p>
</section>

<!-- FOOTER -->
<footer class="k-footer">
    <div class="k-help-row">
        <button type="button" onclick="showFooterInfo('help')">NEED HELP?</button>
        <button type="button" onclick="showFooterInfo('faqs')">FAQS</button>
    </div>

    <div class="k-footer-main">
        <div>
            <h4>HELP</h4>
            <button type="button" onclick="showFooterInfo('faqs')">Frequently Asked Questions</button>
            <button type="button" onclick="showFooterInfo('terms')">Terms & Conditions</button>
            <button type="button" onclick="showFooterInfo('privacy')">Privacy Policy</button>
            <button type="button" onclick="showFooterInfo('disclaimer')">Disclaimer</button>
            <button type="button" onclick="showFooterInfo('contact')">Contact Us</button>
        </div>

        <div>
            <h4>MORE FROM SHOPZONE</h4>
            <button type="button" onclick="showFooterInfo('about')">About Us</button>
            <button type="button" onclick="showFooterInfo('blogs')">Blogs</button>
            <button type="button" onclick="showFooterInfo('cloth')">Cloth Care</button>
        </div>

        <div>
            <h4>OUR SOCIALS</h4>

            <div class="k-socials">
                <a href="https://www.tiktok.com/" target="_blank" title="TikTok">
                    <i class="fa-brands fa-tiktok"></i>
                </a>

                <a href="https://www.youtube.com/" target="_blank" title="YouTube">
                    <i class="fa-brands fa-youtube"></i>
                </a>

                <a href="https://www.facebook.com/" target="_blank" title="Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>

                <a href="https://www.instagram.com/a_ahad2428" target="_blank" title="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>

            <h4>CONTACT US</h4>

            <div class="footer-contact-icons">
                <a href="https://wa.me/923435247548" target="_blank" title="WhatsApp" class="contact-whatsapp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>

                <a href="mailto:kabdulahad576@gmail.com" title="Email" class="contact-email">
                    <i class="fa-solid fa-envelope"></i>
                </a>

                <a href="https://www.instagram.com/a_ahad2428" target="_blank" title="Instagram" class="contact-instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>

            <h4>GET THE LATEST NEWS</h4>

            <form class="k-newsletter">
                <input type="email" placeholder="Email Address">
                <button type="button" onclick="showFooterInfo('newsletter')">CONFIRM</button>
            </form>
        </div>
    </div>

    <div class="shopzone-description">
        <h3>About ShopZone</h3>
        <p>
            Welcome to ShopZone, your trusted destination for quality products at unbeatable prices.
            Founded by Abdul Ahad, ShopZone is dedicated to bringing you a smooth, secure, and enjoyable
            online shopping experience. From fashion and electronics to daily essentials, we offer everything
            you need in one place. Our mission is to provide top-quality products, fast delivery, and excellent
            customer service to make every shopping experience convenient and satisfying.
        </p>
    </div>

    <div class="k-footer-bottom">
        <strong>ShopZone</strong>
        <span>Copyright © 2026 ShopZone. All Rights Reserved.</span>
    </div>
</footer>

<a href="https://wa.me/923435247548" class="k-chat-float" target="_blank">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<a href="#" class="k-top-float">
    <i class="fa-solid fa-chevron-up"></i>
</a>

<script src="scripts.js?v=<?php echo time(); ?>"></script>
</body>
</html>