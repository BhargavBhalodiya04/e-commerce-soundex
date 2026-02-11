<div class="sidebar">
    <div class="sidebar-header">
        <h3>Soundex Admin</h3>
    </div>
    <div class="sidebar-footer">
        <div class="user-nav-info"
            style="margin-right: 20px; font-size: 0.9rem; font-weight: 500; color: #64748b; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-user-circle" style="font-size: 1.2rem; color: #4361ee;"></i> Welcome, Admin
        </div>
        <button id="logoutBtn" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i> <span>Dashboard</span></a></li>
        <li><a href="categories.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
                <i class="fas fa-th-large"></i> <span>Categories</span></a></li>
        <li><a href="products.php"
                class="<?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>">
                <i class="fas fa-box-open"></i> <span>Products</span></a></li>
        <li><a href="orders.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag"></i> <span>Orders</span></a></li>
        <li><a href="users.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-shield"></i> <span>Users</span></a></li>
    </ul>
</div>

<script>
    document.getElementById('logoutBtn').addEventListener('click', function () {
        const sessionToken = localStorage.getItem('session_token');
        if (sessionToken) {
            fetch('../../backend/php/api.php?action=logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ session_token: sessionToken })
            })
                .then(response => response.json())
                .then(data => {
                    localStorage.removeItem('session_token');
                    localStorage.removeItem('user');
                    window.location.href = '../pages/login.php';
                });
        } else {
            window.location.href = '../pages/login.php';
        }
    });
</script>