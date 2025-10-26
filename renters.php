<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="renters.css">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-section">
                    <i data-lucide="building-2" class="logo-icon"></i>
                    <div class="logo-text">
                        <h1>Kagay an View</h1>
                        <p>Renter Portal</p>
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
                    <span>Visit Website</span>
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
                    <h2>Welcome, Maria!</h2>
                    <p>Here's what's happening with your rental</p>
                </div>
                <div class="header-right">
                    <div class="notification-icon">
                        <i data-lucide="bell" width="20" height="20"></i>
                        <span class="notification-badge"></span>
                    </div>
                    <div class="user-avatar">MS</div>
                </div>
            </header>

            <div class="content-area">
                <!-- Rent Summary Card -->
                <div class="rent-summary-card">
                    <div class="rent-info">
                        <div class="rent-amount">₱8,500</div>
                        <div class="rent-period">Monthly Rent</div>
                    </div>
                    <div class="rent-status">
                        <div class="status-badge paid">Paid</div>
                        <div class="next-payment">Next payment: Oct 5, 2025</div>
                    </div>
                    <button class="btn-primary" onclick="makePayment()">
                        <i data-lucide="credit-card" width="18" height="18"></i>
                        Pay Rent
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <div class="action-card" onclick="goToPage('renter-maintenance.html')">
                        <div class="action-icon blue">
                            <i data-lucide="wrench" width="24" height="24"></i>
                        </div>
                        <div class="action-content">
                            <h3>Maintenance Request</h3>
                            <p>Submit a new maintenance request</p>
                        </div>
                    </div>
                    <div class="action-card" onclick="goToPage('renter-lease.html')">
                        <div class="action-icon green">
                            <i data-lucide="file-text" width="24" height="24"></i>
                        </div>
                        <div class="action-content">
                            <h3>View Lease</h3>
                            <p>Check your lease agreement</p>
                        </div>
                    </div>
                    <div class="action-card" onclick="goToPage('renter-messages.html')">
                        <div class="action-icon purple">
                            <i data-lucide="message-square" width="24" height="24"></i>
                        </div>
                        <div class="action-content">
                            <h3>Messages</h3>
                            <p>Communicate with management</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                        <a href="renter-payments.html" class="view-all">View All</a>
                    </div>
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon paid">
                                <i data-lucide="check-circle" width="20" height="20"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">Rent Payment</div>
                                <div class="activity-description">₱8,500 - September 2025</div>
                                <div class="activity-date">Sep 5, 2025</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon maintenance">
                                <i data-lucide="wrench" width="20" height="20"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">Maintenance Request</div>
                                <div class="activity-description">Kitchen sink repair - Completed</div>
                                <div class="activity-date">Sep 10, 2025</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon message">
                                <i data-lucide="message-square" width="20" height="20"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-title">New Message</div>
                                <div class="activity-description">From: Property Manager</div>
                                <div class="activity-date">Sep 12, 2025</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="card">
                    <div class="card-header">
                        <h3>Upcoming Events</h3>
                    </div>
                    <div class="events-list">
                        <div class="event-item">
                            <div class="event-date">
                                <div class="event-day">05</div>
                                <div class="event-month">OCT</div>
                            </div>
                            <div class="event-details">
                                <div class="event-title">Rent Due</div>
                                <div class="event-description">Monthly rent payment</div>
                            </div>
                        </div>
                        <div class="event-item">
                            <div class="event-date">
                                <div class="event-day">15</div>
                                <div class="event-month">OCT</div>
                            </div>
                            <div class="event-details">
                                <div class="event-title">Building Inspection</div>
                                <div class="event-description">Annual safety inspection</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Make a Payment</h3>
                <button class="close-modal" onclick="closeModal('paymentModal')">
                    <i data-lucide="x" width="20" height="20"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="payment-form">
                    <div class="payment-amount">
                        <label>Amount</label>
                        <div class="amount-input">₱8,500.00</div>
                    </div>
                    <div class="payment-method">
                        <label>Payment Method</label>
                        <div class="payment-options">
                            <div class="payment-option selected">
                                <input type="radio" name="paymentMethod" id="creditCard" checked>
                                <label for="creditCard">
                                    <i data-lucide="credit-card" width="20" height="20"></i>
                                    Credit/Debit Card
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" name="paymentMethod" id="bankTransfer">
                                <label for="bankTransfer">
                                    <i data-lucide="building-2" width="20" height="20"></i>
                                    Bank Transfer
                                </label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" name="paymentMethod" id="ewallet">
                                <label for="ewallet">
                                    <i data-lucide="smartphone" width="20" height="20"></i>
                                    E-Wallet
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card-details">
                        <label>Card Details</label>
                        <div class="form-group">
                            <input type="text" placeholder="Card Number" class="form-input">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" placeholder="MM/YY" class="form-input">
                            </div>
                            <div class="form-group">
                                <input type="text" placeholder="CVV" class="form-input">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="Cardholder Name" class="form-input">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal('paymentModal')">Cancel</button>
                <button class="btn-primary" onclick="processPayment()">Pay Now</button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Toggle text visibility for mobile view
            const logoText = document.querySelector('.logo-text');
            const navSpans = document.querySelectorAll('.nav-item span');
            const footerSpans = document.querySelectorAll('.sidebar-footer .nav-item span');
            
            if (sidebar.classList.contains('collapsed')) {
                logoText.style.display = 'none';
                navSpans.forEach(span => span.style.display = 'none');
                footerSpans.forEach(span => span.style.display = 'none');
            } else {
                logoText.style.display = 'block';
                navSpans.forEach(span => span.style.display = 'inline');
                footerSpans.forEach(span => span.style.display = 'inline');
            }
        }

        function goToPage(url) {
            window.location.href = url;
        }

        function makePayment() {
            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function processPayment() {
            // Create a notification
            const notification = document.createElement('div');
            notification.className = 'notification';
            notification.innerHTML = `
                <div class="notification-content">
                    <i data-lucide="check-circle" width="20" height="20"></i>
                    <span>Payment processed successfully!</span>
                </div>
            `;
            
            // Add notification to the page
            document.body.appendChild(notification);
            
            // Initialize Lucide icons for the notification
            lucide.createIcons();
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
            
            // Close modal
            closeModal('paymentModal');
        }

        // Initialize event listeners when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lucide icons
            lucide.createIcons();
        });
    </script>
</body>
</html>