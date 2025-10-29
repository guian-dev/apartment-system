<?php 
include 'db.php'; 
// Simple staff access control - in production, use proper session management
session_start();
if (!isset($_SESSION['staff_logged_in'])) {
    $_SESSION['staff_logged_in'] = true; // For demo purposes - replace with real auth
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 1rem; }
        @media (max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }
        .quick-actions { display: grid; gap: 1rem; }
        .action-btn { padding: 1rem; border: 2px solid var(--border-color); border-radius: var(--radius-lg); display: flex; align-items: center; gap: 1rem; cursor: pointer; transition: all 0.2s ease; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); box-shadow: var(--shadow-sm); }
        .action-btn:hover { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; border-color: var(--primary-color); transform: translateX(4px); box-shadow: var(--shadow-md); }
        .action-icon { width: 48px; height: 48px; border-radius: var(--radius-lg); background: linear-gradient(135deg, var(--info-light) 0%, #bfdbfe 100%); color: var(--info-color); display: flex; align-items: center; justify-content: center; transition: all 0.2s ease; }
        .action-btn:hover .action-icon { background: rgba(255,255,255,0.2); color: white; }
        .action-text h4 { font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; }
        .action-text p { font-size: 0.875rem; opacity: 0.8; }
        .action-btn:hover .action-text p { opacity: 1; }
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
                        <p>Staff Panel</p>
                    </div>
                </div>
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i data-lucide="menu" width="20" height="20"></i>
                </button>
            </div>

            <nav class="nav-menu">
                <a href="staff_dashboard.php" class="nav-item active">
                    <i data-lucide="layout-dashboard" width="20" height="20"></i>
                    <span>Dashboard</span>
                </a>
                <a href="units.php" class="nav-item">
                    <i data-lucide="building-2" width="20" height="20"></i>
                    <span>Units</span>
                </a>
                <a href="tenants.php" class="nav-item">
                    <i data-lucide="users" width="20" height="20"></i>
                    <span>Tenants</span>
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
                    <h2>Staff Dashboard</h2>
                    <p>Overview of operations and quick actions</p>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" placeholder="Search..." id="globalSearch">
                    </div>
                    <div class="header-icons">
                        <button class="icon-btn">
                            <i data-lucide="bell" width="20" height="20"></i>
                            <div class="notification-badge"></div>
                        </button>
                        <button class="icon-btn">
                            <i data-lucide="settings" width="20" height="20"></i>
                        </button>
                        <div class="user-avatar">S</div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Stats Grid -->
                <div class="stats-grid">
                    <?php
                    $totalUnits = $conn->query("SELECT COUNT(*) as c FROM units")->fetch_assoc()['c'];
                    $availableUnits = $conn->query("SELECT COUNT(*) as c FROM units WHERE status = 'available'")->fetch_assoc()['c'];
                    $pendingReservations = ($conn->query("SHOW TABLES LIKE 'reservations'"))->num_rows ? ($conn->query("SELECT COUNT(*) as c FROM reservations WHERE status = 'pending'"))->fetch_assoc()['c'] : 0;
                    $openRequests = ($conn->query("SHOW TABLES LIKE 'maintenance_requests'"))->num_rows ? ($conn->query("SELECT COUNT(*) as c FROM maintenance_requests WHERE status IN ('pending','in_progress')"))->fetch_assoc()['c'] : 0;
                    ?>
                    <div class="stat-card">
                        <div class="stat-icon blue"><i data-lucide="building-2" width="28" height="28"></i></div>
                        <div class="stat-info"><h3>TOTAL UNITS</h3><div class="value"><?php echo $totalUnits; ?></div><div class="change">Inventory</div></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green"><i data-lucide="check-circle" width="28" height="28"></i></div>
                        <div class="stat-info"><h3>AVAILABLE</h3><div class="value"><?php echo $availableUnits; ?></div><div class="change">Ready to rent</div></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange"><i data-lucide="calendar-clock" width="28" height="28"></i></div>
                        <div class="stat-info"><h3>PENDING RESERVATIONS</h3><div class="value"><?php echo $pendingReservations; ?></div><div class="change">Awaiting action</div></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple"><i data-lucide="wrench" width="28" height="28"></i></div>
                        <div class="stat-info"><h3>OPEN REQUESTS</h3><div class="value"><?php echo $openRequests; ?></div><div class="change">Maintenance</div></div>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="main-grid">
                    <!-- Recent Reservations -->
                    <div class="card">
                        <div class="card-header">
                            <h3>Recent Reservations</h3>
                            <a href="customer.php" class="view-all">Browse Units</a>
                        </div>
                        <div style="padding: 0; overflow-x:auto;">
                            <table class="tenants-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Unit</th>
                                        <th>Move-in</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $hasRes = ($conn->query("SHOW TABLES LIKE 'reservations'"))->num_rows;
                                    if ($hasRes) {
                                        $res = $conn->query("SELECT r.*, u.unit_number, c.name AS customer_name FROM reservations r LEFT JOIN units u ON r.unit_id=u.id LEFT JOIN customers c ON r.customer_id=c.id ORDER BY r.created_at DESC LIMIT 6");
                                        if ($res && $res->num_rows) {
                                            while($row=$res->fetch_assoc()) {
                                                $statusClass = strtolower($row['status']);
                                                $moveIn = $row['move_in_date'] ? formatDate($row['move_in_date']) : 'N/A';
                                                echo '<tr>';
                                                echo '<td>'.htmlspecialchars($row['customer_name'] ?? 'N/A').'</td>';
                                                echo '<td>Unit '.htmlspecialchars($row['unit_number'] ?? '—').'</td>';
                                                echo '<td>'.$moveIn.'</td>';
                                                echo '<td><span class="status-badge '.$statusClass.'">'.ucfirst($row['status']).'</span></td>';
                                                echo '<td><a class="action-icon-btn" href="units.php"><i data-lucide="eye" width="16"></i></a></td>';
                                                echo '</tr>';
                                            }
                                        } else { echo "<tr><td colspan='5'>No reservations found</td></tr>"; }
                                    } else { echo "<tr><td colspan='5'>Reservations table not found</td></tr>"; }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header"><h3>Quick Actions</h3></div>
                        <div style="padding: 1.5rem;">
                            <div class="quick-actions">
                                <div class="action-btn" onclick="window.location.href='units.php'">
                                    <div class="action-icon"><i data-lucide="building-2" width="24" height="24"></i></div>
                                    <div class="action-text"><h4>Manage Units</h4><p>Add or update unit info</p></div>
                                </div>
                                <div class="action-btn" onclick="window.location.href='payments.php'">
                                    <div class="action-icon"><i data-lucide="dollar-sign" width="24" height="24"></i></div>
                                    <div class="action-text"><h4>Record Payment</h4><p>Accept rent payments</p></div>
                                </div>
                                <div class="action-btn" onclick="window.location.href='tenants.php'">
                                    <div class="action-icon"><i data-lucide="users" width="24" height="24"></i></div>
                                    <div class="action-text"><h4>Update Tenants</h4><p>Manage tenant data</p></div>
                                </div>
                                <div class="action-btn" onclick="window.location.href='reports.php'">
                                    <div class="action-icon"><i data-lucide="file-text" width="24" height="24"></i></div>
                                    <div class="action-text"><h4>Run Reports</h4><p>Financial and occupancy</p></div>
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
        function toggleSidebar(){ const s=document.getElementById('sidebar'); const m=document.querySelector('.main-content'); s.classList.toggle('collapsed'); m.style.marginLeft = s.classList.contains('collapsed') ? '80px' : '280px'; }
        document.getElementById('globalSearch')?.addEventListener('input', function(){ const term=this.value.toLowerCase(); document.querySelectorAll('table tbody tr').forEach(r=>{ r.style.display = r.textContent.toLowerCase().includes(term)?'':'none'; }); });
        document.querySelectorAll('.action-btn').forEach(btn=>{ btn.addEventListener('mouseenter',()=>btn.style.transform='translateX(4px)'); btn.addEventListener('mouseleave',()=>btn.style.transform='translateX(0)'); });
        document.querySelectorAll('.stat-card').forEach(card=>{ card.addEventListener('click',()=>{ card.style.transform='translateY(-4px) scale(1.02)'; setTimeout(()=>{ card.style.transform='translateY(-4px)'; },150); }); });
    </script>
</body>
</html>


