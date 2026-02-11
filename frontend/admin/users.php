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
            <h2>Users</h2>
        </div>

        <div class="card">
            <div class="table-container">
                <table id="usersTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
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