<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';

// Initialize ProductManager
$productManager = new ProductManager($pdo);

// Get featured products (limit to 2 for the home page)
$products = $productManager->getAllProducts();
if (count($products) > 2) {
    $products = array_slice($products, 0, 2); // Limit to first 2 products
}

$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Soundex Audio Solutions</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/footer.css" />
</head>

<body>

    <!-- Navigation Header -->
    <?php include '../includes/header.php'; ?>

    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Experience Sound Like Never Before</h1>
                <p>Premium audio solutions for the audiophile in you.</p>
                <a href="../pages/buy.php" class="cta-button">Shop Now</a>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="new-speakers section-padding">
            <h2 class="section-title">Latest Arrivals</h2>
            <?php if (!empty($products)): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <a href="../pages/product_detail.php?id=<?php echo $product['id']; ?>" class="product-card">
                            <div class="product-image-wrapper">
                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? '/Bhavya/assets/images/product_gallery/1.jpg'); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price">₹<?php echo number_format($product['price'] ?? 0); ?></p>
                                <span class="view-btn">View Details</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Fallback content if no products from DB -->
                <div class="products-grid">
                    <a href="../pages/buy.php" class="product-card">
                        <div class="product-image-wrapper">
                            <img src="/Bhavya/assets/images/product_gallery/1.jpg" alt="Premium Bluetooth Speaker"
                                onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/1.jpg';">
                        </div>
                        <div class="product-info">
                            <h3>Bluetooth Speakers</h3>
                            <p class="description">High-quality wireless audio.</p>
                            <span class="view-btn">View Collection</span>
                        </div>
                    </a>
                    <a href="../pages/Gallery.php" class="product-card">
                        <div class="product-image-wrapper">
                            <img src="/Bhavya/assets/images/product_gallery/2.jpg" alt="Portable Speaker"
                                onerror="this.onerror=null; this.src='/Bhavya/assets/images/product_gallery/2.jpg';">
                        </div>
                        <div class="product-info">
                            <h3>Portable Speakers</h3>
                            <p class="description">Music on the go.</p>
                            <span class="view-btn">View Collection</span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <!-- Services Section -->
        <section class="services-section section-padding">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="icon">🔧</div>
                    <h3>Expert Repair</h3>
                    <p>Restore your audio devices to their former glory with our professional repair services.</p>
                    <a href="../pages/repair.php" class="service-link">Learn More &rarr;</a>
                </div>
                <div class="service-card">
                    <div class="icon">💰</div>
                    <h3>Sell Your Gear</h3>
                    <p>Upgrade your setup by trading in your old audio equipment for competitive prices.</p>
                    <a href="../pages/sell.php" class="service-link">Get a Quote &rarr;</a>
                </div>
            </div>
        </section>

        <!-- CTA/Internship Section -->
        <section class="internship-section">
            <div class="cta-content">
                <h2>Join The Audio Revolution</h2>
                <p>Passionate about sound technology? Apply for our hands-on internship program.</p>
                <a href="../pages/INTERNSHIP.php" class="internship-btn">Apply Now</a>
            </div>
        </section>
    </main>

    <?php include '../includes/footer.php'; ?>
</body>

</html>