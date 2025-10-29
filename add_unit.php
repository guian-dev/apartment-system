<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Unit - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
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
                <button class="toggle-btn" onclick="document.getElementById('sidebar').classList.toggle('collapsed')">
                    <i data-lucide="menu" width="20" height="20"></i>
                </button>
            </div>

            <nav class="nav-menu">
                <a href="main.php" class="nav-item">
                    <i data-lucide="layout-dashboard" width="20" height="20"></i>
                    <span>Dashboard</span>
                </a>
                <a href="units.php" class="nav-item active">
                    <i data-lucide="building-2" width="20" height="20"></i>
                    <span>Units</span>
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
                    <h2>Add New Unit</h2>
                    <p>Create a new unit. New units marked as "available" will appear on the customer page automatically.</p>
                </div>
            </header>

            <div class="content-area">
                <?php
                $error = '';
                $success = '';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $unit_number = trim($_POST['unit_number'] ?? '');
                    $bedrooms = (int)($_POST['bedrooms'] ?? 0);
                    $bathrooms = (int)($_POST['bathrooms'] ?? 0);
                    $area_sqm = $_POST['area_sqm'] !== '' ? (float)$_POST['area_sqm'] : null;
                    $monthly_rent = (float)($_POST['monthly_rent'] ?? 0);
                    $location = trim($_POST['location'] ?? '');
                    $description = trim($_POST['description'] ?? '');
                    $status = $_POST['status'] ?? 'available';
                    $amenities = trim($_POST['amenities'] ?? ''); // comma-separated

                    if ($unit_number === '' || $monthly_rent <= 0) {
                        $error = 'Unit number and a positive monthly rent are required.';
                    } else {
                        try {
                            $stmt = $conn->prepare("INSERT INTO units (unit_number, bedrooms, bathrooms, area_sqm, monthly_rent, location, description, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param('siiddsss', $unit_number, $bedrooms, $bathrooms, $area_sqm, $monthly_rent, $location, $description, $status);
                            if ($stmt->execute()) {
                                $unitId = $stmt->insert_id;
                                // Insert amenities if table exists and provided
                                $tableCheck = $conn->query("SHOW TABLES LIKE 'unit_amenities'");
                                if ($tableCheck && $tableCheck->num_rows > 0 && $amenities !== '') {
                                    $amenityList = array_filter(array_map('trim', explode(',', $amenities)));
                                    if (!empty($amenityList)) {
                                        $amenStmt = $conn->prepare("INSERT INTO unit_amenities (unit_id, amenity) VALUES (?, ?)");
                                        foreach ($amenityList as $am) {
                                            $amSafe = substr($am, 0, 100);
                                            $amenStmt->bind_param('is', $unitId, $amSafe);
                                            $amenStmt->execute();
                                        }
                                    }
                                }
                                $success = 'Unit added successfully!';
                                // Redirect back to units list after short delay
                                echo '<script>setTimeout(()=>{ window.location.href = "units.php"; }, 800);</script>';
                            } else {
                                $error = 'Failed to add unit.';
                            }
                        } catch (Throwable $e) {
                            $error = 'Error: ' . $e->getMessage();
                        }
                    }
                }
                ?>

                <?php if ($error): ?>
                    <div class="card" style="border-left: 4px solid #ef4444;">
                        <div class="card-header"><h3 style="color:#ef4444;">Error</h3></div>
                        <div style="padding:1rem; color:#991b1b;"><?php echo htmlspecialchars($error); ?></div>
                    </div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="card" style="border-left: 4px solid #10b981;">
                        <div class="card-header"><h3 style="color:#059669;">Success</h3></div>
                        <div style="padding:1rem; color:#065f46;"><?php echo htmlspecialchars($success); ?></div>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header">
                        <h3>Unit Details</h3>
                        <a href="units.php" class="view-all">Back to Units</a>
                    </div>
                    <form method="post" style="padding:1.5rem; display:grid; gap:1rem; grid-template-columns: 1fr 1fr;">
                        <div>
                            <label class="form-label">Unit Number</label>
                            <input type="text" name="unit_number" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Monthly Rent (PHP)</label>
                            <input type="number" name="monthly_rent" class="form-input" step="0.01" min="0" required>
                        </div>
                        <div>
                            <label class="form-label">Bedrooms</label>
                            <input type="number" name="bedrooms" class="form-input" min="0" value="0">
                        </div>
                        <div>
                            <label class="form-label">Bathrooms</label>
                            <input type="number" name="bathrooms" class="form-input" min="0" value="1">
                        </div>
                        <div>
                            <label class="form-label">Area (sqm)</label>
                            <input type="number" name="area_sqm" class="form-input" step="0.1" min="0">
                        </div>
                        <div>
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-input" placeholder="e.g. Cagayan de Oro, City Center">
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-input" rows="3" placeholder="Short description shown on customer page"></textarea>
                        </div>
                        <div>
                            <label class="form-label">Status</label>
                            <select name="status" class="form-input">
                                <option value="available">Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Amenities (comma-separated)</label>
                            <input type="text" name="amenities" class="form-input" placeholder="Wi-Fi, Private Bathroom, Aircon">
                        </div>
                        <div style="grid-column: 1 / -1; display:flex; gap:0.75rem; justify-content:flex-end;">
                            <a href="units.php" class="btn-secondary" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;">
                                <i data-lucide="x"></i> Cancel
                            </a>
                            <button type="submit" class="btn-primary" style="display:inline-flex; align-items:center; gap:0.5rem;">
                                <i data-lucide="save"></i> Save Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>


