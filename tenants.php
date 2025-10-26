<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenants - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Tenants-specific styles */
        .tenants-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .tenants-title {
            flex: 1;
        }

        .tenants-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tenants-title p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .tenants-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-container {
            position: relative;
        }

        .search-container .search-icon {
            position: absolute;
            left: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 1;
        }

        .search-container input {
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            width: 300px;
            background-color: white;
            outline: none;
            transition: all 0.2s ease;
            font-size: 0.875rem;
        }

        .search-container input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .filters {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .tenants-table-container {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .table-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
        }

        .tenants-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tenants-table th {
            text-align: left;
            padding: 1rem 2rem;
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border-light);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .tenants-table td {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .tenants-table tr:hover td {
            background-color: rgba(79, 70, 229, 0.02);
        }

        .tenants-table tr:last-child td {
            border-bottom: none;
        }

        .tenant-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .tenant-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.125rem;
            box-shadow: var(--shadow-md);
        }

        .tenant-details {
            flex: 1;
        }

        .tenant-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .tenant-email {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .tenant-unit {
            font-weight: 500;
            color: var(--text-primary);
        }

        .tenant-phone {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .tenant-rent {
            font-weight: 600;
            color: var(--success-color);
            font-size: 1rem;
        }

        .tenant-date {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            box-shadow: var(--shadow-sm);
        }

        .status-badge.active {
            background: linear-gradient(135deg, var(--success-light) 0%, #a7f3d0 100%);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-badge.pending {
            background: linear-gradient(135deg, var(--warning-light) 0%, #fde68a 100%);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, var(--danger-light) 0%, #fecaca 100%);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-icon-btn {
            background: none;
            border: 2px solid var(--border-color);
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--radius);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-icon-btn:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .action-icon-btn.view:hover {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .action-icon-btn.edit:hover {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .action-icon-btn.delete:hover {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .no-tenants {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .no-tenants i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-tenants h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-tenants p {
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .tenants-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tenants-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .search-container input {
                width: 100%;
            }

            .filters {
                justify-content: center;
            }

            .tenants-table {
                font-size: 0.75rem;
            }

            .tenants-table th,
            .tenants-table td {
                padding: 0.75rem 1rem;
            }

            .tenant-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
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
                <a href="main.php" class="nav-item">
                    <i data-lucide="layout-dashboard" width="20" height="20"></i>
                    <span>Dashboard</span>
                </a>
                <a href="staff.php" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Staff</span>
                </a>
                <a href="renters.php" class="nav-item">
                    <i data-lucide="user" width="20" height="20"></i>
                    <span>Renters</span>
                </a>
                <a href="tenants.php" class="nav-item active">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Tenants</span>
                </a>
                <a href="units.php" class="nav-item">
                    <i data-lucide="building-2" width="20" height="20"></i>
                    <span>Units</span>
                </a>
                <a href="payments.php" class="nav-item">
                    <i data-lucide="dollar-sign" width="20" height="20"></i>
                    <span>Payments</span>
                </a>
                <a href="reports.php" class="nav-item">
                    <i data-lucide="file-text" width="20" height="20"></i>
                    <span>Reports</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="https://guiancarlosbuhawe-diaht.wordpress.com" target="_blank" class="nav-item">
                    <i data-lucide="home" width="20" height="20"></i>
                    <span>Website</span>
                </a>
                <a href="logout.php" class="nav-item">
                    <i data-lucide="log-out" width="20" height="20"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <div class="main-content">
            <header class="header">
                <div class="header-left">
                    <h2>Tenant Management</h2>
                    <p>Manage all tenants and their information</p>
                </div>
                <div class="header-right">
                    <div class="header-icons">
                        <button class="icon-btn">
                            <i data-lucide="bell" width="20" height="20"></i>
                            <div class="notification-badge"></div>
                        </button>
                        <div class="user-avatar">A</div>
                    </div>
                </div>
            </header>

            <div class="content-area">
                <!-- Tenants Header -->
                <div class="tenants-header">
                    <div class="tenants-title">
                        <h2>All Tenants</h2>
                        <p>Manage tenant information and track their status</p>
                    </div>
                    <div class="tenants-actions">
                        <div class="search-container">
                            <i data-lucide="search" class="search-icon"></i>
                            <input type="text" placeholder="Search tenants..." id="tenantSearch">
                        </div>
                        <button class="btn-primary" onclick="addTenant()">
                            <i data-lucide="user-plus" width="16"></i>
                            Add Tenant
                        </button>
                    </div>
                    </div>
                    
                <!-- Filters -->
                    <div class="filters">
                    <?php
                    // Get counts for each status
                    $allCount = $conn->query("SELECT COUNT(*) as count FROM tenants")->fetch_assoc()['count'];
                    $activeCount = $conn->query("SELECT COUNT(*) as count FROM tenants WHERE status = 'active'")->fetch_assoc()['count'];
                    $pendingCount = $conn->query("SELECT COUNT(*) as count FROM tenants WHERE status = 'pending'")->fetch_assoc()['count'];
                    $inactiveCount = $conn->query("SELECT COUNT(*) as count FROM tenants WHERE status = 'inactive'")->fetch_assoc()['count'];
                    ?>
                    <button class="filter-btn active" onclick="filterTenants('all')">
                        <i data-lucide="users" width="16"></i>
                        All (<?php echo $allCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterTenants('active')">
                        <i data-lucide="user-check" width="16"></i>
                        Active (<?php echo $activeCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterTenants('pending')">
                        <i data-lucide="user-clock" width="16"></i>
                        Pending (<?php echo $pendingCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterTenants('inactive')">
                        <i data-lucide="user-x" width="16"></i>
                        Inactive (<?php echo $inactiveCount; ?>)
                    </button>
                    </div>

                <!-- Tenants Table -->
                <div class="tenants-table-container">
                    <div class="table-header">
                        <h3>Tenant List</h3>
                        <div class="table-actions">
                            <button class="btn-secondary" onclick="exportTenants()">
                                <i data-lucide="download" width="16"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <table class="tenants-table">
                        <thead>
                            <tr>
                                <th>Tenant</th>
                                <th>Unit</th>
                                <th>Contact</th>
                                <th>Rent</th>
                                <th>Status</th>
                                <th>Move-in Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tenantsTableBody">
                            <?php
                            // Get all tenants with their unit information
                            $tenants = $conn->query("
                                SELECT t.*, u.unit_number, u.monthly_rent
                                FROM tenants t
                                LEFT JOIN units u ON t.unit_id = u.id
                                ORDER BY t.created_at DESC
                            ");
                            
                            if ($tenants->num_rows > 0) {
                                while($tenant = $tenants->fetch_assoc()) {
                                    $statusClass = strtolower($tenant['status']);
                                    $moveInDate = $tenant['move_in_date'] ? formatDate($tenant['move_in_date']) : 'N/A';
                                    $rentAmount = $tenant['monthly_rent'] ? formatCurrency($tenant['monthly_rent']) : 'N/A';
                                    $unitNumber = $tenant['unit_number'] ? "Unit {$tenant['unit_number']}" : 'N/A';
                                    $initials = strtoupper(substr($tenant['name'], 0, 2));
                                    
                                    echo "<tr data-status='{$tenant['status']}'>
                                            <td>
                                                <div class='tenant-info'>
                                                    <div class='tenant-avatar'>{$initials}</div>
                                                    <div class='tenant-details'>
                                                        <div class='tenant-name'>{$tenant['name']}</div>
                                                        <div class='tenant-email'>{$tenant['email']}</div>
                                                    </div>
                                                </div>
                                </td>
                                            <td>
                                                <div class='tenant-unit'>{$unitNumber}</div>
                                </td>
                                <td>
                                                <div class='tenant-phone'>{$tenant['phone']}</div>
                                </td>
                                            <td>
                                                <div class='tenant-rent'>{$rentAmount}</div>
                                </td>
                                <td>
                                                <span class='status-badge {$statusClass}'>{$tenant['status']}</span>
                                </td>
                                            <td>
                                                <div class='tenant-date'>{$moveInDate}</div>
                                </td>
                                            <td>
                                                <div class='action-buttons'>
                                                    <button class='action-icon-btn view' onclick='viewTenant({$tenant['id']})' title='View Details'>
                                                        <i data-lucide='eye' width='16'></i>
                                    </button>
                                                    <button class='action-icon-btn edit' onclick='editTenant({$tenant['id']})' title='Edit Tenant'>
                                                        <i data-lucide='edit' width='16'></i>
                                    </button>
                                                    <button class='action-icon-btn delete' onclick='deleteTenant({$tenant['id']})' title='Delete Tenant'>
                                                        <i data-lucide='trash-2' width='16'></i>
                                    </button>
                                                </div>
                                </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='7'>
                                            <div class='no-tenants'>
                                                <i data-lucide='users'></i>
                                                <h3>No tenants found</h3>
                                                <p>Start by adding your first tenant</p>
                                            </div>
                                </td>
                                      </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '80px';
                    } else {
                mainContent.style.marginLeft = '280px';
            }
        }

        // Search functionality
        document.getElementById('tenantSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tenantsTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filter functionality
        function filterTenants(status) {
            const rows = document.querySelectorAll('#tenantsTableBody tr');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update active filter button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                    row.style.animation = 'fadeIn 0.3s ease';
                    } else {
                    row.style.display = 'none';
                }
            });
        }

        // Tenant management functions
        function addTenant() {
            showNotification('Add tenant form would open here', 'info');
        }

        function viewTenant(id) {
            window.location.href = 'tenant_details.php?id=' + id;
        }

        function editTenant(id) {
            window.location.href = 'edit_tenant.php?id=' + id;
        }

        function deleteTenant(id) {
            if (confirm('Are you sure you want to delete this tenant?')) {
                const deleteBtn = event.target.closest('button');
                const originalContent = deleteBtn.innerHTML;
                deleteBtn.innerHTML = '<div class="loading"></div>';
                deleteBtn.disabled = true;
                
                fetch('delete_tenant.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Tenant deleted successfully!', 'success');
                        const row = deleteBtn.closest('tr');
                        row.style.transition = 'all 0.3s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-100%)';
                        setTimeout(() => row.remove(), 300);
                    } else {
                        showNotification('Error deleting tenant: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showNotification('Error deleting tenant', 'error');
                })
                .finally(() => {
                    deleteBtn.innerHTML = originalContent;
                    deleteBtn.disabled = false;
                });
            }
        }

        function exportTenants() {
            showNotification('Exporting tenants data...', 'info');
        }

        // Simple notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };
            
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border: 1px solid ${colors[type]};
                border-left: 4px solid ${colors[type]};
                border-radius: 8px;
                padding: 1rem 1.5rem;
                box-shadow: var(--shadow-lg);
                z-index: 1000;
                animation: slideInRight 0.3s ease;
                max-width: 300px;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
            
            .loading {
                width: 16px;
                height: 16px;
                border: 2px solid #e2e8f0;
                border-top: 2px solid #4f46e5;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>