<?php 
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
</head>
<body>
    <?php
    $actionMessage = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (isset($_POST['approve_reservation']) && isset($_POST['reservation_id'])) {
                $hasRes = ($conn->query("SHOW TABLES LIKE 'reservations'"))->num_rows;
                if ($hasRes) {
                    $rid = (int)$_POST['reservation_id'];
                    $stmt = $conn->prepare("UPDATE reservations SET status='approved' WHERE id=?");
                    $stmt->bind_param('i', $rid);
                    $stmt->execute();
                    $actionMessage = 'Reservation approved.';
                }
            }
            if (isset($_POST['decline_reservation']) && isset($_POST['reservation_id'])) {
                $hasRes = ($conn->query("SHOW TABLES LIKE 'reservations'"))->num_rows;
                if ($hasRes) {
                    $rid = (int)$_POST['reservation_id'];
                    $stmt = $conn->prepare("UPDATE reservations SET status='declined' WHERE id=?");
                    $stmt->bind_param('i', $rid);
                    $stmt->execute();
                    $actionMessage = 'Reservation declined.';
                }
            }
            if (isset($_POST['update_request']) && isset($_POST['request_id']) && isset($_POST['new_status'])) {
                $hasMr = ($conn->query("SHOW TABLES LIKE 'maintenance_requests'"))->num_rows;
                if ($hasMr) {
                    $rqid = (int)$_POST['request_id'];
                    $ns = $_POST['new_status'];
                    $stmt = $conn->prepare("UPDATE maintenance_requests SET status=? WHERE id=?");
                    $stmt->bind_param('si', $ns, $rqid);
                    $stmt->execute();
                    $actionMessage = 'Maintenance request updated.';
                }
            }
        } catch (Throwable $e) {
            $actionMessage = 'Error: ' . $e->getMessage();
        }
    }
    ?>

    <div class="dashboard-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo-section">
                    <i data-lucide="building-2" class="logo-icon"></i>
                    <div class="logo-text">
                        <h1>Kagay an View</h1>
                        <p>Staff Portal</p>
                    </div>
                </div>
                <button class="toggle-btn" onclick="toggleSidebar()">
                    <i data-lucide="menu" width="20" height="20"></i>
                </button>
            </div>

            <nav class="nav-menu">
                <a href="staff_dashboard.php" class="nav-item">
                    <i data-lucide="layout-dashboard" width="20" height="20"></i>
                    <span>Dashboard</span>
                </a>
                <a href="staff_portal.php" class="nav-item active">
                    <i data-lucide="grid" width="20" height="20"></i>
                    <span>Portal</span>
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

        <div class="main-content">
            <header class="header">
                <div class="header-left">
                    <h2>Staff Operations</h2>
                    <p>Handle reservations, maintenance, payments, and feedback</p>
                </div>
            </header>

            <div class="content-area">
                <?php if ($actionMessage): ?>
                    <div class="card" style="border-left:4px solid var(--info-color);">
                        <div class="card-header"><h3>Update</h3></div>
                        <div style="padding:1rem;"><?php echo htmlspecialchars($actionMessage); ?></div>
                    </div>
                <?php endif; ?>

                <div class="stats-grid">
                    <?php
                    $totalUnits = $conn->query("SELECT COUNT(*) c FROM units")->fetch_assoc()['c'];
                    $availableUnits = $conn->query("SELECT COUNT(*) c FROM units WHERE status='available'")->fetch_assoc()['c'];
                    $pendingRes = ($conn->query("SHOW TABLES LIKE 'reservations'"))->num_rows ? ($conn->query("SELECT COUNT(*) c FROM reservations WHERE status='pending'"))->fetch_assoc()['c'] : 0;
                    $openMaint = ($conn->query("SHOW TABLES LIKE 'maintenance_requests'"))->num_rows ? ($conn->query("SELECT COUNT(*) c FROM maintenance_requests WHERE status IN ('pending','in_progress')"))->fetch_assoc()['c'] : 0;
                    ?>
                    <div class="stat-card"><div class="stat-icon blue"><i data-lucide="building-2"></i></div><div class="stat-info"><h3>UNITS</h3><div class="value"><?php echo $totalUnits; ?></div><div class="change">Total</div></div></div>
                    <div class="stat-card"><div class="stat-icon green"><i data-lucide="check-circle"></i></div><div class="stat-info"><h3>AVAILABLE</h3><div class="value"><?php echo $availableUnits; ?></div><div class="change">Ready</div></div></div>
                    <div class="stat-card"><div class="stat-icon orange"><i data-lucide="calendar-clock"></i></div><div class="stat-info"><h3>PENDING RES</h3><div class="value"><?php echo $pendingRes; ?></div><div class="change">Awaiting</div></div></div>
                    <div class="stat-card"><div class="stat-icon purple"><i data-lucide="wrench"></i></div><div class="stat-info"><h3>OPEN MAINT</h3><div class="value"><?php echo $openMaint; ?></div><div class="change">Workload</div></div></div>
                </div>

                <div class="main-grid" style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <h3>Reservations - Approvals</h3>
                                <a href="customer.php" class="view-all">Browse Units</a>
                            </div>
                            <div style="padding:0; overflow-x:auto;">
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
                                            $res = $conn->query("SELECT r.*, u.unit_number, c.name AS customer_name FROM reservations r LEFT JOIN units u ON r.unit_id=u.id LEFT JOIN customers c ON r.customer_id=c.id ORDER BY r.created_at DESC LIMIT 10");
                                            if ($res && $res->num_rows) {
                                                while($row=$res->fetch_assoc()) {
                                                    $statusClass = strtolower($row['status']);
                                                    $moveIn = $row['move_in_date'] ? formatDate($row['move_in_date']) : 'N/A';
                                                    echo '<tr>';
                                                    echo '<td>'.htmlspecialchars($row['customer_name'] ?? 'N/A').'</td>';
                                                    echo '<td>Unit '.htmlspecialchars($row['unit_number'] ?? '—').'</td>';
                                                    echo '<td>'.$moveIn.'</td>';
                                                    echo '<td><span class="status-badge '.$statusClass.'">'.ucfirst($row['status']).'</span></td>';
                                                    echo '<td>';
                                                    echo '<form method="post" style="display:inline-block; margin-right:4px;"><input type="hidden" name="reservation_id" value="'.(int)$row['id'].'"><button class="action-icon-btn" name="approve_reservation" value="1" title="Approve"><i data-lucide="check" width="16"></i></button></form>';
                                                    echo '<form method="post" style="display:inline-block;"><input type="hidden" name="reservation_id" value="'.(int)$row['id'].'"><button class="action-icon-btn" name="decline_reservation" value="1" title="Decline"><i data-lucide="x" width="16"></i></button></form>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else { echo "<tr><td colspan='5'>No reservations found</td></tr>"; }
                                        } else { echo "<tr><td colspan='5'>Reservations table not found</td></tr>"; }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header"><h3>Maintenance - Update Status</h3></div>
                            <div style="padding:0; overflow-x:auto;">
                                <table class="tenants-table">
                                    <thead>
                                        <tr>
                                            <th>Issue</th>
                                            <th>Unit</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $hasMr = ($conn->query("SHOW TABLES LIKE 'maintenance_requests'"))->num_rows;
                                        if ($hasMr) {
                                            $mr = $conn->query("SELECT m.*, u.unit_number FROM maintenance_requests m LEFT JOIN units u ON m.unit_id=u.id ORDER BY FIELD(m.status,'pending','in_progress','fixed'), m.created_at DESC LIMIT 10");
                                            if ($mr && $mr->num_rows) {
                                                while($row=$mr->fetch_assoc()) {
                                                    $statusClass = strtolower($row['status']);
                                                    echo '<tr>';
                                                    $issueText = isset($row['issue']) ? $row['issue'] : ($row['description'] ?? '');
                                                    echo '<td>'.htmlspecialchars($issueText).'</td>';
                                                    echo '<td>Unit '.htmlspecialchars($row['unit_number'] ?? '—').'</td>';
                                                    echo '<td>'.htmlspecialchars($row['priority'] ?? 'normal').'</td>';
                                                    echo '<td><span class="status-badge '.$statusClass.'">'.ucfirst($row['status']).'</span></td>';
                                                    echo '<td>';
                                                    echo '<form method="post" style="display:flex; gap:6px; align-items:center;">';
                                                    echo '<input type="hidden" name="request_id" value="'.(int)$row['id'].'">';
                                                    echo '<select name="new_status" class="form-input" style="padding:0.25rem 0.5rem;">';
                                                    echo '<option value="pending"'.($row['status']=='pending'?' selected':'').'>Pending</option>';
                                                    echo '<option value="in_progress"'.($row['status']=='in_progress'?' selected':'').'>In Progress</option>';
                                                    echo '<option value="fixed"'.($row['status']=='fixed'?' selected':'').'>Fixed</option>';
                                                    echo '</select>';
                                                    echo '<button class="btn-primary" name="update_request" value="1" style="padding:0.4rem 0.75rem;">Update</button>';
                                                    echo '</form>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else { echo "<tr><td colspan='5'>No maintenance requests</td></tr>"; }
                                        } else { echo "<tr><td colspan='5'>Maintenance table not found</td></tr>"; }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card">
                            <div class="card-header"><h3>Recent Customer Payments</h3></div>
                            <div style="padding:0; overflow-x:auto;">
                                <table class="tenants-table">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $hasCP = ($conn->query("SHOW TABLES LIKE 'customer_payments'"))->num_rows;
                                        if ($hasCP) {
                                            $cp = $conn->query("SELECT p.*, c.name AS customer_name FROM customer_payments p LEFT JOIN customers c ON p.customer_id=c.id ORDER BY p.created_at DESC LIMIT 8");
                                            if ($cp && $cp->num_rows) {
                                                while($row=$cp->fetch_assoc()) {
                                                    $statusClass = strtolower($row['status'] ?? 'paid');
                                                    $amount = isset($row['amount']) ? formatCurrency($row['amount']) : '—';
                                                    $date = isset($row['created_at']) ? formatDate($row['created_at']) : '—';
                                                    echo '<tr>';
                                                    echo '<td>'.htmlspecialchars($row['customer_name'] ?? 'N/A').'</td>';
                                                    echo '<td>'.$amount.'</td>';
                                                    echo '<td>'.htmlspecialchars($row['method'] ?? '—').'</td>';
                                                    echo '<td>'.$date.'</td>';
                                                    echo '<td><span class="status-badge '.$statusClass.'">'.ucfirst($row['status'] ?? 'paid').'</span></td>';
                                                    echo '</tr>';
                                                }
                                            } else { echo "<tr><td colspan='5'>No customer payments found</td></tr>"; }
                                        } else { echo "<tr><td colspan='5'>Customer payments table not found</td></tr>"; }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header"><h3>Latest Reviews & Feedback</h3></div>
                            <div style="padding:0; overflow-x:auto;">
                                <table class="tenants-table">
                                    <thead>
                                        <tr>
                                            <th>Unit</th>
                                            <th>Customer</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $hasRev = ($conn->query("SHOW TABLES LIKE 'reviews'"))->num_rows;
                                        if ($hasRev) {
                                            $rv = $conn->query("SELECT r.*, u.unit_number, c.name AS customer_name FROM reviews r LEFT JOIN units u ON r.unit_id=u.id LEFT JOIN customers c ON r.customer_id=c.id ORDER BY r.created_at DESC LIMIT 8");
                                            if ($rv && $rv->num_rows) {
                                                while($row=$rv->fetch_assoc()) {
                                                    echo '<tr>';
                                                    echo '<td>Unit '.htmlspecialchars($row['unit_number'] ?? '—').'</td>';
                                                    echo '<td>'.htmlspecialchars($row['customer_name'] ?? 'N/A').'</td>';
                                                    echo '<td>'.htmlspecialchars($row['rating'] ?? '—').'</td>';
                                                    echo '<td>'.htmlspecialchars($row['comment'] ?? '').'</td>';
                                                    echo '</tr>';
                                                }
                                            } else { echo "<tr><td colspan='4'>No reviews yet</td></tr>"; }
                                        } else { echo "<tr><td colspan='4'>Reviews table not found</td></tr>"; }
                                        ?>
                                    </tbody>
                                </table>
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
    </script>
</body>
</html>


