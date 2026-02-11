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
            <h2>Products</h2>
            <button id="addProductBtn" class="btn btn-primary"><i class="fas fa-plus"></i> Add Product</button>
        </div>

        <div class="card">
            <div class="table-container">
                <table id="productsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
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

<!-- Product Modal -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3 id="modalTitle">Add Product</h3>
        <form id="productForm">
            <input type="hidden" id="productId">
            <div class="form-group">
                <label>Name</label>
                <input type="text" id="productName" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="productDesc" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" id="productPrice" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" id="productStock" required>
            </div>
            <div class="form-group">
                <label>Category</label>
                <!-- In a real app, this should be a dynamic select populated from categories -->
                <input type="text" id="productCategory" placeholder="Category Name" required>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" id="productImage" placeholder="assets/images/..." required>
            </div>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
</div>

<script src="js/admin.js"></script>
</body>

</html>