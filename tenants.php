<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenants - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="tenants.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-section">
                    <i data-lucide="building-2" class="logo-icon"></i>
                    <div class="logo-text">
                        <h1>Kagay an View</h1>
                        <p>Admin Panel</p>
                    </div>
                </div>
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i data-lucide="menu" width="20" height="20"></i>
                </button>
            </div>

            <nav class="nav-menu">
                <a href="main.html" class="nav-item">
                    <i data-lucide="layout-dashboard" width="20" height="20"></i>
                    <span>Dashboard</span>
                </a>
                <a href="staff.html" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Staff</span>
                </a>
                <a href="renters.html" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Renters</span>
                </a>
                <a href="tenants.html" class="nav-item active">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Tenants</span>
                </a>
                <a href="units.html" class="nav-item">
                    <i data-lucide="building-2" width="20" height="20"></i>
                    <span>Units</span>
                </a>
                <a href="payments.html" class="nav-item">
                    <i data-lucide="dollar-sign" width="20" height="20"></i>
                    <span>Payments</span>
                </a>
                <a href="reports.html" class="nav-item">
                    <i data-lucide="file-text" width="20" height="20"></i>
                    <span>Reports</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="https://guiancarlosbuhawe-diaht.wordpress.com" target="_blank" class="nav-item">
                    <i data-lucide="home" width="20" height="20"></i>
                    <span></span>
                </a>
                <a href="logout.html" class="nav-item">
                    <i data-lucide="log-out" width="20" height="20"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="main-content">
            <header class="header">
                <div class="header-left">
                    <h2>Tenants Management</h2>
                    <p>Manage all tenant information and records</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon" width="20" height="20"></i>
                        <input type="text" placeholder="Search tenants..." id="searchTenants" onkeyup="searchTenants()">
                    </div>
                    <div class="header-icons">
                        <button class="icon-btn">
                            <i data-lucide="bell" width="20" height="20"></i>
                            <span class="notification-badge"></span>
                        </button>
                        <div class="user-avatar">A</div>
                    </div>
                </div>
            </header>

            <div class="content-area">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i data-lucide="users" width="28" height="28"></i>
                        </div>
                <div class="card">
                    <div class="card-header">
                        <h3>All Tenants</h3>
                        <button class="btn-primary" onclick="showAddTenantForm()">
                            <i data-lucide="user-plus" width="18" height="18"></i>
                            Add New Tenant
                        </button>
                    </div>
                    
                    <div class="filters">
                        <button class="filter-btn active" onclick="filterTenants('all')">All (42)</button>
                        <button class="filter-btn" onclick="filterTenants('active')">Active (38)</button>
                        <button class="filter-btn" onclick="filterTenants('pending')">Pending (4)</button>
                        <button class="filter-btn" onclick="filterTenants('inactive')">Inactive (0)</button>
                    </div>

                    <table class="tenants-table" id="tenantsTable">
                        <thead>
                            <tr>
                                <th>Tenant Name</th>
                                <th>Unit</th>
                                <th>Phone</th>
                                <th>Rent Amount</th>
                                <th>Status</th>
                                <th>Move-in Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="tenant-name">Maria Santos</div>
                                    <div class="tenant-email">maria.santos@email.com</div>
                                </td>
                                <td>Unit 301</td>
                                <td>+63 912 345 6789</td>
                                <td>₱8,500</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Jan 15, 2024</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewTenant('Maria Santos')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="editTenant('Maria Santos')">
                                        <i data-lucide="edit" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="deleteTenant('Maria Santos')">
                                        <i data-lucide="trash-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="tenant-name">Juan Dela Cruz</div>
                                    <div class="tenant-email">juan.delacruz@email.com</div>
                                </td>
                                <td>Unit 205</td>
                                <td>+63 923 456 7890</td>
                                <td>₱7,200</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Mar 10, 2024</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewTenant('Juan Dela Cruz')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="editTenant('Juan Dela Cruz')">
                                        <i data-lucide="edit" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="deleteTenant('Juan Dela Cruz')">
                                        <i data-lucide="trash-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="tenant-name">Ana Reyes</div>
                                    <div class="tenant-email">ana.reyes@email.com</div>
                                </td>
                                <td>Unit 412</td>
                                <td>+63 934 567 8901</td>
                                <td>₱9,000</td>
                                <td><span class="status-badge pending">Pending</span></td>
                                <td>Sep 20, 2025</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewTenant('Ana Reyes')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="editTenant('Ana Reyes')">
                                        <i data-lucide="edit" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="deleteTenant('Ana Reyes')">
                                        <i data-lucide="trash-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="tenant-name">Pedro Garcia</div>
                                    <div class="tenant-email">pedro.garcia@email.com</div>
                                </td>
                                <td>Unit 108</td>
                                <td>+63 945 678 9012</td>
                                <td>₱6,800</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Jun 5, 2024</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewTenant('Pedro Garcia')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="editTenant('Pedro Garcia')">
                                        <i data-lucide="edit" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="deleteTenant('Pedro Garcia')">
                                        <i data-lucide="trash-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="tenant-name">Rosa Mendoza</div>
                                    <div class="tenant-email">rosa.mendoza@email.com</div>
                                </td>
                                <td>Unit 203</td>
                                <td>+63 956 789 0123</td>
                                <td>₱7,500</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>Feb 20, 2024</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewTenant('Rosa Mendoza')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="editTenant('Rosa Mendoza')">
                                        <i data-lucide="edit" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="deleteTenant('Rosa Mendoza')">
                                        <i data-lucide="trash-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function searchTenants() {
            const input = document.getElementById('searchTenants');
            const filter = input.value.toUpperCase();
            const table = document.getElementById('tenantsTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td')[0];
                if (td) {
                    const txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function filterTenants(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const table = document.getElementById('tenantsTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const statusCell = tr[i].getElementsByTagName('td')[4];
                if (status === 'all') {
                    tr[i].style.display = '';
                } else if (statusCell) {
                    const statusText = statusCell.textContent.toLowerCase();
                    if (statusText.includes(status)) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }

        function showAddTenantForm() {
            alert('Add New Tenant form would open here. This would be a modal or separate page.');
        }

        function viewTenant(name) {
            alert('Viewing details for: ' + name);
        }

        function editTenant(name) {
            alert('Editing tenant: ' + name);
        }

        function deleteTenant(name) {
            if (confirm('Are you sure you want to delete tenant: ' + name + '?')) {
                alert('Tenant deleted successfully!');
            }
        }

        // Renters Navigation Functions
        function navigateToRenters() {
            window.location.href = 'renters.html';
        }

        function showRentersInfo() {
            alert('Renters Portal: Access tenant information, payment history, and maintenance requests.');
        }

        function handleRentersNavigation() {
            // Add click event listeners to renters navigation
            const rentersLinks = document.querySelectorAll('a[href="renters.html"]');
            rentersLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    navigateToRenters();
                });
            });
        }

        // Initialize renters navigation on page load
        document.addEventListener('DOMContentLoaded', function() {
            handleRentersNavigation();
        });
    </script>
</body>
</html>