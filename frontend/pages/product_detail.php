<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ProductManager
$productManager = new ProductManager($pdo);
$userManager = new UserManager($pdo);

// Get product ID from URL parameter
$productId = $_GET['id'] ?? 1;

// Get specific product
$product = $productManager->getProductById($productId);

// Get gallery images
$galleryImages = $productManager->getGalleryImages($productId);

// If no specific product was found, use a default product or redirect
if (!$product) {
  // Optionally redirect to home or show error
  // header("Location: home.php");
  // exit;

  // Using default data for display purposes if DB fails or ID invalid
  $product = [
    'id' => 1,
    'name' => 'Wireless Bluetooth Headphones',
    'description' => 'Experience crystal-clear sound and long-lasting comfort with these wireless Bluetooth headphones. Perfect for music, calls, and gaming.',
    'price' => 4999,
    'image_url' => '../../assets/images/product_gallery/1.jpg',
    'features' => [
      'Bluetooth 5.0 connectivity',
      '20 hours battery life',
      'Noise-cancelling microphone',
      'Lightweight & foldable design'
    ]
  ];
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['name'] ?? 'Product Detail'); ?> - Soundex</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/product_detail.css">
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
      <li><a href="../pages/services.php">Services</a></li>
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
    <div class="product-detail-container">
      <!-- Left Column: Product Images -->
      <div class="product-gallery">
        <div class="main-image-container">
          <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>"
            alt="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>" class="main-img" id="mainImage"
            onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
        </div>
        <div class="thumbnails">
          <!-- Main Image as first thumbnail -->
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../../assets/images/product_gallery/1.jpg'); ?>"
                class="active" onclick="changeImage(this)"
                onerror="this.onerror=null; this.src='../../assets/images/product_gallery/1.jpg';">
            
            <?php if (!empty($galleryImages)): ?>
              <?php foreach ($galleryImages as $img): ?>
                <img src="<?php echo htmlspecialchars($img['image_url']); ?>" 
                    onclick="changeImage(this)" 
                    alt="<?php echo htmlspecialchars($img['alt_text'] ?? 'Gallery Image'); ?>"
                    onerror="this.style.display='none';">
              <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </div>

      <!-- Right Column: Product Details -->
      <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name'] ?? 'Product Name'); ?></h1>
        <div class="product-price">₹<?php echo number_format($product['price'] ?? 0); ?></div>

        <div class="description-section">
          <span class="sections-title">Description</span>
          <p class="product-description">
            <?php echo htmlspecialchars($product['description'] ?? 'No description available for this product.'); ?>
          </p>
        </div>

        <div class="features-section">
          <span class="sections-title">Key Features</span>
          <ul class="features-list">
            <?php if (!empty($product['features'])): ?>
              <?php foreach ($product['features'] as $feature): ?>
                <li><?php echo htmlspecialchars($feature); ?></li>
              <?php endforeach; ?>
            <?php else: ?>
              <li>Premium Sound Quality</li>
              <li>Durable Build Material</li>
              <li>1 Year Warranty</li>
              <li>Free Shipping</li>
            <?php endif; ?>
          </ul>
        </div>

        <div class="action-buttons">
          <button class="btn btn-cart"
            onclick="addToCart('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>, '<?php echo htmlspecialchars($product['image_url'] ?? '../../assets/images/product_gallery/1.jpg'); ?>')">
            Add to Cart
          </button>
          <button class="btn btn-buy"
            onclick="buyNow('<?php echo addslashes(htmlspecialchars($product['name'] ?? 'Product')); ?>', <?php echo $product['price'] ?? 0; ?>, '<?php echo htmlspecialchars($product['image_url'] ?? '../../assets/images/product_gallery/1.jpg'); ?>')">
            Buy Now
          </button>
        </div>
      </div>
    </div>
  </main>

  <div id="toast" class="toast">Item added to cart!</div>

  <script>
    // Image Gallery Logic
    function changeImage(thumbnail) {
      // Update main image
      document.getElementById('mainImage').src = thumbnail.src;

      // Update active class
      document.querySelectorAll('.thumbnails img').forEach(img => img.classList.remove('active'));
      thumbnail.classList.add('active');
    }

    // Cart Logic
    function addToCart(productName, price, imageUrl) {
      let cart = JSON.parse(localStorage.getItem('cart')) || [];
      const existingItemIndex = cart.findIndex(item => item.name === productName);

      if (existingItemIndex > -1) {
        cart[existingItemIndex].quantity += 1;
      } else {
        cart.push({
          name: productName,
          price: price,
          image: imageUrl,
          quantity: 1
        });
      }

      localStorage.setItem('cart', JSON.stringify(cart));
      updateCartCount();
      showToast(`${productName} added to cart!`);
    }

    // Buy Now Logic
    function buyNow(productName, price, imageUrl) {
      <?php if (!$isLoggedIn): ?>
        if (confirm('You need to login or signup to proceed with checkout. Would you like to login now?')) {
          window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname);
        }
        return;
      <?php else: ?>
        // Add to cart first
        addToCart(productName, price, imageUrl);
        window.location.href = 'checkout.php';
      <?php endif; ?>
    }

    // Update Header Cart Count
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

    // Toast Notification
    function showToast(message) {
      const toast = document.getElementById('toast');
      toast.textContent = message;
      toast.className = "toast show";
      setTimeout(function () { toast.className = toast.className.replace("show", ""); }, 3000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', updateCartCount);
  </script>
</body>

</html>