<?php
session_start();
require_once '../../backend/php/db_config.php';
require_once '../../backend/php/ProductManager.php';
require_once '../../backend/php/UserManager.php';

// Initialize ProductManager
$productManager = new ProductManager($pdo);
$userManager = new UserManager($pdo);

// Get all products from the database
$products = $productManager->getAllProducts();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Products - Soundex</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/buy.css">
</head>

<body>
    <!-- Fixed Navigation Header -->
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

    <!-- Main Content Container -->
    <main class="main-content">
        <header class="page-header">
            <h1>Our Premium Collection</h1>
            <p>Explore our range of high-fidelity audio equipment.</p>
        </header>

        <div class="products-wrapper">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <a href="product_detail.php?id=<?php echo $product['id']; ?>">
                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                            </a>
                        </div>
                        <div class="product-details">
                            <h2 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="product-description">
                                <?php echo htmlspecialchars($product['description'] ?? 'Experience crystal clear sound quality with our premium audio devices.'); ?>
                            </p>
                            <div class="product-price">₹<?php echo number_format($product['price'] ?? 0); ?></div>
                            <div class="product-actions">
                                <button class="btn btn-cart add-to-cart-btn" data-id="<?php echo $product['id']; ?>"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-image="<?php echo htmlspecialchars($product['image_url'] ?? '../assets/images/product_gallery/1.jpg'); ?>">
                                    Add to Cart
                                </button>
                                <button class="btn btn-buy buy-now-btn"
                                    data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                    data-price="<?php echo $product['price']; ?>"
                                    data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback Products if DB is empty -->
                <div class="product-card">
                    <div class="product-image">
                        <img src="../assets/images/product_gallery/1.jpg" alt="Premium Speaker"
                            onerror="this.onerror=null; this.src='../assets/images/product_gallery/1.jpg';">
                    </div>
                    <div class="product-details">
                        <h2 class="product-title">Bose SoundLink Micro</h2>
                        <p class="product-description">Compact speaker with surprisingly powerful bass and clear sound.</p>
                        <div class="product-price">₹2,999</div>
                        <div class="product-actions">
                            <button class="btn btn-cart add-to-cart-btn" data-id="1" data-name="Bose SoundLink Micro"
                                data-price="2999">Add to Cart</button>
                            <button class="btn btn-buy buy-now-btn" data-name="Bose SoundLink Micro" data-price="2999">Buy
                                Now</button>
                        </div>
                    </div>
                </div>
                <!-- Repeat logic for other fallback items if desired, but DB is preferred -->
            <?php endif; ?>
        </div>
    </main>

    <!-- Buy Now Modal -->
    <div id="purchaseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Complete Your Purchase</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <h3 id="modalProductName">Product Name</h3>
                <div id="modalProductPrice" class="modal-product-price">₹0</div>
                <div class="modal-steps">
                    <strong>Next Steps:</strong>
                    <ol>
                        <li>Review order details.</li>
                        <li>Proceed to secure checkout.</li>
                        <li>Enter shipping & payment info.</li>
                    </ol>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelBtn">Cancel</button>
                <button class="btn btn-buy" id="proceedBtn">Proceed to Checkout</button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">Item added to cart!</div>

    <script>
        // DOM Elements
        const modal = document.getElementById('purchaseModal');
        const closeBtn = document.querySelector('.close');
        const cancelBtn = document.getElementById('cancelBtn');
        const proceedBtn = document.getElementById('proceedBtn');
        const modalProductName = document.getElementById('modalProductName');
        const modalProductPrice = document.getElementById('modalProductPrice');
        const toast = document.getElementById('toast');

        // State for Buy Now
        let currentBuyItem = null;

        // --- Event Listeners ---

        // Buy Now Buttons
        document.querySelectorAll('.buy-now-btn').forEach(button => {
            button.addEventListener('click', function () {
                const name = this.getAttribute('data-name');
                const price = this.getAttribute('data-price');

                // Set modal content
                modalProductName.textContent = name;
                modalProductPrice.textContent = '₹' + parseInt(price).toLocaleString();

                // Store current item data for checkout
                currentBuyItem = {
                    name: name,
                    price: parseFloat(price),
                    quantity: 1
                };

                // Show modal
                modal.style.display = 'block';
            });
        });

        // Add to Cart Buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function () {
                const product = {
                    id: this.getAttribute('data-id'),
                    name: this.getAttribute('data-name'),
                    price: parseFloat(this.getAttribute('data-price')),
                    image: this.getAttribute('data-image'),
                    quantity: 1
                };

                addToCart(product);
                showToast(product.name + ' added to cart!');
            });
        });

        // Modal Controls
        closeBtn.onclick = closeModal;
        cancelBtn.onclick = closeModal;
        window.onclick = function (event) {
            if (event.target == modal) {
                closeModal();
            }
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        // Proceed to Checkout (Buy Now flow)
        proceedBtn.addEventListener('click', function () {
            <?php if (!$isLoggedIn): ?>
                if (confirm('You need to login to proceed. Login now?')) {
                    window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname);
                }
            <?php else: ?>
                if (currentBuyItem) {
                    // Start fresh cart or just add to it? 
                    // Usually "Buy Now" might clear cart or just add this item and go to checkout.
                    // Let's add this item to cart and go to checkout for smoother flow.
                    addToCart(currentBuyItem);
                    window.location.href = 'checkout.php';
                }
            <?php endif; ?>
        });

        // --- Helper Functions ---

        function addToCart(product) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            // Check if item already exists
            const existingItemIndex = cart.findIndex(item => item.name === product.name);

            if (existingItemIndex > -1) {
                cart[existingItemIndex].quantity += 1;
            } else {
                cart.push(product);
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

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

        function showToast(message) {
            toast.textContent = message;
            toast.className = "toast show";
            setTimeout(function () { toast.className = toast.className.replace("show", ""); }, 3000);
        }

        // Init
        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
</body></html>