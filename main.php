<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kagay an View - Admin Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Additional styles specific to main dashboard */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-top: 1rem;
        }

        .quick-actions {
            display: grid;
            gap: 1rem;
        }

        .action-btn {
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: var(--shadow-sm);
        }

        .action-btn:hover {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, var(--info-light) 0%, #bfdbfe 100%);
            color: var(--info-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-btn:hover .action-icon {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .action-text h4 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .action-text p {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .action-btn:hover .action-text p {
            opacity: 1;
        }

        .tenant-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .tenant-unit {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .tenant-email {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
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
                <a href="main.php" class="nav-item active">
                    <i data-lucide="layout-dashboard" width="20" height="20"></i>
                    <span>Dashboard</span>
                </a>
                <a href="staff.php" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Staff</span>
                </a>
                <a href="renters.php" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Renters</span>
                </a>
                <a href="tenants.php" class="nav-item">
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

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h2>Dashboard Overview</h2>
                    <p>Welcome to Kagay an View Apartment Management System</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="header-icons">
                        <button class="icon-btn">
                            <i data-lucide="bell" width="20" height="20"></i>
                            <div class="notification-badge"></div>
                        </button>
                        <button class="icon-btn">
                            <i data-lucide="settings" width="20" height="20"></i>
                        </button>
                        <div class="user-avatar">A</div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <?php
                    // Get total units count
                    $totalUnitsResult = $conn->query("SELECT COUNT(*) as count FROM units");
                    $totalUnits = $totalUnitsResult ? $totalUnitsResult->fetch_assoc()['count'] ?? 0 : 0;
                    
                    // Get occupied units count
                    $occupiedUnitsResult = $conn->query("SELECT COUNT(*) as count FROM units WHERE status = 'occupied'");
                    $occupiedUnits = $occupiedUnitsResult ? $occupiedUnitsResult->fetch_assoc()['count'] ?? 0 : 0;
                    
                    // Get monthly revenue
                    $monthlyRevenueResult = $conn->query("SELECT SUM(amount) as total FROM payments WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) AND status = 'paid'");
                    $monthlyRevenue = $monthlyRevenueResult ? $monthlyRevenueResult->fetch_assoc()['total'] ?? 0 : 0;
                    
                    // Get pending maintenance requests
                    $pendingRequestsResult = $conn->query("SELECT COUNT(*) as count FROM maintenance_requests WHERE status = 'pending'");
                    $pendingRequests = $pendingRequestsResult ? $pendingRequestsResult->fetch_assoc()['count'] ?? 0 : 0;
                    
                    // Calculate occupancy percentage
                    $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;
                    ?>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i data-lucide="building-2" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>TOTAL UNITS</h3>
                            <div class="value" id="totalUnits"><?php echo $totalUnits; ?></div>
                            <div class="change">All units in building</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i data-lucide="users" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>OCCUPIED</h3>
                            <div class="value" id="occupiedUnits"><?php echo $occupiedUnits; ?></div>
                            <div class="change"><?php echo $occupancyRate; ?>% occupancy rate</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i data-lucide="dollar-sign" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>MONTHLY REVENUE</h3>
                            <div class="value" id="monthlyRevenue"><?php echo formatCurrency($monthlyRevenue); ?></div>
                            <div class="change">This month's income</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i data-lucide="wrench" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>PENDING REQUESTS</h3>
                            <div class="value" id="pendingRequests"><?php echo $pendingRequests; ?></div>
                            <div class="change">Maintenance requests</div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="main-grid">
                    <!-- Recent Tenants -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Recent Tenants</h3>
                            <a href="tenants.php" class="view-all">View All</a>
                        </div>
                        <div style="padding: 0;">
                        <table class="tenants-table">
                            <thead>
                                <tr>
                                    <th>Tenant</th>
                                    <th>Status</th>
                                    <th>Rent</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody id="tenantsTableBody">
                                    <?php
                                    // Get recent tenants with their unit and payment info
                                    $recentTenants = $conn->query("
                                        SELECT t.*, u.unit_number, u.monthly_rent,
                                               p.due_date, p.status as payment_status
                                        FROM tenants t
                                        LEFT JOIN units u ON t.unit_id = u.id
                                        LEFT JOIN payments p ON t.id = p.tenant_id 
                                            AND MONTH(p.due_date) = MONTH(CURRENT_DATE()) 
                                            AND YEAR(p.due_date) = YEAR(CURRENT_DATE())
                                        WHERE t.status IN ('active', 'pending')
                                        ORDER BY t.created_at DESC
                                        LIMIT 5
                                    ");
                                    
                                    if ($recentTenants->num_rows > 0) {
                                        while($tenant = $recentTenants->fetch_assoc()) {
                                            $statusClass = strtolower($tenant['status']);
                                            $dueDate = $tenant['due_date'] ? formatDate($tenant['due_date']) : 'N/A';
                                            $rentAmount = $tenant['monthly_rent'] ? formatCurrency($tenant['monthly_rent']) : 'N/A';
                                            
                                            echo "<tr>
                                                    <td>
                                                        <div class='tenant-name'>{$tenant['name']}</div>
                                                        <div class='tenant-unit'>Unit {$tenant['unit_number']}</div>
                                    </td>
                                                    <td><span class='status-badge {$statusClass}'>{$tenant['status']}</span></td>
                                                    <td>{$rentAmount}</td>
                                                    <td>{$dueDate}</td>
                                                  </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No tenants found</td></tr>";
                                    }
                                    ?>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div style="padding: 1.5rem;">
                        <div class="quick-actions">
                                <div class="action-btn" onclick="window.location.href='tenants.php'">
                                <div class="action-icon">
                                        <i data-lucide="user-plus" width="24" height="24"></i>
                                </div>
                                <div class="action-text">
                                        <h4>Add Tenant</h4>
                                    <p>Register new tenant</p>
                                </div>
                                </div>
                                <div class="action-btn" onclick="window.location.href='units.php'">
                                <div class="action-icon">
                                        <i data-lucide="building-2" width="24" height="24"></i>
                                </div>
                                <div class="action-text">
                                        <h4>Manage Units</h4>
                                        <p>View and edit units</p>
                                    </div>
                                </div>
                                <div class="action-btn" onclick="window.location.href='payments.php'">
                                <div class="action-icon">
                                        <i data-lucide="dollar-sign" width="24" height="24"></i>
                                </div>
                                <div class="action-text">
                                        <h4>Process Payment</h4>
                                        <p>Record rent payment</p>
                                    </div>
                                </div>
                                <div class="action-btn" onclick="window.location.href='reports.php'">
                                <div class="action-icon">
                                        <i data-lucide="file-text" width="24" height="24"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Generate Report</h4>
                                        <p>Create financial reports</p>
                                </div>
                                </div>
                                </div>
                        </div>
                    </div>
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
        document.querySelector('.search-box input').addEventListener('input', function() {
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

        // Add hover effects to action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(4px)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
                });
            });

        // Add click animations to stat cards
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'translateY(-4px) scale(1.02)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-4px)';
                }, 150);
            });
        });
    </script>
</body>
</html>