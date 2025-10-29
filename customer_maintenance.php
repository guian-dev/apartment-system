<?php
include 'auth.php';
requireLogin();

include 'db.php';

$customerId = getCustomerId();
$error = '';
$success = '';

// Get customer's tenant info
$tenant = $conn->prepare("SELECT t.*, u.unit_number FROM tenants t JOIN units u ON t.unit_id = u.id WHERE t.customer_id = ? AND t.status = 'active'");
$tenant->bind_param("i", $customerId);
$tenant->execute();
$tenantResult = $tenant->get_result();
$tenantData = $tenantResult->fetch_assoc();

if (!$tenantData) {
    $error = 'You must be an active tenant to submit maintenance requests.';
    $canSubmit = false;
} else {
    $canSubmit = true;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $priority = $_POST['priority'] ?? 'medium';
        
        if (empty($title) || empty($description)) {
            $error = 'Please fill all required fields.';
        } else {
            $stmt = $conn->prepare("INSERT INTO maintenance_requests (tenant_id, unit_id, title, description, priority, requested_date, status) VALUES (?, ?, ?, ?, ?, CURDATE(), 'pending')");
            $stmt->bind_param("iisss", $tenantData['id'], $tenantData['unit_id'], $title, $description, $priority);
            
            if ($stmt->execute()) {
                $success = 'Maintenance request submitted successfully!';
                sendNotification($customerId, 'maintenance_update', 'Maintenance Request Submitted', "Your request '{$title}' has been received and is being processed.", 'customer_maintenance.php');
            } else {
                $error = 'Failed to submit request. Please try again.';
            }
        }
    }
}

// Get all maintenance requests
$requests = [];
if ($tenantData) {
    $allRequests = $conn->prepare("
        SELECT mr.*, u.unit_number, s.name as assigned_staff
        FROM maintenance_requests mr
        JOIN units u ON mr.unit_id = u.id
        LEFT JOIN staff s ON mr.assigned_staff_id = s.id
        WHERE mr.tenant_id = ?
        ORDER BY mr.created_at DESC
    ");
    $allRequests->bind_param("i", $tenantData['id']);
    $allRequests->execute();
    $allRequestsResult = $allRequests->get_result();
    while ($r = $allRequestsResult->fetch_assoc()) {
        $requests[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Requests - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .maintenance-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .maintenance-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .request-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }
        
        .request-item:last-child {
            border-bottom: none;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
        }
        
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-pending { background: var(--warning-light); color: var(--warning-color); }
        .status-in-progress { background: var(--info-light); color: var(--info-color); }
        .status-completed { background: var(--success-light); color: var(--success-color); }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <a href="customer_dashboard.php" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-color); text-decoration: none; margin-bottom: 1rem;">
            <i data-lucide="arrow-left" width="20"></i>
            Back to Dashboard
        </a>
        
        <?php if ($canSubmit): ?>
        <div class="maintenance-card">
            <h1 style="margin-bottom: 1.5rem;">Submit Maintenance Request</h1>
            
            <?php if ($error): ?>
                <div style="padding: 1rem; background: var(--danger-light); color: var(--danger-color); border-radius: var(--radius-lg); margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="padding: 1rem; background: var(--success-light); color: var(--success-color); border-radius: var(--radius-lg); margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Title / Issue</label>
                    <input type="text" name="title" class="form-input" placeholder="e.g., No water, Light bulb busted" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-textarea" rows="5" placeholder="Describe the issue in detail..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Priority</label>
                    <select name="priority" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit">Submit Request</button>
            </form>
        </div>
        <?php else: ?>
            <div class="maintenance-card">
                <div style="text-align: center; padding: 2rem;">
                    <i data-lucide="alert-circle" width="48" height="48" style="color: var(--warning-color); margin-bottom: 1rem;"></i>
                    <h2>Not Available</h2>
                    <p style="color: var(--text-secondary);"><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="maintenance-card">
            <h2 style="margin-bottom: 1.5rem;">My Maintenance Requests</h2>
            <?php if (!empty($requests)): ?>
                <?php foreach ($requests as $req): ?>
                    <div class="request-item">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div>
                                <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($req['title']); ?></h3>
                                <p style="color: var(--text-secondary); line-height: 1.6;"><?php echo nl2br(htmlspecialchars($req['description'])); ?></p>
                            </div>
                            <span class="status-badge status-<?php echo str_replace('_', '-', $req['status']); ?>"><?php echo $req['status']; ?></span>
                        </div>
                        <div style="display: flex; gap: 2rem; margin-top: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                            <span><strong>Priority:</strong> <?php echo ucfirst($req['priority']); ?></span>
                            <span><strong>Requested:</strong> <?php echo formatDate($req['requested_date']); ?></span>
                            <?php if ($req['assigned_staff']): ?>
                                <span><strong>Assigned to:</strong> <?php echo htmlspecialchars($req['assigned_staff']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No maintenance requests yet.</p>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

