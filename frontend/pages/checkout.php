<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login with redirect back to checkout
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

// This file doesn't require database connections as it's primarily static content
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Soundex</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/shared.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            margin-top: 100px;
        }

        .page-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title h1 {
            color: #1a1a2e;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .checkout-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }

        .checkout-form {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .form-section h2 {
            color: #1a1a2e;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .order-summary {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .summary-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .summary-header h2 {
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .cart-items {
            margin-bottom: 25px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .item-price {
            color: #27ae60;
            font-weight: 600;
        }

        .summary-totals {
            border-top: 2px solid #3498db;
            padding-top: 20px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .grand-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #e74c3c;
            border-top: 1px solid #eee;
            padding-top: 15px;
            margin-top: 15px;
        }

        .payment-methods {
            margin: 25px 0;
        }

        .payment-option {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-option:hover {
            border-color: #3498db;
        }

        .payment-option.selected {
            border-color: #27ae60;
            background-color: #f8fff8;
        }

        .payment-option input {
            margin-right: 10px;
        }

        .place-order-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #27ae60, #219653);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .place-order-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(39, 174, 96, 0.4);
        }

        .empty-cart-message {
            text-align: center;
            padding: 50px 20px;
            color: #666;
        }

        .back-to-shopping {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .back-to-shopping:hover {
            background: #2980b9;
        }

        .remove-item-btn {
            background: none;
            border: none;
            color: #e74c3c;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 5px 0;
            margin-top: 5px;
            display: inline-block;
            transition: color 0.3s;
        }

        .remove-item-btn:hover {
            color: #c0392b;
            text-decoration: underline;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }

        .qty-btn {
            background: #eee;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background: #3498db;
            color: white;
        }

        .qty-value {
            font-weight: 600;
            min-width: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-title">
            <h1>Secure Checkout</h1>
            <p>Complete your purchase with confidence</p>
        </div>

        <div class="checkout-container" id="checkoutContainer">
            <!-- Empty cart message -->
            <div class="empty-cart-message" id="emptyCartMessage">
                <h2>Your cart is empty</h2>
                <p>Add some products to your cart to checkout</p>
                <a href="Gallery.php" class="back-to-shopping">Continue Shopping</a>
            </div>
            <!-- Population via JS -->
        </div>
    </div>

    <script>
        // Load cart items from localStorage
        function loadCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const checkoutContainer = document.getElementById('checkoutContainer');
            const emptyCartMessage = document.getElementById('emptyCartMessage');

            if (cart.length === 0) {
                emptyCartMessage.style.display = 'block';
                checkoutContainer.innerHTML = '';
                checkoutContainer.appendChild(emptyCartMessage);
                return;
            }

            emptyCartMessage.style.display = 'none';

            checkoutContainer.innerHTML = `
                <div class="checkout-form">
                    <div class="form-section">
                        <h2>Shipping Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="firstName">First Name *</label>
                                <input type="text" id="firstName" required>
                            </div>
                            <div class="form-group">
                                <label for="lastName">Last Name *</label>
                                <input type="text" id="lastName" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Street Address *</label>
                            <textarea id="address" rows="3" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" required>
                            </div>
                            <div class="form-group">
                                <label for="zipCode">ZIP Code *</label>
                                <input type="text" id="zipCode" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="country">Country *</label>
                            <select id="country" required>
                                <option value="">Select Country</option>
                                <option value="India">India</option>
                                <option value="USA">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="Canada">Canada</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h2>Payment Method</h2>
                        <div class="payment-methods">
                            <div class="payment-option" onclick="selectPaymentMethod('credit', event)">
                                <input type="radio" name="payment" value="credit" id="creditCard">
                                <label for="creditCard">Credit/Debit Card</label>
                            </div>
                            <div class="payment-option" onclick="selectPaymentMethod('paypal', event)">
                                <input type="radio" name="payment" value="paypal" id="paypal">
                                <label for="paypal">PayPal</label>
                            </div>
                            <div class="payment-option" onclick="selectPaymentMethod('cod', event)">
                                <input type="radio" name="payment" value="cod" id="cashOnDelivery">
                                <label for="cashOnDelivery">Cash on Delivery</label>
                            </div>
                        </div>
                        
                        <div id="cardDetails" style="display: none;">
                            <div class="form-group">
                                <label for="cardNumber">Card Number *</label>
                                <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiryDate">Expiry Date *</label>
                                    <input type="text" id="expiryDate" placeholder="MM/YY">
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV *</label>
                                    <input type="text" id="cvv" placeholder="123">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cardName">Name on Card *</label>
                                <input type="text" id="cardName">
                            </div>
                        </div>
                    </div>
                    
                    <button class="place-order-btn" onclick="placeOrder()">Place Order</button>
                </div>
                
                <div class="order-summary">
                    <div class="summary-header">
                        <h2>Order Summary</h2>
                    </div>
                    <div class="cart-items" id="cartItemsList">
                        \${generateCartItemsHTML(cart)}
                    </div>
                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal:</span>
                            <span>₹\${calculateSubtotal(cart).toLocaleString()}</span>
                        </div>
                        <div class="total-row">
                            <span>Shipping:</span>
                            <span>₹\${cart.length > 0 ? '99' : '0'}</span>
                        </div>
                        <div class="total-row grand-total">
                            <span>Total:</span>
                            <span>₹\${calculateTotal(cart).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Generate HTML for cart items
        function generateCartItemsHTML(cart) {
            return cart.map((item, index) => `
                <div class="cart-item">
                    <div class="item-details">
                        <div class="item-name">\${item.name}</div>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="updateQuantity(\${index}, -1)">-</button>
                            <span class="qty-value">\${item.quantity || 1}</span>
                            <button class="qty-btn" onclick="updateQuantity(\${index}, 1)">+</button>
                        </div>
                        <button class="remove-item-btn" onclick="removeFromCart(\${index})">Remove</button>
                    </div>
                    <div class="item-price">₹\${(item.price * (item.quantity || 1)).toLocaleString()}</div>
                </div>
            `).join('');
        }

        function updateQuantity(index, delta) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart[index]) {
                let currentQty = cart[index].quantity || 1;
                let newQty = currentQty + delta;
                if (newQty < 1) {
                    removeFromCart(index);
                    return;
                }
                cart[index].quantity = newQty;
                localStorage.setItem('cart', JSON.stringify(cart));
                loadCartItems();
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            }
        }

        function removeFromCart(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const removedItem = cart[index];
            if (confirm(\`Are you sure you want to remove \${removedItem.name} from your cart?\`)) {
                cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                loadCartItems();
                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            }
        }

        function calculateSubtotal(cart) {
            return cart.reduce((total, item) => total + (item.price * (item.quantity || 1)), 0);
        }

        function calculateTotal(cart) {
            const subtotal = calculateSubtotal(cart);
            const shipping = cart.length > 0 ? 99 : 0;
            return subtotal + shipping;
        }

        function selectPaymentMethod(method, event) {
            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            const radio = event.currentTarget.querySelector('input[type="radio"]');
            if(radio) radio.checked = true;

            const cardDetails = document.getElementById('cardDetails');
            if (method === 'credit') {
                cardDetails.style.display = 'block';
            } else {
                cardDetails.style.display = 'none';
            }
        }

        async function placeOrder() {
            if (!validateForm()) {
                return;
            }

            const placeOrderBtn = document.querySelector('.place-order-btn');
            const originalText = placeOrderBtn.innerText;
            placeOrderBtn.innerText = 'Processing...';
            placeOrderBtn.disabled = true;

            try {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                const shipping = {
                    firstName: document.getElementById('firstName').value,
                    lastName: document.getElementById('lastName').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    address: document.getElementById('address').value,
                    city: document.getElementById('city').value,
                    zipCode: document.getElementById('zipCode').value,
                    country: document.getElementById('country').value
                };
                const paymentMethod = document.querySelector('input[name="payment"]:checked').value;

                const response = await fetch('../../backend/php/place_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        cart: cart,
                        shipping: shipping,
                        paymentMethod: paymentMethod
                    })
                });

                const result = await response.json();
                if (result.success) {
                    alert('Order placed successfully! Order #' + result.order_number);
                    localStorage.removeItem('cart');
                    window.location.href = 'history.php';
                } else {
                    alert('Failed to place order: ' + result.message);
                    placeOrderBtn.innerText = originalText;
                    placeOrderBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while placing the order.');
                placeOrderBtn.innerText = originalText;
                placeOrderBtn.disabled = false;
            }
        }

        function validateForm() {
            const requiredFields = ['firstName', 'lastName', 'email', 'phone', 'address', 'city', 'zipCode', 'country'];
            let isValid = true;
            requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (!field.value.trim()) {
                    field.style.borderColor = '#e74c3c';
                    isValid = false;
                } else {
                    field.style.borderColor = '#ddd';
                }
            });
            const paymentSelected = document.querySelector('input[name="payment"]:checked');
            if (!paymentSelected) {
                alert('Please select a payment method');
                isValid = false;
            }
            if (!isValid) {
                alert('Please fill in all required fields');
            }
            return isValid;
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadCartItems();
        });
    </script>
    <?php include '../includes/footer.php'; ?>
</body>
</html>