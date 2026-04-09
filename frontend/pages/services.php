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
  <link rel="stylesheet" href="../css/footer.css" />
  <link rel="stylesheet" href="../css/shared.css">
  <link rel="stylesheet" href="../css/services.css">
</head>

<body>
  <!-- Navigation Header -->
  <?php include '../includes/header.php'; ?>

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

  <script src="../js/services.js"></script>
  <?php include '../includes/footer.php'; ?>
</body>

</html>