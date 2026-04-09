<?php
// Determine the current page for active link highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$username = $is_logged_in ? $_SESSION['username'] : '';
?>

<nav>
    <ul>
        <div class="logo">
            <a href="about.php">
                <h1>Soun<p>Dex</p></h1>
            </a>
        </div>
        <li><a href="home.php" class="<?php echo ($current_page == 'home.php') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="Gallery.php" class="<?php echo ($current_page == 'Gallery.php') ? 'active' : ''; ?>">Gallery</a></li>
        <li><a href="faqs.php" class="<?php echo ($current_page == 'faqs.php') ? 'active' : ''; ?>">FAQs</a></li>
        <li><a href="services.php" class="<?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">Services</a></li>
        <li><a href="contact us.php" class="<?php echo ($current_page == 'contact us.php') ? 'active' : ''; ?>">Contact</a></li>
        <li><a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
        
        <?php if ($is_logged_in): ?>
            <li><a href="history.php" class="<?php echo ($current_page == 'history.php') ? 'active' : ''; ?>">History</a></li>
            <li><a href="#" style="color: #0077cc; font-weight: bold;"><?php echo htmlspecialchars($username); ?></a></li>
            <li><a href="../logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php" class="<?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">Login</a></li>
            <li><a href="signup.php" class="<?php echo ($current_page == 'signup.php') ? 'active' : ''; ?>">Sign Up</a></li>
        <?php endif; ?>

        <li>
            <a href="checkout.php" class="cart-icon <?php echo ($current_page == 'checkout.php') ? 'active' : ''; ?>" id="cartIcon">
                🛒 <span class="cart-count" id="cartCount">0</span>
            </a>
        </li>
    </ul>
</nav>

<!-- JavaScript for Cart Count (Global) -->
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

// Update cart count on page load
document.addEventListener('DOMContentLoaded', updateCartCount);

// Expose updateCartCount globally so other scripts can call it after adding items
window.updateCartCount = updateCartCount;
</script>
