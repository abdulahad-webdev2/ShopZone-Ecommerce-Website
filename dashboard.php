<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$totalProducts = 0;
$totalUsers = 0;
$totalOrders = 0;

$productResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
if ($productResult) {
    $totalProducts = mysqli_fetch_assoc($productResult)['total'];
}

$userResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
if ($userResult) {
    $totalUsers = mysqli_fetch_assoc($userResult)['total'];
}

$orderResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
if ($orderResult) {
    $totalOrders = mysqli_fetch_assoc($orderResult)['total'];
}

$userName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - ShopZone</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<?php include("header.php"); ?>

<main class="premium-dashboard">

    <section class="dashboard-hero-premium">
        <div class="dashboard-hero-content">
            <span class="dashboard-badge">Admin Panel</span>
            <h1>Store Dashboard</h1>
            <p>
                Welcome back, <?php echo htmlspecialchars($userName); ?>. Manage your products, users,
                orders and store activity from one clean premium dashboard.
            </p>

            <div class="dashboard-hero-actions">
                <a href="add_product.php">
                    <i class="fa-solid fa-plus"></i>
                    Add Product
                </a>

                <a href="products.php">
                    <i class="fa-solid fa-store"></i>
                    View Products
                </a>

                <a href="cart.php">
                    <i class="fa-solid fa-bag-shopping"></i>
                    View Bag
                </a>
            </div>
        </div>

        <div class="dashboard-hero-card">
            <i class="fa-solid fa-chart-line"></i>
            <h3>ShopZone</h3>
            <p>Admin Control Center</p>
        </div>
    </section>

    <section class="dashboard-stats-premium">
        <div class="dashboard-stat-card">
            <div class="stat-icon products-icon">
                <i class="fa-solid fa-box-open"></i>
            </div>

            <div>
                <span>Total Products</span>
                <h3><?php echo $totalProducts; ?></h3>
                <p>Products available in your store.</p>
            </div>
        </div>

        <div class="dashboard-stat-card">
            <div class="stat-icon users-icon">
                <i class="fa-solid fa-users"></i>
            </div>

            <div>
                <span>Total Users</span>
                <h3><?php echo $totalUsers; ?></h3>
                <p>Registered customers on ShopZone.</p>
            </div>
        </div>

        <div class="dashboard-stat-card">
            <div class="stat-icon orders-icon">
                <i class="fa-solid fa-receipt"></i>
            </div>

            <div>
                <span>Total Orders</span>
                <h3><?php echo $totalOrders; ?></h3>
                <p>Orders placed by customers.</p>
            </div>
        </div>
    </section>

    <section class="admin-section premium-admin-section">
        <div class="admin-section-header">
            <div>
                <span>Quick Overview</span>
                <h2>Store Management</h2>
            </div>

            <a href="products.php" class="page-btn">
                Browse Store
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="admin-info-grid">
            <div>
                <i class="fa-solid fa-shirt"></i>
                <h4>Products</h4>
                <p>Add new products, update sale items, manage product images and organize categories.</p>
            </div>

            <div>
                <i class="fa-solid fa-user-check"></i>
                <h4>Users</h4>
                <p>Track registered customers and keep your ecommerce project organized.</p>
            </div>

            <div>
                <i class="fa-solid fa-truck-fast"></i>
                <h4>Orders</h4>
                <p>Review customer orders and improve your delivery and shopping process.</p>
            </div>
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