<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/UserManager.php';

// Initialize UserManager
$userManager = new UserManager($pdo);

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Services - Soundex Audio Solutions</title>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/header.css" />
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/services.css">
</head>

<body>
  <!-- Navigation Header -->
  <nav>
    <ul>
      <div class="logo"><a href="../pages/about.php">
          <h1>Soun<p>Dex</p>
          </h1>
        </a></div>
      <li><a href="../pages/home.php">Home</a></li>
      <li><a href="../pages/Gallery.php">Gallery</a></li>
      <li><a href="../pages/faqs.php">FAQs</a></li>
      <li><a href="../pages/services.php" class="active">Services</a></li>
      <li><a href="../pages/contact us.php">Contact</a></li>
      <li><a href="../pages/about.php">About</a></li>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <li><a href="../admin/index.php" style="color: #f50057; font-weight: bold;">Admin Panel</a></li>
      <?php endif; ?>
      <?php if ($isLoggedIn): ?>
        <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a></li>
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
    <section class="services-section section-padding">
      <h1 class="section-title">Our Services</h1>
      <p class="intro" style="text-align: center; margin-bottom: 40px; color: #666;">Explore how Soundex empowers you to
        buy, sell, repair, exchange, and learn—all in one place.</p>

      <div class="services-grid">
        <div class="service-card" onclick="showDetails('buy')">
          <div class="icon">🛒</div>
          <h2>Buy</h2>
          <p>Shop premium audio gear with verified quality and eco-friendly packaging.</p>
        </div>
        <div class="service-card" onclick="showDetails('sell')">
          <div class="icon">💰</div>
          <h2>Sell</h2>
          <p>List your used speakers and accessories with ease and transparency.</p>
        </div>
        <div class="service-card" onclick="showDetails('repair')">
          <div class="icon">🔧</div>
          <h2>Repair</h2>
          <p>Get expert diagnostics and sustainable repair options for your devices.</p>
        </div>
        <div class="service-card" onclick="showDetails('exchange')">
          <div class="icon">🔄</div>
          <h2>Exchange</h2>
          <p>Swap your gear for upgrades or alternatives—reduce waste, increase value.</p>
        </div>
        <div class="service-card" onclick="showDetails('learn')">
          <div class="icon">📚</div>
          <h2>Learn</h2>
          <p>Access tutorials, guides, and community insights to build your tech skills.</p>
        </div>
      </div>

      <div id="service-details" class="details-box"
        style="margin-top: 40px; text-align: center; display: none; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
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

  <script src="../js/services.js"></script>
  <script>
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