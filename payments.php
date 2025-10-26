<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="payments.css">
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
                <a href="payments.php" class="nav-item active">
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
                    <span></span>
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
                    <h2>Reports & Analytics</h2>
                    <p>Generate and view detailed property reports</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon" width="20" height="20"></i>
                        <input type="text" placeholder="Search reports...">
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
                            <i data-lucide="trending-up" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>TOTAL REVENUE (2025)</h3>
                            <div class="value">₱2.8M</div>
                            <div class="change">+18% from 2024</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i data-lucide="bar-chart-3" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>AVG OCCUPANCY RATE</h3>
                            <div class="value">87.5%</div>
                            <div class="change">+5% this year</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i data-lucide="file-text" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>REPORTS GENERATED</h3>
                            <div class="value">156</div>
                            <div class="change">This year</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i data-lucide="download" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>EXPORTS</h3>
                            <div class="value">89</div>
                            <div class="change">PDF & Excel</div>
                        </div>
                    </div>
                </div>

                <div class="card" style="margin-bottom: 24px;">
                    <div class="card-header">
                        <h3>Quick Report Generation</h3>
                    </div>
                    <div class="reports-grid">
                        <div class="report-card" onclick="generateReport('financial')">
                            <div class="report-icon blue">
                                <i data-lucide="dollar-sign" width="24"></i>
                            </div>
                            <div class="report-title">Financial Summary</div>
                            <div class="report-description">Complete income and expense report with detailed breakdown</div>
                            <div class="report-meta">
                                <span><i data-lucide="calendar" width="14" style="display: inline; vertical-align: middle;"></i> Monthly/Yearly</span>
                                <span><i data-lucide="download" width="14" style="display: inline; vertical-align: middle;"></i> PDF, Excel</span>
                            </div>
                        </div>

                        <div class="report-card" onclick="generateReport('occupancy')">
                            <div class="report-icon green">
                                <i data-lucide="home" width="24"></i>
                            </div>
                            <div class="report-title">Occupancy Report</div>
                            <div class="report-description">Track unit occupancy rates and availability trends</div>
                            <div class="report-meta">
                                <span><i data-lucide="calendar" width="14" style="display: inline; vertical-align: middle;"></i> Monthly</span>
                                <span><i data-lucide="download" width="14" style="display: inline; vertical-align: middle;"></i> PDF, Excel</span>
                            </div>
                        </div>

                        <div class="report-card" onclick="generateReport('tenant')">
                            <div class="report-icon purple">
                                <i data-lucide="users" width="24"></i>
                            </div>
                            <div class="report-title">Tenant Analysis</div>
                            <div class="report-description">Comprehensive tenant demographics and payment history</div>
                            <div class="report-meta">
                                <span><i data-lucide="calendar" width="14" style="display: inline; vertical-align: middle;"></i> Custom Period</span>
                                <span><i data-lucide="download" width="14" style="display: inline; vertical-align: middle;"></i> PDF, Excel</span>
                            </div>
                        </div>

                        <div class="report-card" onclick="generateReport('payment')">
                            <div class="report-icon orange">
                                <i data-lucide="credit-card" width="24"></i>
                            </div>
                            <div class="report-title">Payment Collection</div>
                            <div class="report-description">Track payment collections, pending, and overdue amounts</div>
                            <div class="report-meta">
                                <span><i data-lucide="calendar" width="14" style="display: inline; vertical-align: middle;"></i> Monthly</span>
                                <span><i data-lucide="download" width="14" style="display: inline; vertical-align: middle;"></i> PDF, Excel</span>
                            </div>
                        </div>

                        <div class="report-card" onclick="generateReport('maintenance')">
                            <div class="report-icon red">
                                <i data-lucide="wrench" width="24"></i>
                            </div>
                            <div class="report-title">Maintenance Log</div>
                            <div class="report-description">Complete maintenance requests and resolution history</div>
                            <div class="report-meta">
                                <span><i data-lucide="calendar" width="14" style="display: inline; vertical-align: middle;"></i> Quarterly</span>
                                <span><i data-lucide="download" width="14" style="display: inline; vertical-align: middle;"></i> PDF, Excel</span>
                            </div>
                        </div>

                        <div class="report-card" onclick="generateReport('lease')">
                            <div class="report-icon yellow">
                                <i data-lucide="file-text" width="24"></i>
                            </div>
                            <div class="report-title">Lease Expiry</div>
                            <div class="report-description">Upcoming lease expirations and renewal tracking</div>
                            <div class="report-meta">
                                <span><i data-lucide="calendar" width="14" style="display: inline; vertical-align: middle;"></i> 30/60/90 Days</span>
                                <span><i data-lucide="download" width="14" style="display: inline; vertical-align: middle;"></i> PDF, Excel</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Recent Reports</h3>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <table class="tenants-table">
                        <thead>
                            <tr>
                                <th>Report Name</th>
                                <th>Type</th>
                                <th>Generated Date</th>
                                <th>Generated By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="tenant-name">Financial Summary - September 2025</div>
                                    <div class="tenant-unit">Monthly financial report</div>
                                </td>
                                <td><span class="status-badge active">Financial</span></td>
                                <td>Sep 28, 2025</td>
                                <td>Administrator</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewReport('Financial Summary')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="downloadReport('Financial Summary')">
                                        <i data-lucide="download" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="shareReport('Financial Summary')">
                                        <i data-lucide="share-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="tenant-name">Occupancy Analysis - Q3 2025</div>
                                    <div class="tenant-unit">Quarterly occupancy report</div>
                                </td>
                                <td><span class="status-badge active">Occupancy</span></td>
                                <td>Sep 25, 2025</td>
                                <td>Administrator</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewReport('Occupancy Analysis')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="downloadReport('Occupancy Analysis')">
                                        <i data-lucide="download" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="shareReport('Occupancy Analysis')">
                                        <i data-lucide="share-2" width="16"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="tenant-name">Payment Collection - September 2025</div>
                                    <div class="tenant-unit">Monthly payment report</div>
                                </td>
                                <td><span class="status-badge active">Payments</span></td>
                                <td>Sep 20, 2025</td>
                                <td>Administrator</td>
                                <td>
                                    <button class="action-icon-btn" onclick="viewReport('Payment Collection')">
                                        <i data-lucide="eye" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="downloadReport('Payment Collection')">
                                        <i data-lucide="download" width="16"></i>
                                    </button>
                                    <button class="action-icon-btn" onclick="shareReport('Payment Collection')">
                                        <i data-lucide="share-2" width="16"></i>
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

        function generateReport(type) {
            alert('Generating ' + type + ' report... This would open a dialog to select date range and format (PDF/Excel).');
        }

        function viewReport(name) {
            alert('Viewing report: ' + name);
        }

        function downloadReport(name) {
            alert('Downloading report: ' + name);
        }

        function shareReport(name) {
            alert('Share report: ' + name + ' via email or link.');
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