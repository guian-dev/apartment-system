<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Reports - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="reports.css">
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
                <a href="reports.html" class="nav-item active">
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
                    <h2>Payment Reports</h2>
                    <p>Generate and view payment reports</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon" width="20" height="20"></i>
                        <input type="text" placeholder="Search payment reports...">
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
                <div class="report-filters">
                    <div class="filter-group">
                        <label>Payment Type</label>
                        <select class="filter-select">
                            <option>All Payments</option>
                            <option>Rent Payments</option>
                            <option>Utility Payments</option>
                            <option>Late Fees</option>
                            <option>Deposits</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Date Range</label>
                        <select class="filter-select">
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>Last 3 Months</option>
                            <option>This Year</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select class="filter-select">
                            <option>All Statuses</option>
                            <option>Paid</option>
                            <option>Pending</option>
                            <option>Overdue</option>
                            <option>Partial</option>
                        </select>
                    </div>
                    <button class="btn-primary">
                        <i data-lucide="download" width="18" height="18"></i>
                        Generate Report
                    </button>
                </div>

                <div class="reports-grid">
                    <div class="report-card">
                        <div class="report-icon blue">
                            <i data-lucide="dollar-sign" width="32" height="32"></i>
                        </div>
                        <h3>Payment Summary</h3>
                        <p>Overview of all payment transactions</p>
                        <div class="report-actions">
                            <button class="btn-secondary">View Report</button>
                            <button class="icon-btn"><i data-lucide="download" width="18"></i></button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-icon green">
                            <i data-lucide="trending-up" width="32" height="32"></i>
                        </div>
                        <h3>Payment Trends</h3>
                        <p>Payment patterns over time</p>
                        <div class="report-actions">
                            <button class="btn-secondary">View Report</button>
                            <button class="icon-btn"><i data-lucide="download" width="18"></i></button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-icon purple">
                            <i data-lucide="credit-card" width="32" height="32"></i>
                        </div>
                        <h3>Payment Methods</h3>
                        <p>Analysis of payment methods used</p>
                        <div class="report-actions">
                            <button class="btn-secondary">View Report</button>
                            <button class="icon-btn"><i data-lucide="download" width="18"></i></button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-icon orange">
                            <i data-lucide="alert-circle" width="32" height="32"></i>
                        </div>
                        <h3>Late Payments</h3>
                        <p>Report on overdue and late payments</p>
                        <div class="report-actions">
                            <button class="btn-secondary">View Report</button>
                            <button class="icon-btn"><i data-lucide="download" width="18"></i></button>
                        </div>
                    </div>

                    <div class="report-card">
                        <div class="report-icon red">
                            <i data-lucide="users" width="32" height="32"></i>
                        </div>
                        <h3>Tenant Payment History</h3>
                        <p>Track tenant payment patterns and history</p>
                        <div class="report-actions">
                            <button class="btn-secondary">View Report</button>
                            <button class="icon-btn"><i data-lucide="download" width="18"></i></button>
                        </div>
                    </div>
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