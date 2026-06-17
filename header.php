<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="top-promo-bar">
    <div></div>

    <div class="top-sale-text">
        Discover what's new this season with our latest styles 
        <a href="products.php?new=1">right here.</a>
    </div>

    <div class="top-contact-link">
        <a href="https://wa.me/923435247548" target="_blank">Track</a>
    </div>
</div>

<header class="main-header">
    <button type="button" class="menu-icon" onclick="openMenu()" aria-label="Open Menu">
        ☰
    </button>

    <a href="index.php" class="logo">ShopZone</a>

    <nav class="category-nav">
        <a href="products.php?sale=1">Sale</a>
        <a href="products.php?new=1">New In</a>
        <a href="products.php?category=women">Ready To Wear</a>
        <a href="products.php?category=shirts">Fabrics</a>
        <a href="products.php?category=accessories">Fragrances</a>
        <a href="products.php">Now Happening</a>
    </nav>

    <div class="header-icons">
        <button type="button" class="search-toggle-btn" onclick="toggleSearchBox()" aria-label="Search">
            Search
        </button>

        <?php if(isset($_SESSION['user_id'])) { ?>
            <a href="cart.php" class="header-icon-link" title="Cart">
                <i class="fa-solid fa-bag-shopping"></i>
            </a>

            <a href="dashboard.php" class="header-text-link">Dashboard</a>
            <a href="logout.php" class="header-text-link">Logout</a>
        <?php } else { ?>
            <a href="login.php" class="header-text-link">Login</a>
            <a href="register.php" class="header-text-link">Register</a>
        <?php } ?>
    </div>
</header>

<div class="search-bar-box" id="searchBarBox">
    <form action="products.php" method="GET" class="main-search-form">
        <input 
            type="text" 
            name="search" 
            placeholder="Search products, dresses, shoes, bags..." 
            autocomplete="off"
            required
        >
        <button type="submit">Search</button>
    </form>
</div>

<div class="side-menu" id="sideMenu">
    <button type="button" class="close-menu" onclick="closeMenu()" aria-label="Close Menu">×</button>

    <a href="index.php">Home</a>
    <a href="products.php">All Products</a>
    <a href="products.php?new=1">New In</a>
    <a href="products.php?sale=1">Sale</a>
    <a href="products.php?category=women">Ready To Wear</a>
    <a href="products.php?category=men">Men Fashion</a>
    <a href="products.php?category=shirts">Shirts</a>
    <a href="products.php?category=shoes">Shoes</a>
    <a href="products.php?category=bags">Bags</a>
    <a href="products.php?category=accessories">Accessories</a>

    <?php if(isset($_SESSION['user_id'])) { ?>
        <a href="cart.php">My Bag</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    <?php } else { ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php } ?>

    <a href="https://wa.me/923435247548" target="_blank">WhatsApp Order</a>
</div>

<div class="menu-overlay" id="menuOverlay" onclick="closeMenu()"></div>