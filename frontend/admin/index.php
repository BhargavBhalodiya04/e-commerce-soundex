<?php
require_once 'includes/header.php';
?>

<div class="sidebar-container">
    <?php require_once 'includes/sidebar.php'; ?>
</div>

<div class="main-content">
    <div class="top-header">
        <div class="user-info">
            <span>Welcome, Admin</span>
        </div>
    </div>

    <div class="content">
        <div class="page-header">
            <h2>Dashboard Overview</h2>
        </div>

        <div class="stats-grid">
            <div class="card">
                <div class="stat-card">
                    <div class="stat-info">
                        <p>Total Orders</p>
                        <h3 id="totalOrders">0</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="stat-card">
                    <div class="stat-info">
                        <p>Products</p>
                        <h3 id="totalProducts">0</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="stat-card">
                    <div class="stat-info">
                        <p>Total Users</p>
                        <h3 id="totalUsers">0</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="stat-card">
                    <div class="stat-info">
                        <p>Categories</p>
                        <h3 id="totalCategories">0</h3>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <h3>Recent Orders</h3>
            <div class="table-container">
                <table id="recentOrdersTable">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="js/admin.js"></script>
</body>

</html>