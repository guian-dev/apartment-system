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
    header('Location: customer_dashboard.php');
    exit();
}

// Get pending payments
$pendingPayments = $conn->prepare("
    SELECT * FROM payments 
    WHERE tenant_id = ? AND status IN ('pending', 'overdue')
    ORDER BY due_date ASC
");
$pendingPayments->bind_param("i", $tenantData['id']);
$pendingPayments->execute();
$pendingPaymentsResult = $pendingPayments->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentId = $_POST['payment_id'] ?? 0;
    $amount = $_POST['amount'] ?? 0;
    $paymentMethod = $_POST['payment_method'] ?? '';
    $transactionId = trim($_POST['transaction_id'] ?? '');
    
    if (empty($paymentMethod) || $amount <= 0) {
        $error = 'Please fill all required fields.';
    } else {
        // Record payment
        $stmt = $conn->prepare("INSERT INTO customer_payments (customer_id, amount, payment_method, payment_date, transaction_id, status) VALUES (?, ?, ?, NOW(), ?, 'pending')");
        $stmt->bind_param("idss", $customerId, $amount, $paymentMethod, $transactionId);
        
        if ($stmt->execute()) {
            $success = 'Payment submitted successfully! It will be verified and processed.';
            // In real scenario, update the payment record status
            // $updateStmt = $conn->prepare("UPDATE payments SET status = 'paid' WHERE id = ?");
            // $updateStmt->bind_param("i", $paymentId);
            // $updateStmt->execute();
        } else {
            $error = 'Payment submission failed. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .payment-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin: 1.5rem 0;
        }
        
        .payment-method {
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            cursor: pointer;
            text-align: center;
            transition: all 0.2s ease;
        }
        
        .payment-method:hover {
            border-color: var(--primary-color);
            background: var(--light-color);
        }
        
        .payment-method.selected {
            border-color: var(--primary-color);
            background: rgba(79, 70, 229, 0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
        }
        
        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <a href="customer_dashboard.php" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--primary-color); text-decoration: none; margin-bottom: 1rem;">
            <i data-lucide="arrow-left" width="20"></i>
            Back to Dashboard
        </a>
        
        <div class="payment-card">
            <h1 style="margin-bottom: 1rem;">Make Payment</h1>
            
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
            
            <h3 style="margin-bottom: 1rem;">Pending Payments</h3>
            <?php while ($pay = $pendingPaymentsResult->fetch_assoc()): ?>
                <div style="padding: 1rem; background: var(--light-color); border-radius: var(--radius-lg); margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; font-size: 1.25rem;"><?php echo formatCurrency($pay['amount']); ?></div>
                            <div style="color: var(--text-secondary); font-size: 0.875rem;">Due: <?php echo formatDate($pay['due_date']); ?></div>
                        </div>
                        <span class="status-badge status-<?php echo $pay['status']; ?>"><?php echo $pay['status']; ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <div style="margin-top: 2rem; margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">Payment History</h3>
                <?php
                // Get payment history
                $paymentHistoryCheck = $conn->query("SHOW TABLES LIKE 'customer_payments'");
                if ($paymentHistoryCheck && $paymentHistoryCheck->num_rows > 0) {
                    $history = $conn->prepare("SELECT * FROM customer_payments WHERE customer_id = ? ORDER BY payment_date DESC LIMIT 10");
                    $history->bind_param("i", $customerId);
                    $history->execute();
                    $historyResult = $history->get_result();
                    
                    if ($historyResult->num_rows > 0): ?>
                        <div style="border: 1px solid var(--border-light); border-radius: var(--radius-lg); overflow: hidden;">
                            <?php while ($hist = $historyResult->fetch_assoc()): ?>
                                <div style="padding: 1rem; border-bottom: 1px solid var(--border-light); display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <div style="font-weight: 600;"><?php echo formatCurrency($hist['amount']); ?></div>
                                        <div style="font-size: 0.875rem; color: var(--text-secondary);">
                                            <?php echo ucfirst(str_replace('_', ' ', $hist['payment_method'])); ?> | 
                                            <?php echo date('M j, Y', strtotime($hist['payment_date'])); ?>
                                        </div>
                                        <?php if ($hist['transaction_id']): ?>
                                            <div style="font-size: 0.75rem; color: var(--text-muted);">Ref: <?php echo htmlspecialchars($hist['transaction_id']); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="status-badge status-<?php echo $hist['status']; ?>"><?php echo ucfirst($hist['status']); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No payment history yet.</p>
                    <?php endif;
                }
                ?>
            </div>
            
            <form method="POST" style="margin-top: 2rem;">
                <input type="hidden" name="payment_id" value="<?php echo $pay['id'] ?? ''; ?>">
                
                <div class="form-group">
                    <label class="form-label">Amount (₱)</label>
                    <input type="number" name="amount" class="form-input" step="0.01" required min="0.01">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Payment Method</label>
                    <div class="payment-methods">
                        <div class="payment-method" onclick="selectMethod('gcash')">
                            <input type="radio" name="payment_method" value="gcash" id="gcash" style="display: none;">
                            <label for="gcash" style="cursor: pointer;">
                                <div style="font-size: 2rem;">📱</div>
                                <div style="font-weight: 600;">GCash</div>
                            </label>
                        </div>
                        <div class="payment-method" onclick="selectMethod('paymaya')">
                            <input type="radio" name="payment_method" value="paymaya" id="paymaya" style="display: none;">
                            <label for="paymaya" style="cursor: pointer;">
                                <div style="font-size: 2rem;">💳</div>
                                <div style="font-weight: 600;">PayMaya</div>
                            </label>
                        </div>
                        <div class="payment-method" onclick="selectMethod('bank_transfer')">
                            <input type="radio" name="payment_method" value="bank_transfer" id="bank_transfer" style="display: none;">
                            <label for="bank_transfer" style="cursor: pointer;">
                                <div style="font-size: 2rem;">🏦</div>
                                <div style="font-weight: 600;">Bank Transfer</div>
                            </label>
                        </div>
                        <div class="payment-method" onclick="selectMethod('credit_card')">
                            <input type="radio" name="payment_method" value="credit_card" id="credit_card" style="display: none;">
                            <label for="credit_card" style="cursor: pointer;">
                                <div style="font-size: 2rem;">💳</div>
                                <div style="font-weight: 600;">Credit Card</div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Transaction ID / Reference Number</label>
                    <input type="text" name="transaction_id" class="form-input" placeholder="Enter transaction ID or reference number" required>
                </div>
                
                <button type="submit" class="btn-submit">Submit Payment</button>
            </form>
        </div>
    </div>
    
    <script>
        lucide.createIcons();
        
        function selectMethod(method) {
            document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
            event.currentTarget.classList.add('selected');
            document.getElementById(method).checked = true;
        }
    </script>
</body>
</html>

