<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renter Portal - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Renter-specific styles */
        .rent-summary-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            padding: 2rem;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .rent-summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--success-color) 0%, #059669 100%);
        }

        .rent-info {
            flex: 1;
        }

        .rent-amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .rent-period {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 500;
        }

        .rent-status {
            text-align: center;
        }

        .status-badge.paid {
            background: linear-gradient(135deg, var(--success-light) 0%, #a7f3d0 100%);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .next-payment {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .action-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .action-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .action-card:hover::before {
            transform: scaleX(1);
        }

        .action-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--primary-color);
        }

        .action-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .action-icon.blue {
            background: linear-gradient(135deg, var(--info-color) 0%, #1d4ed8 100%);
            color: white;
        }

        .action-icon.green {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
        }

        .action-icon.purple {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
        }

        .action-content h3 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .action-content p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .activity-list {
            padding: 0;
        }

        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .activity-item:hover {
            background-color: rgba(79, 70, 229, 0.02);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-sm);
        }

        .activity-icon.paid {
            background: linear-gradient(135deg, var(--success-light) 0%, #a7f3d0 100%);
            color: var(--success-color);
        }

        .activity-icon.maintenance {
            background: linear-gradient(135deg, var(--warning-light) 0%, #fde68a 100%);
            color: var(--warning-color);
        }

        .activity-icon.message {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(139, 92, 246, 0.2) 100%);
            color: #8b5cf6;
        }

        .activity-details {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .activity-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .activity-date {
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 500;
        }

        .events-list {
            padding: 0;
        }

        .event-item {
            display: flex;
            gap: 1rem;
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .event-item:hover {
            background-color: rgba(79, 70, 229, 0.02);
        }

        .event-item:last-child {
            border-bottom: none;
        }

        .event-date {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: var(--radius-lg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-md);
        }

        .event-day {
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1;
        }

        .event-month {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .event-details {
            flex: 1;
        }

        .event-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .event-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.4;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .rent-summary-card {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .rent-amount {
                font-size: 2rem;
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
                <a href="renters.php" class="nav-item active">
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

        <div class="main-content">
            <header class="header">
                <div class="header-left">
                    <h2>Renter Portal</h2>
                    <p>Manage your apartment and payments</p>
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
                        <div class="user-avatar">R</div>
                    </div>
                </div>
            </header>

            <div class="content-area">
                <!-- Rent Summary Card -->
                <div class="rent-summary-card">
                    <div class="rent-info">
                        <div class="rent-amount">₱8,500.00</div>
                        <div class="rent-period">Monthly Rent</div>
                    </div>
                    <div class="rent-status">
                        <span class="status-badge paid">Paid</span>
                        <div class="next-payment">Next payment: Oct 5, 2025</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-card" onclick="openPaymentModal()">
                        <div class="action-icon blue">
                            <i data-lucide="credit-card" width="24" height="24"></i>
                        </div>
                        <div class="action-content">
                            <h3>Make Payment</h3>
                            <p>Pay your monthly rent online securely</p>
                        </div>
                    </div>
                    <div class="action-card" onclick="openMaintenanceModal()">
                        <div class="action-icon green">
                            <i data-lucide="wrench" width="24" height="24"></i>
                        </div>
                        <div class="action-content">
                            <h3>Request Maintenance</h3>
                            <p>Report issues or request repairs</p>
                        </div>
                    </div>
                    <div class="action-card" onclick="openMessageModal()">
                        <div class="action-icon purple">
                            <i data-lucide="message-circle" width="24" height="24"></i>
                        </div>
                        <div class="action-content">
                            <h3>Contact Management</h3>
                            <p>Send messages to property management</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Modal (hidden by default) -->
                <?php
                // Fetch tenants and their unit numbers for the payment form
                $tenantOptions = [];
                $tRes = $conn->query("SELECT t.id AS tenant_id, t.name AS tenant_name, t.unit_id, u.unit_number FROM tenants t LEFT JOIN units u ON t.unit_id = u.id ORDER BY t.name");
                if ($tRes && $tRes->num_rows > 0) {
                    while ($tr = $tRes->fetch_assoc()) {
                        $unitNum = $tr['unit_number'] ? $tr['unit_number'] : 'N/A';
                        $tenantOptions[] = ['id' => $tr['tenant_id'], 'label' => $tr['tenant_name'] . ' — Unit ' . $unitNum];
                    }
                }
                ?>

                <div id="paymentModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:2000;">
                    <div style="background:white; width:520px; max-width:95%; border-radius:12px; padding:1.25rem; box-shadow:var(--shadow-lg);">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.75rem;">
                            <h3 style="margin:0;">Make a Payment</h3>
                            <button onclick="closePaymentModal()" style="background:none; border:none; font-size:1.25rem; cursor:pointer;">&times;</button>
                        </div>
                        <form id="paymentForm">
                            <div style="margin-bottom:0.5rem;">
                                <label for="tenant_id">Tenant</label>
                                <select id="tenant_id" name="tenant_id" required style="width:100%; padding:0.5rem;">
                                    <option value="">-- Select tenant --</option>
                                    <?php foreach ($tenantOptions as $opt): ?>
                                        <option value="<?php echo $opt['id']; ?>"><?php echo htmlspecialchars($opt['label']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                                <div style="flex:1;">
                                    <label for="amount">Amount</label>
                                    <input id="amount" name="amount" type="number" step="0.01" min="0" required style="width:100%; padding:0.5rem;" />
                                </div>
                                <div style="width:160px;">
                                    <label for="payment_method">Method</label>
                                    <select id="payment_method" name="payment_method" required style="width:100%; padding:0.5rem;">
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="gcash">GCash</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="check">Check</option>
                                    </select>
                                </div>
                            </div>
                            <div style="margin-bottom:0.5rem;">
                                <label for="reference_number">Reference (optional)</label>
                                <input id="reference_number" name="reference_number" type="text" style="width:100%; padding:0.5rem;" />
                            </div>
                            <div style="margin-bottom:0.75rem;">
                                <label for="notes">Notes (optional)</label>
                                <textarea id="notes" name="notes" rows="3" style="width:100%; padding:0.5rem;"></textarea>
                            </div>
                            <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
                                <button type="button" onclick="closePaymentModal()" class="btn-secondary">Cancel</button>
                                <button type="submit" class="btn-primary">Submit Payment</button>
                            </div>
                        </form>
                        <div id="paymentMessage" style="margin-top:0.5rem;"></div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="main-grid">
                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                            <a href="#" class="view-all">View All</a>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon paid">
                                    <i data-lucide="check" width="20" height="20"></i>
                            </div>
                            <div class="activity-details">
                                    <div class="activity-title">Rent Payment Received</div>
                                    <div class="activity-description">Payment of ₱8,500.00 for September 2025 has been processed successfully</div>
                                    <div class="activity-date">Sep 1, 2025</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon maintenance">
                                <i data-lucide="wrench" width="20" height="20"></i>
                            </div>
                            <div class="activity-details">
                                    <div class="activity-title">Maintenance Request Completed</div>
                                    <div class="activity-description">Kitchen faucet repair has been completed by maintenance team</div>
                                    <div class="activity-date">Aug 28, 2025</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon message">
                                    <i data-lucide="message-circle" width="20" height="20"></i>
                            </div>
                            <div class="activity-details">
                                    <div class="activity-title">Message from Management</div>
                                    <div class="activity-description">Building maintenance scheduled for next week. Please prepare accordingly.</div>
                                    <div class="activity-date">Aug 25, 2025</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="card">
                    <div class="card-header">
                        <h3>Upcoming Events</h3>
                            <a href="#" class="view-all">View All</a>
                    </div>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date">
                                <div class="event-day">05</div>
                                    <div class="event-month">Oct</div>
                            </div>
                            <div class="event-details">
                                <div class="event-title">Rent Due</div>
                                    <div class="event-description">Monthly rent payment due</div>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <div class="event-day">15</div>
                                    <div class="event-month">Oct</div>
                            </div>
                            <div class="event-details">
                                <div class="event-title">Building Inspection</div>
                                    <div class="event-description">Quarterly building maintenance</div>
                                </div>
                            </div>
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="event-day">20</div>
                                    <div class="event-month">Oct</div>
                                </div>
                                <div class="event-details">
                                    <div class="event-title">Community Meeting</div>
                                    <div class="event-description">Monthly residents meeting</div>
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

        // Renter functions
        function openPaymentModal() {
            const modal = document.getElementById('paymentModal');
            if (modal) {
                modal.style.display = 'flex';
            } else {
                showNotification('Payment modal not available', 'error');
            }
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            if (modal) modal.style.display = 'none';
            const msg = document.getElementById('paymentMessage');
            if (msg) msg.innerHTML = '';
            const form = document.getElementById('paymentForm');
            if (form) form.reset();
        }

        // Handle payment form submission
        document.addEventListener('DOMContentLoaded', function() {
            const paymentForm = document.getElementById('paymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(paymentForm);
                    const btn = paymentForm.querySelector('button[type="submit"]');
                    if (btn) btn.disabled = true;

                    fetch('process_payment.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(r => r.json())
                    .then(data => {
                        const msg = document.getElementById('paymentMessage');
                        if (data.success) {
                            if (msg) msg.innerHTML = '<div style="color:green;">' + data.message + '</div>';
                            setTimeout(() => { closePaymentModal(); location.reload(); }, 900);
                        } else {
                            if (msg) msg.innerHTML = '<div style="color:red;">' + (data.message || 'Error') + '</div>';
                        }
                    })
                    .catch(err => {
                        const msg = document.getElementById('paymentMessage');
                        if (msg) msg.innerHTML = '<div style="color:red;">Network error</div>';
                    })
                    .finally(() => { if (btn) btn.disabled = false; });
                });
            }
        });

        function openMaintenanceModal() {
            showNotification('Maintenance request modal would open here', 'info');
        }

        function openMessageModal() {
            showNotification('Message modal would open here', 'info');
        }

        // Simple notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: white;
                border: 1px solid var(--border-color);
                border-radius: 8px;
                padding: 1rem;
                box-shadow: var(--shadow-lg);
                z-index: 1000;
                animation: slideInRight 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add hover effects to action cards
        document.querySelectorAll('.action-card').forEach(card => {
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