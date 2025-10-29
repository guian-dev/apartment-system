<?php
include 'auth.php';
requireLogin();

include 'db.php';

$unitId = $_GET['unit_id'] ?? 0;
$customerId = getCustomerId();
$error = '';
$success = '';

// Get unit details
$stmt = $conn->prepare("SELECT * FROM units WHERE id = ? AND status = 'available'");
$stmt->bind_param("i", $unitId);
$stmt->execute();
$result = $stmt->get_result();
$unit = $result->fetch_assoc();

if (!$unit) {
    header('Location: customer.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moveInDate = $_POST['move_in_date'] ?? '';
    $specialRequests = trim($_POST['special_requests'] ?? '');
    
    if (empty($moveInDate)) {
        $error = 'Please select a move-in date.';
    } else {
        // Check if unit is still available
        $checkStmt = $conn->prepare("SELECT status FROM units WHERE id = ?");
        $checkStmt->bind_param("i", $unitId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $unitStatus = $checkResult->fetch_assoc();
        
        if ($unitStatus['status'] !== 'available') {
            $error = 'This unit is no longer available.';
        } else {
            // Create reservation
            $stmt = $conn->prepare("INSERT INTO reservations (customer_id, unit_id, reservation_date, move_in_date, special_requests, status) VALUES (?, ?, CURDATE(), ?, ?, 'pending')");
            $stmt->bind_param("iiss", $customerId, $unitId, $moveInDate, $specialRequests);
            
            if ($stmt->execute()) {
                $reservationId = $conn->insert_id;
                
                // Send notification
                sendNotification($customerId, 'reservation_approved', 'Reservation Submitted', "Your reservation for Unit {$unit['unit_number']} has been submitted and is pending approval.", "customer_dashboard.php");
                
                // If automatic confirmation is enabled (or make it manual)
                // $updateStmt = $conn->prepare("UPDATE reservations SET status = 'confirmed', confirmation_method = 'automatic' WHERE id = ?");
                // $updateStmt->bind_param("i", $reservationId);
                // $updateStmt->execute();
                
                $success = 'Reservation submitted successfully! You will be notified once it is approved.';
                header('refresh:2;url=customer_dashboard.php');
            } else {
                $error = 'Failed to submit reservation. Please try again.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Unit - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .booking-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .booking-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .unit-summary {
            display: flex;
            gap: 1.5rem;
            padding: 1.5rem;
            background: var(--light-color);
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-input, .form-textarea {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
        }
        
        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
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
        
        .alert {
            padding: 1rem;
            border-radius: var(--radius-lg);
            margin-bottom: 1.5rem;
        }
        
        .alert-error {
            background: var(--danger-light);
            color: var(--danger-color);
        }
        
        .alert-success {
            background: var(--success-light);
            color: var(--success-color);
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <div class="booking-card">
            <h1 style="margin-bottom: 1rem;">Reserve Unit <?php echo htmlspecialchars($unit['unit_number']); ?></h1>
            
            <div class="unit-summary">
                <div style="flex: 1;">
                    <h3 style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($unit['unit_number']); ?></h3>
                    <p style="color: var(--text-secondary); margin-bottom: 0.5rem;"><?php echo $unit['bedrooms']; ?> Bedrooms, <?php echo $unit['bathrooms']; ?> Bathrooms</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: var(--success-color);"><?php echo formatCurrency($unit['monthly_rent']); ?>/month</p>
                </div>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Move-in Date</label>
                    <input type="date" name="move_in_date" id="moveInDate" class="form-input" required min="<?php echo date('Y-m-d'); ?>">
                    <small style="color: var(--text-secondary); margin-top: 0.5rem; display: block;">Select your preferred move-in date. Unit availability will be checked.</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Special Requests (Optional)</label>
                    <textarea name="special_requests" class="form-textarea" rows="4" placeholder="Any special requests or questions..."></textarea>
                </div>
                
                <div style="background: var(--light-color); padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Reservation Summary</h3>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span>Monthly Rent:</span>
                        <strong><?php echo formatCurrency($unit['monthly_rent']); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Deposit Required:</span>
                        <strong><?php echo formatCurrency($unit['deposit_amount'] ?? $unit['monthly_rent']); ?></strong>
                    </div>
                </div>
                
                <button type="submit" class="btn-submit">Submit Reservation</button>
            </form>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
        
        // Calendar view for availability (simple implementation)
        const dateInput = document.getElementById('moveInDate');
        if (dateInput) {
            // Highlight weekends (optional)
            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const today = new Date();
                if (selectedDate < today) {
                    alert('Please select a future date');
                    this.value = '';
                }
            });
        }
    </script>
</body>
</html>

