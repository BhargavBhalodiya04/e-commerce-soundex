const API_URL = '../../backend/php/api.php';

// Check if user is admin on page load
document.addEventListener('DOMContentLoaded', () => {
    checkAdminAccess();

    // Load dashboard stats if we are on index.php
    if (window.location.pathname.includes('index.php')) {
        loadDashboardStats();
    }

    // Load products if on products.php
    if (window.location.pathname.includes('products.php')) {
        loadProducts();
    }

    // Load categories.php
    if (window.location.pathname.includes('categories.php')) {
        loadCategories();
    }

    // Load orders.php
    if (window.location.pathname.includes('orders.php')) {
        loadOrders();
    }

    // Load users.php
    if (window.location.pathname.includes('users.php')) {
        loadUsers();
    }
});

function checkAdminAccess() {
    const sessionToken = localStorage.getItem('session_token');

    // Call is_admin API - it now falls back to PHP session if token is missing
    fetch(`${API_URL}?action=is_admin`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ session_token: sessionToken }),
        credentials: 'include'
    })
        .then(res => res.json())
        .then(data => {
            if (data.is_admin === false) {
                console.warn('Access denied by server');
                window.location.href = '../pages/home.php';
            } else if (data.is_admin === true) {
                console.log('Access granted');
            } else if (data.success === false) {
                console.error('Admin check server error:', data.message);
                // Handle server error - maybe don't redirect yet if PHP check passed
            }
        })
        .catch((err) => {
            console.error('Fetch error in admin check:', err);
            window.location.href = '../pages/login.php';
        });
}

function loadDashboardStats() {
    const sessionToken = localStorage.getItem('session_token');

    // Fetch counts
    Promise.all([
        fetch(`${API_URL}?action=get_all_orders`, { method: 'POST', credentials: 'include', body: JSON.stringify({ session_token: sessionToken }) }).then(r => r.json()),
        fetch(`${API_URL}?action=get_products`, { credentials: 'include' }).then(r => r.json()),
        fetch(`${API_URL}?action=get_all_users`, { method: 'POST', credentials: 'include', body: JSON.stringify({ session_token: sessionToken }) }).then(r => r.json()),
        fetch(`${API_URL}?action=get_categories`, { credentials: 'include' }).then(r => r.json())
    ]).then(([ordersData, productsData, usersData, categoriesData]) => {
        if (ordersData.success) {
            document.getElementById('totalOrders').textContent = ordersData.orders.length;
            populateRecentOrders(ordersData.orders.slice(0, 5));
        }
        if (productsData.success) {
            document.getElementById('totalProducts').textContent = productsData.products.length;
        }
        if (usersData.success) {
            document.getElementById('totalUsers').textContent = usersData.users.length;
        }
        if (categoriesData.success) {
            document.getElementById('totalCategories').textContent = categoriesData.categories.length;
        }

    });
}

function populateRecentOrders(orders) {
    const tbody = document.querySelector('#recentOrdersTable tbody');
    if (!tbody) return;
    tbody.innerHTML = '';

    orders.forEach(order => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${order.order_number}</td>
            <td>${order.username || 'Guest'}</td>
            <td>${new Date(order.created_at).toLocaleDateString()}</td>
            <td>₹${order.total_amount}</td>
            <td><span class="status-badge ${order.status}">${order.status}</span></td>
            <td><a href="orders.php?id=${order.id}" class="btn btn-sm btn-primary">View</a></td>
        `;
        tbody.appendChild(tr);
    });
}

// Product Management
function loadProducts() {
    const sessionToken = localStorage.getItem('session_token');
    fetch(`${API_URL}?action=get_products`, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#productsTable tbody');
                tbody.innerHTML = '';
                data.products.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${p.id}</td>
                    <td><img src="${p.image_url}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"></td>
                    <td>${p.name}</td>
                    <td>₹${p.price}</td>
                    <td>${p.stock_quantity}</td>
                    <td>
                        <button onclick="editProduct(${p.id})" class="btn btn-sm btn-primary">Edit</button>
                        <button onclick="deleteProduct(${p.id})" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            }
        });

    // Setup modal listeners
    const modal = document.getElementById('productModal');
    const addBtn = document.getElementById('addProductBtn');
    const closeBtn = document.querySelector('.close-btn');
    const form = document.getElementById('productForm');

    if (addBtn) {
        addBtn.onclick = () => {
            document.getElementById('modalTitle').textContent = 'Add Product';
            form.reset();
            document.getElementById('productId').value = '';
            modal.style.display = 'flex';
        };
    }

    if (closeBtn) {
        closeBtn.onclick = () => {
            modal.style.display = 'none';
        };
    }

    window.onclick = (e) => {
        if (e.target == modal) {
            modal.style.display = 'none';
        }
    }

    form.onsubmit = (e) => {
        e.preventDefault();
        const id = document.getElementById('productId').value;
        const action = id ? 'update_product' : 'create_product';

        const formData = {
            session_token: sessionToken,
            id: id, // Optional for create
            name: document.getElementById('productName').value,
            description: document.getElementById('productDesc').value,
            price: document.getElementById('productPrice').value,
            stock_quantity: document.getElementById('productStock').value,
            category: document.getElementById('productCategory').value,
            image_url: document.getElementById('productImage').value
        };

        fetch(`${API_URL}?action=${action}`, {
            method: 'POST',
            body: JSON.stringify(formData),
            credentials: 'include'
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Success!');
                    modal.style.display = 'none';
                    loadProducts();
                } else {
                    alert('Error: ' + data.message);
                }
            });
    }
}

function editProduct(id) {
    fetch(`${API_URL}?action=get_products`, { credentials: 'include' }) // In real app, maybe get_product_by_id
        .then(r => r.json())
        .then(data => {
            const product = data.products.find(p => p.id == id);
            if (product) {
                document.getElementById('modalTitle').textContent = 'Edit Product';
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productDesc').value = product.description;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productStock').value = product.stock_quantity;
                document.getElementById('productCategory').value = product.category;
                document.getElementById('productImage').value = product.image_url;

                document.getElementById('productModal').style.display = 'flex';
            }
        });
}

function deleteProduct(id) {
    if (confirm('Are you sure?')) {
        const sessionToken = localStorage.getItem('session_token');
        fetch(`${API_URL}?action=delete_product`, {
            method: 'POST',
            body: JSON.stringify({ session_token: sessionToken, id: id }),
            credentials: 'include'
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadProducts();
                } else {
                    alert(data.message);
                }
            });
    }
}


// Categories Management
function loadCategories() {
    const sessionToken = localStorage.getItem('session_token');
    fetch(`${API_URL}?action=get_categories`, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#categoriesTable tbody');
                tbody.innerHTML = '';
                data.categories.forEach(c => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${c.id}</td>
                     <td><img src="${c.image_url}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"></td>
                    <td>${c.name}</td>
                    <td>${c.description}</td>
                    <td>
                        <button onclick="editCategory(${c.id})" class="btn btn-sm btn-primary">Edit</button>
                        <button onclick="deleteCategory(${c.id})" class="btn btn-sm btn-danger">Delete</button>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            }
        });

    // Setup modal listeners (similar to products)
    const modal = document.getElementById('categoryModal');
    const addBtn = document.getElementById('addCategoryBtn');
    const closeBtn = document.querySelector('.close-btn');
    const form = document.getElementById('categoryForm');

    if (addBtn) {
        addBtn.onclick = () => {
            document.getElementById('modalTitle').textContent = 'Add Category';
            form.reset();
            document.getElementById('categoryId').value = '';
            modal.style.display = 'flex';
        };
    }
    if (closeBtn) {
        closeBtn.onclick = () => {
            modal.style.display = 'none';
        };
    }

    form.onsubmit = (e) => {
        e.preventDefault();
        const id = document.getElementById('categoryId').value;
        const action = id ? 'update_category' : 'create_category';

        const formData = {
            session_token: sessionToken,
            id: id,
            name: document.getElementById('categoryName').value,
            description: document.getElementById('categoryDesc').value,
            image_url: document.getElementById('categoryImage').value
        };

        fetch(`${API_URL}?action=${action}`, {
            method: 'POST',
            body: JSON.stringify(formData),
            credentials: 'include'
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Success!');
                    modal.style.display = 'none';
                    loadCategories();
                } else {
                    alert('Error: ' + data.message);
                }
            });
    }
}

function editCategory(id) {
    fetch(`${API_URL}?action=get_categories`, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            const category = data.categories.find(c => c.id == id);
            if (category) {
                document.getElementById('modalTitle').textContent = 'Edit Category';
                document.getElementById('categoryId').value = category.id;
                document.getElementById('categoryName').value = category.name;
                document.getElementById('categoryDesc').value = category.description;
                document.getElementById('categoryImage').value = category.image_url;
                document.getElementById('categoryModal').style.display = 'flex';
            }
        });
}

function deleteCategory(id) {
    if (confirm('Are you sure?')) {
        const sessionToken = localStorage.getItem('session_token');
        fetch(`${API_URL}?action=delete_category`, {
            method: 'POST',
            body: JSON.stringify({ session_token: sessionToken, id: id }),
            credentials: 'include'
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadCategories();
                } else {
                    alert(data.message);
                }
            });
    }
}

// Order Management
function loadOrders() {
    const sessionToken = localStorage.getItem('session_token');
    fetch(`${API_URL}?action=get_all_orders`, {
        method: 'POST',
        body: JSON.stringify({ session_token: sessionToken }),
        credentials: 'include'
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#ordersTable tbody');
                tbody.innerHTML = '';
                data.orders.forEach(o => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                    <td>${o.order_number}</td>
                    <td>${o.username || 'Guest'}</td>
                    <td>${new Date(o.created_at).toLocaleDateString()}</td>
                    <td>₹${o.total_amount}</td>
                    <td>
                        <select onchange="updateOrderStatus(${o.id}, this.value)" class="form-control">
                            <option value="pending" ${o.status == 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="processing" ${o.status == 'processing' ? 'selected' : ''}>Processing</option>
                            <option value="shipped" ${o.status == 'shipped' ? 'selected' : ''}>Shipped</option>
                            <option value="delivered" ${o.status == 'delivered' ? 'selected' : ''}>Delivered</option>
                            <option value="cancelled" ${o.status == 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    </td>
                `;
                    tbody.appendChild(tr);
                });
            }
        });
}

function updateOrderStatus(id, status) {
    const sessionToken = localStorage.getItem('session_token');
    fetch(`${API_URL}?action=update_order_status`, {
        method: 'POST',
        body: JSON.stringify({ session_token: sessionToken, order_id: id, status: status }),
        credentials: 'include'
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // simple feedback
            } else {
                alert(data.message);
            }
        });
}

// User Management
function loadUsers() {
    const sessionToken = localStorage.getItem('session_token');
    fetch(`${API_URL}?action=get_all_users`, {
        method: 'POST',
        body: JSON.stringify({ session_token: sessionToken }),
        credentials: 'include'
    })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tbody = document.querySelector('#usersTable tbody');
                tbody.innerHTML = '';
                data.users.forEach((u, index) => {
                    const tr = document.createElement('tr');
                    const fullName = `${u.first_name || ''} ${u.last_name || ''}`.trim();
                    const displayName = fullName || u.username;
                    tr.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${displayName}</td>
                    <td>${u.email}</td>
                    <td>${u.role}</td>
                    <td>${new Date(u.created_at).toLocaleDateString()}</td>
                `;
                    tbody.appendChild(tr);
                });
            }
        });
}
