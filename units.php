<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Units - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Units-specific styles */
        .units-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .units-title {
            flex: 1;
        }

        .units-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .units-title p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .units-actions {
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

        .units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .unit-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .unit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .unit-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .unit-header {
            padding: 1.5rem 1.5rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .unit-header h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
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

        .status-badge.occupied {
            background: linear-gradient(135deg, var(--success-light) 0%, #a7f3d0 100%);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-badge.available {
            background: linear-gradient(135deg, var(--info-light) 0%, #bfdbfe 100%);
            color: var(--info-color);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .status-badge.maintenance {
            background: linear-gradient(135deg, var(--warning-light) 0%, #fde68a 100%);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .unit-details {
            padding: 0 1.5rem 1rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            background: rgba(79, 70, 229, 0.05);
            border-radius: var(--radius);
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .detail-item i {
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .unit-footer {
            padding: 1rem 1.5rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--border-light);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .unit-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--success-color);
        }

        .unit-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-secondary {
            background-color: white;
            color: var(--text-primary);
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .no-units {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
            grid-column: 1 / -1;
        }

        .no-units i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-units h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-units p {
            font-size: 0.875rem;
        }

        .units-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-mini {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-lg);
            padding: 1rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
            text-align: center;
            transition: all 0.2s ease;
        }

        .stat-mini:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-mini .value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-mini .label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .units-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .units-actions {
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

            .units-grid {
                grid-template-columns: 1fr;
            }

            .unit-details {
                grid-template-columns: 1fr;
            }

            .unit-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .unit-actions {
                justify-content: center;
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
                    <span>Staff Management</span>
                </a>
                <a href="renters.php" class="nav-item">
                    <i data-lucide="user" width="20" height="20"></i>
                    <span>Renters</span>
                </a>
                <a href="tenants.php" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Tenants</span>
                </a>
                <a href="units.php" class="nav-item active">
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
                    <h2>Unit Management</h2>
                    <p>Manage apartment units and their status</p>
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
                <!-- Units Header -->
                <div class="units-header">
                    <div class="units-title">
                        <h2>All Units</h2>
                        <p>Manage apartment units and track their occupancy</p>
                    </div>
                    <div class="units-actions">
                        <div class="search-container">
                            <i data-lucide="search" class="search-icon"></i>
                            <input type="text" placeholder="Search units..." id="unitSearch">
                        </div>
                        <button class="btn-primary" onclick="window.location.href='add_unit.php'">
                            <i data-lucide="plus" width="16"></i>
                            Add Unit
                        </button>
                    </div>
                    </div>

                <!-- Units Stats -->
                <div class="units-stats">
                    <?php
                    // Get counts for each status
                    $allCount = $conn->query("SELECT COUNT(*) as count FROM units")->fetch_assoc()['count'];
                    $occupiedCount = $conn->query("SELECT COUNT(*) as count FROM units WHERE status = 'occupied'")->fetch_assoc()['count'];
                    $availableCount = $conn->query("SELECT COUNT(*) as count FROM units WHERE status = 'available'")->fetch_assoc()['count'];
                    $maintenanceCount = $conn->query("SELECT COUNT(*) as count FROM units WHERE status = 'maintenance'")->fetch_assoc()['count'];
                    ?>
                    <div class="stat-mini">
                        <div class="value"><?php echo $allCount; ?></div>
                        <div class="label">Total Units</div>
                            </div>
                    <div class="stat-mini">
                        <div class="value"><?php echo $occupiedCount; ?></div>
                        <div class="label">Occupied</div>
                                </div>
                    <div class="stat-mini">
                        <div class="value"><?php echo $availableCount; ?></div>
                        <div class="label">Available</div>
                                </div>
                    <div class="stat-mini">
                        <div class="value"><?php echo $maintenanceCount; ?></div>
                        <div class="label">Maintenance</div>
        </div>
    </div>

                <!-- Filters -->
                <div class="filters">
                    <button class="filter-btn active" onclick="filterUnits('all')">
                        <i data-lucide="building-2" width="16"></i>
                        All Units (<?php echo $allCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterUnits('occupied')">
                        <i data-lucide="user-check" width="16"></i>
                        Occupied (<?php echo $occupiedCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterUnits('available')">
                        <i data-lucide="home" width="16"></i>
                        Available (<?php echo $availableCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterUnits('maintenance')">
                        <i data-lucide="wrench" width="16"></i>
                        Maintenance (<?php echo $maintenanceCount; ?>)
                    </button>
                </div>

                <!-- Units Grid -->
                <div class="units-grid" id="unitsGrid">
                    <?php
                    // Get all units with tenant information
                    $units = $conn->query("
                        SELECT u.*, t.name as tenant_name
                        FROM units u
                        LEFT JOIN tenants t ON u.id = t.unit_id AND t.status = 'active'
                        ORDER BY u.unit_number
                    ");
                    
                    if ($units->num_rows > 0) {
                        while($unit = $units->fetch_assoc()) {
                            $statusClass = strtolower($unit['status']);
                            $rentAmount = formatCurrency($unit['monthly_rent']);
                            $bedroomText = $unit['bedrooms'] == 1 ? '1 Bedroom' : ($unit['bedrooms'] == 0 ? 'Studio' : $unit['bedrooms'] . ' Bedrooms');
                            $bathroomText = $unit['bathrooms'] == 1 ? '1 Bathroom' : $unit['bathrooms'] . ' Bathrooms';
                            $areaText = $unit['area_sqm'] ? $unit['area_sqm'] . ' sqm' : 'N/A';
                            $tenantName = $unit['tenant_name'] ? $unit['tenant_name'] : 'Available';
                            
                            echo "<div class='unit-card' data-status='{$unit['status']}'>
                                    <div class='unit-header'>
                                        <h4>Unit {$unit['unit_number']}</h4>
                                        <span class='status-badge {$statusClass}'>{$unit['status']}</span>
                                </div>
                                    <div class='unit-details'>
                                        <div class='detail-item'>
                                            <i data-lucide='bed' width='16'></i>
                                            <span>{$bedroomText}</span>
                                </div>
                                        <div class='detail-item'>
                                            <i data-lucide='bath' width='16'></i>
                                            <span>{$bathroomText}</span>
                            </div>
                                        <div class='detail-item'>
                                            <i data-lucide='maximize' width='16'></i>
                                            <span>{$areaText}</span>
                            </div>
                                        <div class='detail-item'>
                                            <i data-lucide='user' width='16'></i>
                                            <span>{$tenantName}</span>
                                </div>
                            </div>
                                    <div class='unit-footer'>
                                        <div class='unit-price'>{$rentAmount}/month</div>
                                        <div class='unit-actions'>
                                            <button class='btn-secondary' onclick='viewUnit({$unit['id']})'>
                                                <i data-lucide='eye' width='16'></i>
                                                View
                                            </button>
                                            <button class='btn-secondary' onclick='editUnit({$unit['id']})'>
                                                <i data-lucide='edit' width='16'></i>
                                                Edit
                                            </button>
                        </div>
                    </div>
                                  </div>";
                        }
                    } else {
                        echo "<div class='no-units'>
                                <i data-lucide='building-2'></i>
                                <h3>No units found</h3>
                                <p>Start by adding your first unit</p>
                              </div>";
                    }
                    ?>
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
        document.getElementById('unitSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const cards = document.querySelectorAll('.unit-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

        // Filter functionality
        function filterUnits(status) {
            const cards = document.querySelectorAll('.unit-card');
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            // Update active filter button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            cards.forEach(card => {
                if (status === 'all' || card.dataset.status === status) {
                    card.style.display = '';
                    card.style.animation = 'fadeIn 0.3s ease';
                    } else {
                        card.style.display = 'none';
                    }
            });
        }

        // Unit management functions
        function addUnit() {
            showNotification('Add unit form would open here', 'info');
        }

        function viewUnit(id) {
            showNotification('Viewing unit details for ID: ' + id, 'info');
        }

        function editUnit(id) {
            showNotification('Editing unit ID: ' + id, 'info');
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
        `;
        document.head.appendChild(style);

        // Add hover effects to unit cards
        document.querySelectorAll('.unit-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>