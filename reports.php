<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Reports-specific styles */
        .reports-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .reports-title {
            flex: 1;
        }

        .reports-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .reports-title p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .reports-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .report-types {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .report-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            padding: 1.5rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .report-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .report-card:hover::before {
            transform: scaleX(1);
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .report-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .report-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .report-icon.blue {
            background: linear-gradient(135deg, var(--info-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .report-icon.green {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
        }

        .report-icon.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        .report-icon.orange {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: white;
        }

        .report-icon.red {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
        }

        .report-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .report-info p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .report-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-report {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--text-primary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-report:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-report.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-report.primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .recent-reports {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .reports-table-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .reports-table-header h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
        }

        .reports-table th {
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

        .reports-table td {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .reports-table tr:hover td {
            background-color: rgba(79, 70, 229, 0.02);
        }

        .reports-table tr:last-child td {
            border-bottom: none;
        }

        .report-name {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .report-type {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .report-date {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .report-author {
            color: var(--text-primary);
            font-weight: 500;
        }

        .report-status {
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

        .report-status.completed {
            background: linear-gradient(135deg, var(--success-light) 0%, #a7f3d0 100%);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .report-status.generating {
            background: linear-gradient(135deg, var(--warning-light) 0%, #fde68a 100%);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .report-status.failed {
            background: linear-gradient(135deg, var(--danger-light) 0%, #fecaca 100%);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .report-actions-table {
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

        .action-icon-btn.download:hover {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .action-icon-btn.delete:hover {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .no-reports {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .no-reports i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-reports h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-reports p {
            font-size: 0.875rem;
        }

        .report-stats {
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
            .reports-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .reports-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .report-types {
                grid-template-columns: 1fr;
            }

            .reports-table {
                font-size: 0.75rem;
            }

            .reports-table th,
            .reports-table td {
                padding: 0.75rem 1rem;
            }

            .report-actions-table {
                flex-direction: column;
            }

            .report-stats {
                grid-template-columns: repeat(2, 1fr);
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
                <a href="reports.php" class="nav-item active">
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
                    <h2>Reports & Analytics</h2>
                    <p>Generate and manage financial reports</p>
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
                <!-- Reports Header -->
                <div class="reports-header">
                    <div class="reports-title">
                        <h2>Financial Reports</h2>
                        <p>Generate comprehensive reports for your apartment management</p>
                    </div>
                    <div class="reports-actions">
                        <button class="btn-primary" onclick="generateCustomReport()">
                            <i data-lucide="plus" width="16"></i>
                            Custom Report
                        </button>
                    </div>
                </div>

                <!-- Report Stats -->
                <div class="report-stats">
                    <?php
                    // Get report statistics
                    $totalReportsResult = $conn->query("SELECT COUNT(*) as count FROM reports");
    $totalReports = $totalReportsResult ? $totalReportsResult->fetch_assoc()['count'] ?? 0 : 0;
    
    $thisMonthReportsResult = $conn->query("SELECT COUNT(*) as count FROM reports WHERE MONTH(generated_date) = MONTH(CURRENT_DATE()) AND YEAR(generated_date) = YEAR(CURRENT_DATE())");
    $thisMonthReports = $thisMonthReportsResult ? $thisMonthReportsResult->fetch_assoc()['count'] ?? 0 : 0;
    
    $totalRevenueResult = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'paid'");
    $totalRevenue = $totalRevenueResult ? $totalRevenueResult->fetch_assoc()['total'] ?? 0 : 0;
    
    $monthlyRevenueResult = $conn->query("SELECT SUM(amount) as total FROM payments WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) AND status = 'paid'");
    $monthlyRevenue = $monthlyRevenueResult ? $monthlyRevenueResult->fetch_assoc()['total'] ?? 0 : 0;
                    ?>
                    <div class="stat-mini">
                        <div class="value"><?php echo $totalReports; ?></div>
                        <div class="label">Total Reports</div>
                    </div>
                    <div class="stat-mini">
                        <div class="value"><?php echo $thisMonthReports; ?></div>
                        <div class="label">This Month</div>
                    </div>
                    <div class="stat-mini">
                        <div class="value"><?php echo formatCurrency($totalRevenue); ?></div>
                        <div class="label">Total Revenue</div>
                    </div>
                    <div class="stat-mini">
                        <div class="value"><?php echo formatCurrency($monthlyRevenue); ?></div>
                        <div class="label">Monthly Revenue</div>
                    </div>
                </div>

                <!-- Report Types -->
                <div class="report-types">
                    <div class="report-card" onclick="generateReport('financial')">
                        <div class="report-header">
                            <div class="report-icon blue">
                                <i data-lucide="dollar-sign" width="24" height="24"></i>
                    </div>
                            <div class="report-info">
                                <h3>Financial Report</h3>
                                <p>Complete financial overview including revenue, expenses, and profit margins</p>
                    </div>
                    </div>
                        <div class="report-actions">
                            <button class="btn-report primary" onclick="event.stopPropagation(); generateReport('financial')">
                                <i data-lucide="file-text" width="16"></i>
                                Generate
                            </button>
                            <button class="btn-report" onclick="event.stopPropagation(); previewReport('financial')">
                                <i data-lucide="eye" width="16"></i>
                                Preview
                    </button>
                        </div>
                </div>

                    <div class="report-card" onclick="generateReport('occupancy')">
                        <div class="report-header">
                            <div class="report-icon green">
                                <i data-lucide="users" width="24" height="24"></i>
                            </div>
                            <div class="report-info">
                                <h3>Occupancy Report</h3>
                                <p>Detailed analysis of unit occupancy rates and tenant statistics</p>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-report primary" onclick="event.stopPropagation(); generateReport('occupancy')">
                                <i data-lucide="file-text" width="16"></i>
                                Generate
                            </button>
                            <button class="btn-report" onclick="event.stopPropagation(); previewReport('occupancy')">
                                <i data-lucide="eye" width="16"></i>
                                Preview
                            </button>
                        </div>
                    </div>

                    <div class="report-card" onclick="generateReport('maintenance')">
                        <div class="report-header">
                            <div class="report-icon orange">
                                <i data-lucide="wrench" width="24" height="24"></i>
                            </div>
                            <div class="report-info">
                                <h3>Maintenance Report</h3>
                                <p>Track maintenance requests, costs, and completion status</p>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-report primary" onclick="event.stopPropagation(); generateReport('maintenance')">
                                <i data-lucide="file-text" width="16"></i>
                                Generate
                            </button>
                            <button class="btn-report" onclick="event.stopPropagation(); previewReport('maintenance')">
                                <i data-lucide="eye" width="16"></i>
                                Preview
                            </button>
                        </div>
                    </div>

                    <div class="report-card" onclick="generateReport('tenant')">
                        <div class="report-header">
                        <div class="report-icon purple">
                                <i data-lucide="user-check" width="24" height="24"></i>
                            </div>
                            <div class="report-info">
                                <h3>Tenant Report</h3>
                                <p>Comprehensive tenant information and payment history</p>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-report primary" onclick="event.stopPropagation(); generateReport('tenant')">
                                <i data-lucide="file-text" width="16"></i>
                                Generate
                            </button>
                            <button class="btn-report" onclick="event.stopPropagation(); previewReport('tenant')">
                                <i data-lucide="eye" width="16"></i>
                                Preview
                            </button>
                        </div>
                    </div>

                    <div class="report-card" onclick="generateReport('monthly')">
                        <div class="report-header">
                            <div class="report-icon red">
                                <i data-lucide="calendar" width="24" height="24"></i>
                            </div>
                            <div class="report-info">
                                <h3>Monthly Summary</h3>
                                <p>Monthly overview of all key metrics and performance indicators</p>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-report primary" onclick="event.stopPropagation(); generateReport('monthly')">
                                <i data-lucide="file-text" width="16"></i>
                                Generate
                            </button>
                            <button class="btn-report" onclick="event.stopPropagation(); previewReport('monthly')">
                                <i data-lucide="eye" width="16"></i>
                                Preview
                            </button>
                        </div>
                    </div>

                    <div class="report-card" onclick="generateReport('annual')">
                        <div class="report-header">
                            <div class="report-icon blue">
                                <i data-lucide="trending-up" width="24" height="24"></i>
                            </div>
                            <div class="report-info">
                                <h3>Annual Report</h3>
                                <p>Yearly comprehensive report with trends and analytics</p>
                            </div>
                        </div>
                        <div class="report-actions">
                            <button class="btn-report primary" onclick="event.stopPropagation(); generateReport('annual')">
                                <i data-lucide="file-text" width="16"></i>
                                Generate
                            </button>
                            <button class="btn-report" onclick="event.stopPropagation(); previewReport('annual')">
                                <i data-lucide="eye" width="16"></i>
                                Preview
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Reports -->
                <div class="recent-reports">
                    <div class="reports-table-header">
                        <h3>Recent Reports</h3>
                        <button class="btn-secondary" onclick="refreshReports()">
                            <i data-lucide="refresh-cw" width="16"></i>
                            Refresh
                        </button>
                    </div>
                    <table class="reports-table">
                        <thead>
                            <tr>
                                <th>Report Name</th>
                                <th>Type</th>
                                <th>Generated Date</th>
                                <th>Generated By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get recent reports
                            $reports = $conn->query("
                                SELECT * FROM reports 
                                ORDER BY generated_date DESC 
                                LIMIT 10
                            ");
                            
                            if ($reports->num_rows > 0) {
                                while($report = $reports->fetch_assoc()) {
                                    $statusClass = 'completed'; // Default status
                                    $generatedDate = formatDate($report['generated_date']);
                                    
                                    echo "<tr>
                                            <td>
                                                <div class='report-name'>{$report['report_name']}</div>
                                            </td>
                                            <td>
                                                <div class='report-type'>{$report['report_type']}</div>
                                            </td>
                                            <td>
                                                <div class='report-date'>{$generatedDate}</div>
                                            </td>
                                            <td>
                                                <div class='report-author'>{$report['generated_by']}</div>
                                            </td>
                                            <td>
                                                <span class='report-status {$statusClass}'>Completed</span>
                                            </td>
                                            <td>
                                                <div class='report-actions-table'>
                                                    <button class='action-icon-btn download' onclick='downloadReport({$report['id']})' title='Download Report'>
                                                        <i data-lucide='download' width='16'></i>
                                                    </button>
                                                    <button class='action-icon-btn delete' onclick='deleteReport({$report['id']})' title='Delete Report'>
                                                        <i data-lucide='trash-2' width='16'></i>
                                                    </button>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='6'>
                                            <div class='no-reports'>
                                                <i data-lucide='file-text'></i>
                                                <h3>No reports generated yet</h3>
                                                <p>Generate your first report to get started</p>
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

        // Report generation functions
        function generateReport(type) {
            showNotification(`Generating ${type} report...`, 'info');
            
            // Simulate report generation
            setTimeout(() => {
                showNotification(`${type.charAt(0).toUpperCase() + type.slice(1)} report generated successfully!`, 'success');
            }, 2000);
        }

        function previewReport(type) {
            showNotification(`Previewing ${type} report...`, 'info');
        }

        function generateCustomReport() {
            showNotification('Opening custom report builder...', 'info');
        }

        function downloadReport(id) {
            showNotification('Downloading report...', 'info');
        }

        function deleteReport(id) {
            if (confirm('Are you sure you want to delete this report?')) {
                showNotification('Report deleted successfully!', 'success');
            }
        }

        function refreshReports() {
            showNotification('Refreshing reports...', 'info');
            setTimeout(() => {
                location.reload();
            }, 1000);
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

        // Add hover effects to report cards
        document.querySelectorAll('.report-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-4px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                });
            });

        // Add hover effects to stat cards
        document.querySelectorAll('.stat-mini').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>