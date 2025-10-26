<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Payments-specific styles */
        .payments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .payments-title {
            flex: 1;
        }

        .payments-title h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            background: linear-gradient(135deg, var(--text-primary) 0%, var(--primary-color) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .payments-title p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .payments-actions {
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

        .payments-table-container {
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

        .payments-table {
            width: 100%;
            border-collapse: collapse;
        }

        .payments-table th {
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

        .payments-table td {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .payments-table tr:hover td {
            background-color: rgba(79, 70, 229, 0.02);
        }

        .payments-table tr:last-child td {
            border-bottom: none;
        }

        .payment-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .payment-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.125rem;
            box-shadow: var(--shadow-md);
        }

        .payment-details {
            flex: 1;
        }

        .payment-tenant {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 1rem;
        }

        .payment-unit {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .payment-amount {
            font-weight: 700;
            color: var(--success-color);
            font-size: 1.125rem;
        }

        .payment-date {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .payment-method {
            color: var(--text-primary);
            font-weight: 500;
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

        .status-badge.paid {
            background: linear-gradient(135deg, var(--success-light) 0%, #a7f3d0 100%);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .status-badge.pending {
            background: linear-gradient(135deg, var(--warning-light) 0%, #fde68a 100%);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-badge.overdue {
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

        .no-payments {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .no-payments i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .no-payments h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .no-payments p {
            font-size: 0.875rem;
        }

        .payment-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            padding: 1.5rem;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .summary-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .summary-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }

        .summary-icon.blue {
            background: linear-gradient(135deg, var(--info-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .summary-icon.green {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
        }

        .summary-icon.orange {
            background: linear-gradient(135deg, var(--warning-color) 0%, #d97706 100%);
            color: white;
        }

        .summary-icon.red {
            background: linear-gradient(135deg, var(--danger-color) 0%, #dc2626 100%);
            color: white;
        }

        .summary-title {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .summary-change {
            font-size: 0.875rem;
            color: var(--success-color);
            font-weight: 500;
        }

        .summary-change.negative {
            color: var(--danger-color);
        }

        @media (max-width: 768px) {
            .payments-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .payments-actions {
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

            .payments-table {
                font-size: 0.75rem;
            }

            .payments-table th,
            .payments-table td {
                padding: 0.75rem 1rem;
            }

            .payment-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .payment-summary {
                grid-template-columns: 1fr;
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
                    <h2>Payment Management</h2>
                    <p>Track and manage all rent payments</p>
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
                <!-- Payments Header -->
                <div class="payments-header">
                    <div class="payments-title">
                        <h2>All Payments</h2>
                        <p>Track rent payments and manage financial records</p>
                        </div>
                    <div class="payments-actions">
                        <div class="search-container">
                            <i data-lucide="search" class="search-icon"></i>
                            <input type="text" placeholder="Search payments..." id="paymentSearch">
                        </div>
                        <button class="btn-primary" onclick="addPayment()">
                            <i data-lucide="plus" width="16"></i>
                            Add Payment
                        </button>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="payment-summary">
                    <?php
                    // Get payment statistics
                    $totalRevenue = $conn->query("SELECT SUM(amount) as total FROM payments WHERE status = 'paid'")->fetch_assoc()['total'] ?? 0;
                    $monthlyRevenue = $conn->query("SELECT SUM(amount) as total FROM payments WHERE MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE()) AND status = 'paid'")->fetch_assoc()['total'] ?? 0;
                    $pendingPayments = $conn->query("SELECT COUNT(*) as count FROM payments WHERE status = 'pending'")->fetch_assoc()['count'];
                    $overduePayments = $conn->query("SELECT COUNT(*) as count FROM payments WHERE status = 'overdue'")->fetch_assoc()['count'];
                    ?>
                    <div class="summary-card">
                        <div class="summary-header">
                            <div class="summary-icon green">
                                <i data-lucide="dollar-sign" width="24" height="24"></i>
                    </div>
                            <div>
                                <div class="summary-title">Total Revenue</div>
                                <div class="summary-value"><?php echo formatCurrency($totalRevenue); ?></div>
                                <div class="summary-change">All time earnings</div>
                            </div>
                            </div>
                        </div>
                    <div class="summary-card">
                        <div class="summary-header">
                            <div class="summary-icon blue">
                                <i data-lucide="calendar" width="24" height="24"></i>
                            </div>
                            <div>
                                <div class="summary-title">This Month</div>
                                <div class="summary-value"><?php echo formatCurrency($monthlyRevenue); ?></div>
                                <div class="summary-change">Current month revenue</div>
                            </div>
                        </div>
                            </div>
                    <div class="summary-card">
                        <div class="summary-header">
                            <div class="summary-icon orange">
                                <i data-lucide="clock" width="24" height="24"></i>
                            </div>
                            <div>
                                <div class="summary-title">Pending</div>
                                <div class="summary-value"><?php echo $pendingPayments; ?></div>
                                <div class="summary-change">Awaiting payment</div>
                            </div>
                        </div>
                            </div>
                    <div class="summary-card">
                        <div class="summary-header">
                            <div class="summary-icon red">
                                <i data-lucide="alert-triangle" width="24" height="24"></i>
                            </div>
                            <div>
                                <div class="summary-title">Overdue</div>
                                <div class="summary-value"><?php echo $overduePayments; ?></div>
                                <div class="summary-change">Past due date</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="filters">
                    <?php
                    // Get counts for each status
                    $allCount = $conn->query("SELECT COUNT(*) as count FROM payments")->fetch_assoc()['count'];
                    $paidCount = $conn->query("SELECT COUNT(*) as count FROM payments WHERE status = 'paid'")->fetch_assoc()['count'];
                    $pendingCount = $conn->query("SELECT COUNT(*) as count FROM payments WHERE status = 'pending'")->fetch_assoc()['count'];
                    $overdueCount = $conn->query("SELECT COUNT(*) as count FROM payments WHERE status = 'overdue'")->fetch_assoc()['count'];
                    ?>
                    <button class="filter-btn active" onclick="filterPayments('all')">
                        <i data-lucide="list" width="16"></i>
                        All (<?php echo $allCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterPayments('paid')">
                        <i data-lucide="check-circle" width="16"></i>
                        Paid (<?php echo $paidCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterPayments('pending')">
                        <i data-lucide="clock" width="16"></i>
                        Pending (<?php echo $pendingCount; ?>)
                    </button>
                    <button class="filter-btn" onclick="filterPayments('overdue')">
                        <i data-lucide="alert-triangle" width="16"></i>
                        Overdue (<?php echo $overdueCount; ?>)
                    </button>
                </div>

                <!-- Payments Table -->
                <div class="payments-table-container">
                    <div class="table-header">
                        <h3>Payment Records</h3>
                        <div class="table-actions">
                            <button class="btn-secondary" onclick="exportPayments()">
                                <i data-lucide="download" width="16"></i>
                                Export
                            </button>
                        </div>
                    </div>
                    <table class="payments-table">
                        <thead>
                            <tr>
                                <th>Payment</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="paymentsTableBody">
                            <?php
                            // Get all payments with tenant and unit information
                            $payments = $conn->query("
                                SELECT p.*, t.name as tenant_name, u.unit_number
                                FROM payments p
                                LEFT JOIN tenants t ON p.tenant_id = t.id
                                LEFT JOIN units u ON p.unit_id = u.id
                                ORDER BY p.payment_date DESC
                            ");
                            
                            if ($payments->num_rows > 0) {
                                while($payment = $payments->fetch_assoc()) {
                                    $statusClass = strtolower($payment['status']);
                                    $paymentDate = formatDate($payment['payment_date']);
                                    $dueDate = $payment['due_date'] ? formatDate($payment['due_date']) : 'N/A';
                                    $amount = formatCurrency($payment['amount']);
                                    $tenantName = $payment['tenant_name'] ? $payment['tenant_name'] : 'Unknown';
                                    $unitNumber = $payment['unit_number'] ? "Unit {$payment['unit_number']}" : 'N/A';
                                    
                                    echo "<tr data-status='{$payment['status']}'>
                                            <td>
                                                <div class='payment-info'>
                                                    <div class='payment-icon'>
                                                        <i data-lucide='dollar-sign' width='20' height='20'></i>
                                                    </div>
                                                    <div class='payment-details'>
                                                        <div class='payment-tenant'>{$tenantName}</div>
                                                        <div class='payment-unit'>{$unitNumber}</div>
                                                    </div>
                                                </div>
                                </td>
                                            <td>
                                                <div class='payment-amount'>{$amount}</div>
                                </td>
                                <td>
                                                <div class='payment-date'>{$paymentDate}</div>
                                                <div class='payment-date' style='font-size: 0.75rem; color: var(--text-muted);'>Due: {$dueDate}</div>
                                </td>
                                            <td>
                                                <div class='payment-method'>{$payment['payment_method']}</div>
                                </td>
                                <td>
                                                <span class='status-badge {$statusClass}'>{$payment['status']}</span>
                                </td>
                                <td>
                                                <div class='action-buttons'>
                                                    <button class='action-icon-btn view' onclick='viewPayment({$payment['id']})' title='View Details'>
                                                        <i data-lucide='eye' width='16'></i>
                                    </button>
                                                    <button class='action-icon-btn edit' onclick='editPayment({$payment['id']})' title='Edit Payment'>
                                                        <i data-lucide='edit' width='16'></i>
                                    </button>
                                                    <button class='action-icon-btn delete' onclick='deletePayment({$payment['id']})' title='Delete Payment'>
                                                        <i data-lucide='trash-2' width='16'></i>
                                    </button>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr>
                                        <td colspan='6'>
                                            <div class='no-payments'>
                                                <i data-lucide='dollar-sign'></i>
                                                <h3>No payments found</h3>
                                                <p>Start by recording your first payment</p>
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
        document.getElementById('paymentSearch').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#paymentsTableBody tr');
            
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
        function filterPayments(status) {
            const rows = document.querySelectorAll('#paymentsTableBody tr');
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

        // Payment management functions
        function addPayment() {
            showNotification('Add payment form would open here', 'info');
        }

        function viewPayment(id) {
            showNotification('Viewing payment details for ID: ' + id, 'info');
        }

        function editPayment(id) {
            showNotification('Editing payment ID: ' + id, 'info');
        }

        function deletePayment(id) {
            if (confirm('Are you sure you want to delete this payment?')) {
                showNotification('Payment deleted successfully!', 'success');
            }
        }

        function exportPayments() {
            showNotification('Exporting payments data...', 'info');
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

        // Add hover effects to summary cards
        document.querySelectorAll('.summary-card').forEach(card => {
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