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
            <h2>Categories</h2>
            <button id="addCategoryBtn" class="btn btn-primary"><i class="fas fa-plus"></i> Add Category</button>
        </div>

        <div class="card">
            <div class="table-container">
                <table id="categoriesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
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

<!-- Category Modal -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3 id="modalTitle">Add Category</h3>
        <form id="categoryForm">
            <input type="hidden" id="categoryId">
            <div class="form-group">
                <label>Name</label>
                <input type="text" id="categoryName" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="categoryDesc" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" id="categoryImage" placeholder="assets/images/...">
            </div>
            <button type="submit" class="btn btn-primary">Save Category</button>
        </form>
    </div>
</div>

<script src="js/admin.js"></script>
</body>

</html>