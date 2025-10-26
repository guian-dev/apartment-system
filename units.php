<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Units - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="units.css">
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
                <a href="units.html" class="nav-item active">
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
                    <span>Visit Website</span>
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
                    <h2>Units Management</h2>
                    <p>View and manage all apartment units</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon" width="20" height="20"></i>
                        <input type="text" placeholder="Search units..." id="searchUnits" onkeyup="searchUnits()">
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
                <div class="card">
                    <div class="card-header">
                        <h3>All Units</h3>
                        <button class="btn-primary" onclick="showAddUnitForm()">
                            <i data-lucide="plus" width="18" height="18"></i>
                            Add New Unit
                        </button>
                    </div>

                    <div class="filters">
                        <button class="filter-btn active" onclick="filterUnits('all')">All Units (48)</button>
                        <button class="filter-btn" onclick="filterUnits('occupied')">Occupied (42)</button>
                        <button class="filter-btn" onclick="filterUnits('available')">Available (6)</button>
                        <button class="filter-btn" onclick="filterUnits('maintenance')">Maintenance (0)</button>
                    </div>

                    <div class="units-grid" id="unitsGrid">
                        <div class="unit-card" data-status="occupied">
                            <div class="unit-header">
                                <h4>Unit 301</h4>
                                <span class="status-badge active">Occupied</span>
                            </div>
                            <div class="unit-details">
                                <div class="detail-item">
                                    <i data-lucide="bed" width="16"></i>
                                    <span>2 Bedrooms</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="bath" width="16"></i>
                                    <span>1 Bathroom</span>
                                </div>
                            </div>
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

        function searchUnits() {
            const input = document.getElementById('searchUnits');
            const filter = input.value.toUpperCase();
            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const unitNumber = card.querySelector('h4').textContent;
                if (unitNumber.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }

        function filterUnits(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                if (status === 'all') {
                    card.style.display = '';
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === status) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            }
        }

        function showAddUnitForm() {
            alert('Add New Unit form would open here. This would be a modal or separate page.');
        }

        function viewUnit(unitNumber) {
            alert('Viewing details for: ' + unitNumber);
        }

        function rentUnit(unitNumber) {
            alert('Renting unit: ' + unitNumber);
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
                                <div class="detail-item">
                                    <i data-lucide="maximize" width="16"></i>
                                    <span>45 sqm</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="user" width="16"></i>
                                    <span>Maria Santos</span>
                                </div>
                            </div>
                            <div class="unit-footer">
                                <div class="unit-price">₱8,500/month</div>
                                <button class="btn-secondary" onclick="viewUnit('Unit 301')">View Details</button>
                            </div>
                        </div>

                        <div class="unit-card" data-status="occupied">
                            <div class="unit-header">
                                <h4>Unit 205</h4>
                                <span class="status-badge active">Occupied</span>
                            </div>
                            <div class="unit-details">
                                <div class="detail-item">
                                    <i data-lucide="bed" width="16"></i>
                                    <span>1 Bedroom</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="bath" width="16"></i>
                                    <span>1 Bathroom</span>
                                </div>
                            </div>
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

        function searchUnits() {
            const input = document.getElementById('searchUnits');
            const filter = input.value.toUpperCase();
            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const unitNumber = card.querySelector('h4').textContent;
                if (unitNumber.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }

        function filterUnits(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                if (status === 'all') {
                    card.style.display = '';
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === status) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            }
        }

        function showAddUnitForm() {
            alert('Add New Unit form would open here. This would be a modal or separate page.');
        }

        function viewUnit(unitNumber) {
            alert('Viewing details for: ' + unitNumber);
        }

        function rentUnit(unitNumber) {
            alert('Renting unit: ' + unitNumber);
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
                                <div class="detail-item">
                                    <i data-lucide="maximize" width="16"></i>
                                    <span>35 sqm</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="user" width="16"></i>
                                    <span>Juan Dela Cruz</span>
                                </div>
                            </div>
                            <div class="unit-footer">
                                <div class="unit-price">₱7,200/month</div>
                                <button class="btn-secondary" onclick="viewUnit('Unit 205')">View Details</button>
                            </div>
                        </div>

                        <div class="unit-card available" data-status="available">
                            <div class="unit-header">
                                <h4>Unit 102</h4>
                                <span class="status-badge available">Available</span>
                            </div>
                            <div class="unit-details">
                                <div class="detail-item">
                                    <i data-lucide="bed" width="16"></i>
                                    <span>Studio</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="bath" width="16"></i>
                                    <span>1 Bathroom</span>
                                </div>
                            </div>
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

        function searchUnits() {
            const input = document.getElementById('searchUnits');
            const filter = input.value.toUpperCase();
            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const unitNumber = card.querySelector('h4').textContent;
                if (unitNumber.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }

        function filterUnits(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                if (status === 'all') {
                    card.style.display = '';
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === status) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            }
        }

        function showAddUnitForm() {
            alert('Add New Unit form would open here. This would be a modal or separate page.');
        }

        function viewUnit(unitNumber) {
            alert('Viewing details for: ' + unitNumber);
        }

        function rentUnit(unitNumber) {
            alert('Renting unit: ' + unitNumber);
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
                                <div class="detail-item">
                                    <i data-lucide="maximize" width="16"></i>
                                    <span>28 sqm</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="check-circle" width="16"></i>
                                    <span>Ready to Move</span>
                                </div>
                            </div>
                            <div class="unit-footer">
                                <div class="unit-price">₱6,000/month</div>
                                <button class="btn-success" onclick="rentUnit('Unit 102')">Rent Unit</button>
                            </div>
                        </div>

                        <div class="unit-card available" data-status="available">
                            <div class="unit-header">
                                <h4>Unit 405</h4>
                                <span class="status-badge available">Available</span>
                            </div>
                            <div class="unit-details">
                                <div class="detail-item">
                                    <i data-lucide="bed" width="16"></i>
                                    <span>3 Bedrooms</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="bath" width="16"></i>
                                    <span>2 Bathrooms</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="maximize" width="16"></i>
                                    <span>65 sqm</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="check-circle" width="16"></i>
                                    <span>Ready to Move</span>
                                </div>
                            </div>
                            <div class="unit-footer">
                                <div class="unit-price">₱12,000/month</div>
                                <button class="btn-success" onclick="rentUnit('Unit 405')">Rent Unit</button>
                            </div>
                        </div>

                        <div class="unit-card" data-status="occupied">
                            <div class="unit-header">
                                <h4>Unit 412</h4>
                                <span class="status-badge pending">Pending</span>
                            </div>
                            <div class="unit-details">
                                <div class="detail-item">
                                    <i data-lucide="bed" width="16"></i>
                                    <span>2 Bedrooms</span>
                                </div>
                                <div class="detail-item">
                                    <i data-lucide="bath" width="16"></i>
                                    <span>1 Bathroom</span>
                                </div>
                            </div>
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

        function searchUnits() {
            const input = document.getElementById('searchUnits');
            const filter = input.value.toUpperCase();
            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const unitNumber = card.querySelector('h4').textContent;
                if (unitNumber.toUpperCase().indexOf(filter) > -1) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        }

        function filterUnits(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const grid = document.getElementById('unitsGrid');
            const cards = grid.getElementsByClassName('unit-card');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                if (status === 'all') {
                    card.style.display = '';
                } else {
                    const cardStatus = card.getAttribute('data-status');
                    if (cardStatus === status) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                }
            }
        }

        function showAddUnitForm() {
            alert('Add New Unit form would open here. This would be a modal or separate page.');
        }

        function viewUnit(unitNumber) {
            alert('Viewing details for: ' + unitNumber);
        }

        function rentUnit(unitNumber) {
            alert('Renting unit: ' + unitNumber);
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