<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piit Apartments - Admin Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: white;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            color: #60a5fa;
        }

        .logo-text h1 {
            font-size: 16px;
            font-weight: 700;
        }

        .logo-text p {
            font-size: 11px;
            color: #94a3b8;
        }

        .sidebar.collapsed .logo-text {
            display: none;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 5px;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Navigation */
        .nav-menu {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 8px;
            border-radius: 8px;
            color: #cbd5e1;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .sidebar.collapsed .nav-item span {
            display: none;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header */
        .header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left h2 {
            font-size: 24px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .header-left p {
            font-size: 14px;
            color: #64748b;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 16px 10px 42px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 300px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .header-icons {
            display: flex;
            gap: 12px;
        }

        .icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .icon-btn:hover {
            background: #f1f5f9;
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid white;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            overflow-y: auto;
            padding: 30px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .stat-icon.blue { background: #3b82f6; }
        .stat-icon.green { background: #10b981; }
        .stat-icon.purple { background: #8b5cf6; }
        .stat-icon.orange { background: #f97316; }

        .stat-info h3 {
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stat-info .value {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .stat-info .change {
            font-size: 13px;
            color: #10b981;
            font-weight: 500;
        }

        .stat-info .change.negative {
            color: #ef4444;
        }

        /* Main Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .card-header h3 {
            font-size: 18px;
            color: #1e293b;
            font-weight: 600;
        }

        .view-all {
            color: #3b82f6;
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
        }

        .view-all:hover {
            text-decoration: underline;
        }

        /* Tenants Table */
        .tenants-table {
            width: 100%;
        }

        .tenants-table thead tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .tenants-table th {
            text-align: left;
            padding: 12px 8px;
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tenants-table td {
            padding: 16px 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .tenant-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .tenant-unit {
            font-size: 13px;
            color: #64748b;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            gap: 12px;
        }

        .action-btn {
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }

        .action-btn:hover {
            background: #f8fafc;
            border-color: #3b82f6;
            transform: translateX(4px);
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #eff6ff;
            color: #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-text h4 {
            font-size: 14px;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .action-text p {
            font-size: 12px;
            color: #64748b;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #1e293b;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #1e293b;
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }

            .search-box input {
                width: 200px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .logo-text {
                display: none;
            }

            .nav-item span {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .header-right {
                gap: 8px;
            }

            .search-box {
                display: none;
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
                <a href="main.html" class="nav-item active">
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
                <a href="tenants.html" class="nav-item">
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

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <h2>Admin Dashboard</h2>
                    <p>Welcome back, Administrator</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon" width="20" height="20"></i>
                        <input type="text" placeholder="Search...">
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

            <!-- Content Area -->
            <div class="content-area">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i data-lucide="building-2" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>TOTAL UNITS</h3>
                            <div class="value" id="totalUnits">48</div>
                            <div class="change">+2 this month</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i data-lucide="users" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>OCCUPIED</h3>
                            <div class="value" id="occupiedUnits">42</div>
                            <div class="change">87.5% occupancy</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i data-lucide="dollar-sign" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>MONTHLY REVENUE</h3>
                            <div class="value" id="monthlyRevenue">₱284K</div>
                            <div class="change">+12% increase</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i data-lucide="alert-circle" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>PENDING REQUESTS</h3>
                            <div class="value" id="pendingRequests">7</div>
                            <div class="change negative">-3 from last week</div>
                        </div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="main-grid">
                    <!-- Recent Tenants -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Recent Tenants</h3>
                            <a href="#" class="view-all">View All</a>
                        </div>
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
                                <tr>
                                    <td>
                                        <div class="tenant-name">Maria Santos</div>
                                        <div class="tenant-unit">Unit 301</div>
                                    </td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>₱8,500</td>
                                    <td>Oct 5, 2025</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tenant-name">Juan Dela Cruz</div>
                                        <div class="tenant-unit">Unit 205</div>
                                    </td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>₱7,200</td>
                                    <td>Oct 3, 2025</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tenant-name">Ana Reyes</div>
                                        <div class="tenant-unit">Unit 412</div>
                                    </td>
                                    <td><span class="status-badge pending">Pending</span></td>
                                    <td>₱9,000</td>
                                    <td>Oct 1, 2025</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="tenant-name">Pedro Garcia</div>
                                        <div class="tenant-unit">Unit 108</div>
                                    </td>
                                    <td><span class="status-badge active">Active</span></td>
                                    <td>₱6,800</td>
                                    <td>Oct 8, 2025</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Quick Actions</h3>
                        </div>
                        <div class="quick-actions">
                            <button class="action-btn" onclick="openAddTenantModal()">
                                <div class="action-icon">
                                    <i data-lucide="user-plus" width="20" height="20"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Add New Tenant</h4>
                                    <p>Register new tenant</p>
                                </div>
                            </button>

                            <button class="action-btn" onclick="openAddUnitModal()">
                                <div class="action-icon">
                                    <i data-lucide="home" width="20" height="20"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Add New Unit</h4>
                                    <p>Create unit listing</p>
                                </div>
                            </button>

                            <button class="action-btn" onclick="openRecordPaymentModal()">
                                <div class="action-icon">
                                    <i data-lucide="credit-card" width="20" height="20"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Record Payment</h4>
                                    <p>Add rent payment</p>
                                </div>
                            </button>

                            <button class="action-btn" onclick="openGenerateReportModal()">
                                <div class="action-icon">
                                    <i data-lucide="file-text" width="20" height="20"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Generate Report</h4>
                                    <p>Create financial report</p>
                                </div>
                            </button>

                            <button class="action-btn">
                                <div class="action-icon">
                                    <i data-lucide="wrench" width="20" height="20"></i>
                                </div>
                                <div class="action-text">
                                    <h4>Maintenance Request</h4>
                                    <p>Log maintenance issue</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Tenant Modal -->
    <div id="addTenantModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Add New Tenant</div>
            <form id="addTenantForm">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="tenantName" required>
                </div>
                <div class="form-group">
                    <label>Unit</label>
                    <input type="text" id="tenantUnit" placeholder="e.g., Unit 301" required>
                </div>
                <div class="form-group">
                    <label>Rent Amount</label>
                    <input type="number" id="tenantRent" placeholder="e.g., 8500" required>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" id="tenantDueDate" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="tenantStatus">
                        <option>Active</option>
                        <option>Pending</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddTenantModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Tenant</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Unit Modal -->
    <div id="addUnitModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Add New Unit</div>
            <form id="addUnitForm">
                <div class="form-group">
                    <label>Unit Number</label>
                    <input type="text" id="unitNumber" placeholder="e.g., 501" required>
                </div>
                <div class="form-group">
                    <label>Floor</label>
                    <input type="number" id="unitFloor" required>
                </div>
                <div class="form-group">
                    <label>Monthly Rent</label>
                    <input type="number" id="unitRent" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="unitStatus">
                        <option>Available</option>
                        <option>Occupied</option>
                        <option>Maintenance</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddUnitModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Unit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div id="recordPaymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Record Payment</div>
            <form id="recordPaymentForm">
                <div class="form-group">
                    <label>Tenant Name</label>
                    <input type="text" id="paymentTenant" required>
                </div>
                <div class="form-group">
                    <label>Unit</label>
                    <input type="text" id="paymentUnit" required>
                </div>
                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" id="paymentAmount" required>
                </div>
                <div class="form-group">
                    <label>Payment Date</label>
                    <input type="date" id="paymentDate" required>
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <select id="paymentMethod">
                        <option>Cash</option>
                        <option>Bank Transfer</option>
                        <option>Check</option>
                        <option>GCash</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeRecordPaymentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Record Payment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Generate Report Modal -->
    <div id="generateReportModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Generate Report</div>
            <form id="generateReportForm">
                <div class="form-group">
                    <label>Report Type</label>
                    <select id="reportType">
                        <option>Monthly Revenue</option>
                        <option>Occupancy Rate</option>
                        <option>Pending Payments</option>
                        <option>Tenant Summary</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Month</label>
                    <input type="month" id="reportMonth" required>
                </div>
                <div class="form-group">
                    <label>Format</label>
                    <select id="reportFormat">
                        <option>PDF</option>
                        <option>Excel</option>
                        <option>CSV</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeGenerateReportModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Toggle sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        // Navigation handler
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Simulate real-time updates
        setInterval(() => {
            const badge = document.querySelector('.notification-badge');
            badge.style.animation = 'pulse 1s ease-in-out';
            setTimeout(() => badge.style.animation = '', 1000);
        }, 5000);

        // Add Tenant Functions
        function openAddTenantModal() {
            document.getElementById('addTenantModal').classList.add('active');
        }

        function closeAddTenantModal() {
            document.getElementById('addTenantModal').classList.remove('active');
            document.getElementById('addTenantForm').reset();
        }

        document.getElementById('addTenantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const name = document.getElementById('tenantName').value;
            const unit = document.getElementById('tenantUnit').value;
            const rent = document.getElementById('tenantRent').value;
            const dueDate = document.getElementById('tenantDueDate').value;
            const status = document.getElementById('tenantStatus').value;

            const tbody = document.getElementById('tenantsTableBody');
            const row = tbody.insertRow(0);
            row.innerHTML = `
                <td>
                    <div class="tenant-name">${name}</div>
                    <div class="tenant-unit">${unit}</div>
                </td>
                <td><span class="status-badge ${status.toLowerCase()}">${status}</span></td>
                <td>₱${parseInt(rent).toLocaleString()}</td>
                <td>${new Date(dueDate).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'})}</td>
            `;

            document.getElementById('occupiedUnits').textContent = parseInt(document.getElementById('occupiedUnits').textContent) + 1;
            alert('Tenant added successfully!');
            closeAddTenantModal();
        });

        // Add Unit Functions
        function openAddUnitModal() {
            document.getElementById('addUnitModal').classList.add('active');
        }

        function closeAddUnitModal() {
            document.getElementById('addUnitModal').classList.remove('active');
            document.getElementById('addUnitForm').reset();
        }

        document.getElementById('addUnitForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const unitNum = document.getElementById('unitNumber').value;
            const floor = document.getElementById('unitFloor').value;
            const rent = document.getElementById('unitRent').value;
            const status = document.getElementById('unitStatus').value;

            document.getElementById('totalUnits').textContent = parseInt(document.getElementById('totalUnits').textContent) + 1;
            alert(`Unit ${unitNum} added successfully!`);
            closeAddUnitModal();
        });

        // Record Payment Functions
        function openRecordPaymentModal() {
            document.getElementById('recordPaymentModal').classList.add('active');
        }

        function closeRecordPaymentModal() {
            document.getElementById('recordPaymentModal').classList.remove('active');
            document.getElementById('recordPaymentForm').reset();
        }

        document.getElementById('recordPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const tenant = document.getElementById('paymentTenant').value;
            const unit = document.getElementById('paymentUnit').value;
            const amount = document.getElementById('paymentAmount').value;
            const method = document.getElementById('paymentMethod').value;

            const currentRevenue = document.getElementById('monthlyRevenue').textContent;
            const numRevenue = parseInt(currentRevenue.replace(/[^0-9]/g, '')) + parseInt(amount);
            document.getElementById('monthlyRevenue').textContent = '₱' + (numRevenue / 1000).toFixed(0) + 'K';
            alert(`Payment of ₱${amount} recorded for ${tenant} via ${method}!`);
            closeRecordPaymentModal();
        });

        // Generate Report Functions
        function openGenerateReportModal() {
            document.getElementById('generateReportModal').classList.add('active');
        }

        function closeGenerateReportModal() {
            document.getElementById('generateReportModal').classList.remove('active');
            document.getElementById('generateReportForm').reset();
        }

        document.getElementById('generateReportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const type = document.getElementById('reportType').value;
            const month = document.getElementById('reportMonth').value;
            const format = document.getElementById('reportFormat').value;

            alert(`Generating ${type} report for ${month} in ${format} format...\nReport download will start soon!`);
            closeGenerateReportModal();
        });

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

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        });
    </script>
</body>
</html>