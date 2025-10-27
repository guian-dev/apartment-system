<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Kagay an View</title>
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest/dist/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideIn 0.3s;
        }

        .modal-header {
            padding: 24px 28px;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px 12px 0 0;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.75rem;
            color: #ffffff;
            font-weight: 600;
        }

        .close {
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(255,255,255,0.2);
            border: none;
            padding: 0;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .close:hover,
        .close:focus {
            background: rgba(255,255,255,0.3);
            transform: scale(1.1);
        }

        .modal-body {
            padding: 28px;
            background-color: #f9fafb;
        }

        .form-section {
            background: white;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
        }

        .form-group label .required {
            color: #ef4444;
            margin-left: 2px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .modal-footer {
            padding: 20px 28px;
            border-top: 2px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background-color: #f9fafb;
            border-radius: 0 0 12px 12px;
        }

        .btn-secondary {
            padding: 12px 24px;
            background-color: #e5e7eb;
            color: #374151;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
            transform: translateY(-1px);
        }

        .btn-submit {
            padding: 12px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-weight: 500;
        }

        .alert.show {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 2px solid #6ee7b7;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #fca5a5;
        }

        .input-hint {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 4px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            
            .modal-content {
                width: 95%;
                margin: 20px 0;
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
                    $totalStaffResult = $conn->query("SELECT COUNT(*) as count FROM staff");
                    $totalStaff = ($totalStaffResult && $totalStaffResult->num_rows > 0) ? $totalStaffResult->fetch_assoc()['count'] : 0;
                    
                    $activeStaffResult = $conn->query("SELECT COUNT(*) as count FROM staff WHERE status = 'active'");
                    $activeStaff = ($activeStaffResult && $activeStaffResult->num_rows > 0) ? $activeStaffResult->fetch_assoc()['count'] : 0;
                    
                    $inactiveStaffResult = $conn->query("SELECT COUNT(*) as count FROM staff WHERE status = 'inactive'");
                    $inactiveStaff = ($inactiveStaffResult && $inactiveStaffResult->num_rows > 0) ? $inactiveStaffResult->fetch_assoc()['count'] : 0;
                    
                    $newThisMonthResult = $conn->query("SELECT COUNT(*) as count FROM staff WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
                    $newThisMonth = ($newThisMonthResult && $newThisMonthResult->num_rows > 0) ? $newThisMonthResult->fetch_assoc()['count'] : 0;
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

    <!-- Add Staff Modal -->
    <div id="addStaffModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Staff Member</h2>
                <button class="close" onclick="closeAddStaffForm()">&times;</button>
            </div>
            <form id="addStaffForm" onsubmit="submitStaffForm(event)">
                <div class="modal-body">
                    <div id="alertMessage" class="alert"></div>
                    
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">Personal Information</div>
                        
                        <div class="form-group">
                            <label for="name">Full Name <span class="required">*</span></label>
                            <input type="text" id="name" name="name" required placeholder="Enter full name">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address <span class="required">*</span></label>
                                <input type="email" id="email" name="email" required placeholder="email@example.com">
                                <div class="input-hint">Used for login and communication</div>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number <span class="required">*</span></label>
                                <input type="tel" id="phone" name="phone" required placeholder="09XX XXX XXXX">
                                <div class="input-hint">Format: 09XX XXX XXXX</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address">Complete Address</label>
                            <textarea id="address" name="address" placeholder="Street, Barangay, City, Province"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth">
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">Employment Information</div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="position">Position/Role <span class="required">*</span></label>
                                <select id="position" name="position" required>
                                    <option value="">Select Position</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Security Guard">Security Guard</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Janitor">Janitor</option>
                                    <option value="Receptionist">Receptionist</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="department">Department</label>
                                <select id="department" name="department">
                                    <option value="">Select Department</option>
                                    <option value="Administration">Administration</option>
                                    <option value="Security">Security</option>
                                    <option value="Maintenance">Maintenance</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Operations">Operations</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="hire_date">Hire Date <span class="required">*</span></label>
                                <input type="date" id="hire_date" name="hire_date" required>
                            </div>
                            <div class="form-group">
                                <label for="employment_type">Employment Type</label>
                                <select id="employment_type" name="employment_type">
                                    <option value="full-time">Full-Time</option>
                                    <option value="part-time">Part-Time</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="salary">Monthly Salary (₱)</label>
                                <input type="number" id="salary" name="salary" placeholder="0.00" step="0.01" min="0">
                                <div class="input-hint">Leave blank if not applicable</div>
                            </div>
                            <div class="form-group">
                                <label for="status">Status <span class="required">*</span></label>
                                <select id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="on-leave">On Leave</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="form-section">
                        <div class="form-section-title">Additional Information</div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="emergency_contact_name">Emergency Contact Name</label>
                                <input type="text" id="emergency_contact_name" name="emergency_contact_name" placeholder="Contact person name">
                            </div>
                            <div class="form-group">
                                <label for="emergency_contact_phone">Emergency Contact Phone</label>
                                <input type="tel" id="emergency_contact_phone" name="emergency_contact_phone" placeholder="09XX XXX XXXX">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes/Comments</label>
                            <textarea id="notes" name="notes" placeholder="Any additional information or comments"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeAddStaffForm()">Cancel</button>
                    <button type="submit" class="btn-submit">Add Staff Member</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function showAddStaffForm() {
            const modal = document.getElementById('addStaffModal');
            modal.classList.add('show');
            document.getElementById('addStaffForm').reset();
            hideAlert();
            // Set default hire date to today
            document.getElementById('hire_date').valueAsDate = new Date();
        }

        function closeAddStaffForm() {
            const modal = document.getElementById('addStaffModal');
            modal.classList.remove('show');
            document.getElementById('addStaffForm').reset();
            hideAlert();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('addStaffModal');
            if (event.target == modal) {
                closeAddStaffForm();
            }
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAddStaffForm();
            }
        });

        function showAlert(message, type) {
            const alert = document.getElementById('alertMessage');
            alert.textContent = message;
            alert.className = 'alert alert-' + type + ' show';
            
            // Auto-hide success messages after 3 seconds
            if (type === 'success') {
                setTimeout(() => {
                    hideAlert();
                }, 3000);
            }
        }

        function hideAlert() {
            const alert = document.getElementById('alertMessage');
            alert.className = 'alert';
        }

        function submitStaffForm(event) {
            event.preventDefault();
            
            const formData = new FormData(event.target);
            const submitBtn = event.target.querySelector('.btn-submit');
            
            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';
            
            fetch('add_staff.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Staff member added successfully!', 'success');
                    setTimeout(() => {
                        closeAddStaffForm();
                        location.reload();
                    }, 1500);
                } else {
                    showAlert('Error: ' + data.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Add Staff Member';
                }
            })
            .catch(error => {
                showAlert('Error adding staff member. Please try again.', 'error');
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add Staff Member';
            });
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