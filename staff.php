<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Kagay an View</title>
    <script src="enhanced-ui.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
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
                <a href="staff.php" class="nav-item active">
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
                    <h2>Staff Management</h2>
                    <p>Manage your staff members</p>
                </div>
                <div class="header-right">
                    <button class="btn-primary" onclick="showAddStaffForm()">
                        <i data-lucide="user-plus" width="18" height="18"></i>
                        Add New Staff
                    </button>
                </div>
            </header>

            <div class="content-area">
                <!-- Staff Stats Cards -->
                <div class="stats-grid">
                    <?php
                    $totalStaff = $conn->query("SELECT COUNT(*) as count FROM staff")->fetch_assoc()['count'];
                    $activeStaff = $conn->query("SELECT COUNT(*) as count FROM staff WHERE status = 'active'")->fetch_assoc()['count'];
                    $inactiveStaff = $conn->query("SELECT COUNT(*) as count FROM staff WHERE status = 'inactive'")->fetch_assoc()['count'];
                    $newThisMonth = $conn->query("SELECT COUNT(*) as count FROM staff WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())")->fetch_assoc()['count'];
                    ?>
                    <div class="stat-card">
                        <div class="stat-icon blue">
                            <i data-lucide="users" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>TOTAL STAFF</h3>
                            <div class="value"><?php echo $totalStaff; ?></div>
                            <div class="change">All staff members</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon green">
                            <i data-lucide="user-check" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>ACTIVE STAFF</h3>
                            <div class="value"><?php echo $activeStaff; ?></div>
                            <div class="change">Currently working</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon orange">
                            <i data-lucide="user-x" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>INACTIVE STAFF</h3>
                            <div class="value"><?php echo $inactiveStaff; ?></div>
                            <div class="change">Not active</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon purple">
                            <i data-lucide="user-plus" width="28" height="28"></i>
                        </div>
                        <div class="stat-info">
                            <h3>NEW THIS MONTH</h3>
                            <div class="value"><?php echo $newThisMonth; ?></div>
                            <div class="change">Recently added</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Staff Members</h3>
                        <button class="btn-primary" onclick="showAddStaffForm()">
                            <i data-lucide="user-plus" width="16"></i>
                            Add New Staff
                        </button>
                    </div>
                    <table class="staff-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM staff ORDER BY created_at DESC");
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $statusClass = strtolower($row['status']);
                                    echo "<tr>
                                            <td>{$row['name']}</td>
                                            <td>{$row['position']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['phone']}</td>
                                            <td><span class='status-badge {$statusClass}'>{$row['status']}</span></td>
                                            <td>
                                                <button class='action-icon-btn' onclick='editStaff({$row['id']})'>
                                                    <i data-lucide='edit' width='16'></i>
                                                </button>
                                                <button class='action-icon-btn' onclick='deleteStaff({$row['id']})'>
                                                    <i data-lucide='trash-2' width='16'></i>
                                                </button>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No staff found</td></tr>";
                            }
                            ?>
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

        function showAddStaffForm() {
            alert('Add New Staff form would open here.');
        }

        function editStaff(id) {
            window.location.href = 'edit_staff.php?id=' + id;
        }

        function deleteStaff(id) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                fetch('delete_staff.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Staff member deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting staff member: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error deleting staff member');
                });
            }
        }
    </script>
</body>
</html>