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
</head>

<body>

    <!-- Navigation Header -->
    <nav>
        <ul>
            <div class="logo"><a href="../pages/about.php">
                    <h1>Soun<p>Dex</p>
                    </h1>
                </a></div>
            <li><a href="../pages/home.php" class="active">Home</a></li>
            <li><a href="../pages/Gallery.php">Gallery</a></li>
            <li><a href="../pages/faqs.php">FAQs</a></li>
            <li><a href="../pages/services.php">Services</a></li>
            <li><a href="../pages/contact us.php">Contact</a></li>
            <li><a href="../pages/about.php">About</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="../pages/history.php">History</a></li>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li><a href="../admin/index.php" style="color: #f50057; font-weight: bold;">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a>
                </li>
                <li><a href="../logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="../pages/login.php">Login</a></li>
                <li><a href="../pages/signup.php">Sign Up</a></li>
            <?php endif; ?>
            <li><a href="../pages/checkout.php" class="cart-icon" id="cartIcon">
                    🛒
                    <span class="cart-count" id="cartCount">0</span>
                </a></li>
        </ul>
    </nav>

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
                        <a href="../pages/buy.php" class="product-card">
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

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <h2>Soun<span>Dex</span></h2>
                <p>Your one-stop shop for premium audio.</p>
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="../pages/home.php">Home</a></li>
                    <li><a href="../pages/buy.php">Shop</a></li>
                    <li><a href="../pages/services.php">Services</a></li>
                    <li><a href="../pages/contact us.php">Contact</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h3>Follow Us</h3>
                <div class="social-icons">
                    <a href="#" class="social-icon">FB</a>
                    <a href="#" class="social-icon">IG</a>
                    <a href="#" class="social-icon">TW</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Soundex Audio Solutions. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Copy Cart Logic from other pages if necessary or ensure header.js is included if it exists
        // Ideally this should be in a common js file
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const totalItems = cart.reduce((total, item) => total + (item.quantity || 1), 0);
            const cartCountElement = document.getElementById('cartCount');
            const cartIconElement = document.getElementById('cartIcon');

            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
                if (totalItems > 0) {
                    cartIconElement.classList.remove('empty');
                } else {
                    cartIconElement.classList.add('empty');
                }
            }
        }
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body>

</html>