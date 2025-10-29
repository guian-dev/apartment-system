<?php
include 'auth.php';
requireLogin();

include 'db.php';

$customerId = getCustomerId();
$customer = getCurrentCustomer();

// Get customer's reservations (check if table exists)
$reservationsResult = null;
$reservationsCheck = $conn->query("SHOW TABLES LIKE 'reservations'");
if ($reservationsCheck && $reservationsCheck->num_rows > 0) {
    try {
        $reservations = $conn->prepare("
            SELECT r.*, u.unit_number, u.monthly_rent, u.floor
            FROM reservations r
            JOIN units u ON r.unit_id = u.id
            WHERE r.customer_id = ?
            ORDER BY r.created_at DESC
        ");
        $reservations->bind_param("i", $customerId);
        $reservations->execute();
        $reservationsResult = $reservations->get_result();
    } catch (Exception $e) {
        $reservationsResult = null;
    }
}

// Get customer's tenant info if they became a tenant
$columnCheck = $conn->query("SHOW COLUMNS FROM tenants LIKE 'customer_id'");
$tenantData = null;

if ($columnCheck && $columnCheck->num_rows > 0) {
    $tenant = $conn->prepare("SELECT t.*, u.unit_number FROM tenants t JOIN units u ON t.unit_id = u.id WHERE t.customer_id = ? AND t.status = 'active'");
    $tenant->bind_param("i", $customerId);
    $tenant->execute();
    $tenantResult = $tenant->get_result();
    $tenantData = $tenantResult->fetch_assoc();
} else {
    $customer = getCurrentCustomer();
    if ($customer && isset($customer['email'])) {
        $tenant = $conn->prepare("SELECT t.*, u.unit_number FROM tenants t JOIN units u ON t.unit_id = u.id WHERE t.email = ? AND t.status = 'active'");
        $tenant->bind_param("s", $customer['email']);
        $tenant->execute();
        $tenantResult = $tenant->get_result();
        $tenantData = $tenantResult->fetch_assoc();
    }
}

// Get upcoming payments
$upcomingPayments = [];
if ($tenantData) {
    $payments = $conn->prepare("
        SELECT * FROM payments 
        WHERE tenant_id = ? AND status IN ('pending', 'overdue')
        ORDER BY due_date ASC
        LIMIT 5
    ");
    $payments->bind_param("i", $tenantData['id']);
    $payments->execute();
    $paymentsResult = $payments->get_result();
    while ($p = $paymentsResult->fetch_assoc()) {
        $upcomingPayments[] = $p;
    }
}

// Get maintenance requests
$maintenanceData = [];
if ($tenantData) {
    $maintenance = $conn->prepare("
        SELECT mr.*, u.unit_number 
        FROM maintenance_requests mr
        JOIN units u ON mr.unit_id = u.id
        WHERE mr.tenant_id = ?
        ORDER BY mr.created_at DESC
        LIMIT 5
    ");
    $maintenance->bind_param("i", $tenantData['id']);
    $maintenance->execute();
    $maintenanceResult = $maintenance->get_result();
    while ($m = $maintenanceResult->fetch_assoc()) {
        $maintenanceData[] = $m;
    }
}

// Get notifications (check if table exists)
$notificationsResult = null;
$unreadCount = 0;
$notificationsCheck = $conn->query("SHOW TABLES LIKE 'customer_notifications'");
if ($notificationsCheck && $notificationsCheck->num_rows > 0) {
    try {
        $notifications = $conn->prepare("
            SELECT * FROM customer_notifications 
            WHERE customer_id = ?
            ORDER BY created_at DESC
            LIMIT 10
        ");
        $notifications->bind_param("i", $customerId);
        $notifications->execute();
        $notificationsResult = $notifications->get_result();
        
        $unreadCount = getUnreadNotificationCount($customerId);
    } catch (Exception $e) {
        $notificationsResult = null;
        $unreadCount = 0;
    }
}

// Get available units for browsing and booking
$unitsTableCheck = $conn->query("SHOW TABLES LIKE 'unit_amenities'");
if ($unitsTableCheck && $unitsTableCheck->num_rows > 0) {
    $availableUnits = $conn->query("
        SELECT u.*, 
               CASE 
                   WHEN u.bedrooms = 0 THEN 'Studio'
                   WHEN u.bedrooms = 1 THEN '1 Bedroom'
                   WHEN u.bedrooms = 2 THEN '2 Bedrooms'
                   ELSE CONCAT(u.bedrooms, '+ Bedrooms')
               END as bedroom_type,
               GROUP_CONCAT(DISTINCT ua.amenity) as amenities
        FROM units u
        LEFT JOIN unit_amenities ua ON u.id = ua.unit_id
        WHERE u.status = 'available'
        GROUP BY u.id
        ORDER BY u.unit_number
        LIMIT 6
    ");
} else {
    $availableUnits = $conn->query("
        SELECT u.*, 
               CASE 
                   WHEN u.bedrooms = 0 THEN 'Studio'
                   WHEN u.bedrooms = 1 THEN '1 Bedroom'
                   WHEN u.bedrooms = 2 THEN '2 Bedrooms'
                   ELSE CONCAT(u.bedrooms, '+ Bedrooms')
               END as bedroom_type,
               NULL as amenities
        FROM units u
        WHERE u.status = 'available'
        ORDER BY u.unit_number
        LIMIT 6
    ");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Kagay an View</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
    <link rel="stylesheet" href="enhanced-ui.css">
    <style>
        /* Customer Dashboard Styles - matching customer.php */
        .customer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }

        .customer-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .customer-header p {
            font-size: 1.125rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .header-actions {
            position: absolute;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 1rem;
        }

        .header-btn {
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-btn:hover {
            background: white;
            color: var(--primary-color);
        }

        .notification-badge {
            position: relative;
        }

        .unread-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .dashboard-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-light);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-light);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header-action {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-header-action:hover {
            text-decoration: underline;
        }

        .info-item {
            padding: 1.25rem;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: var(--radius-lg);
            margin-bottom: 1rem;
            border: 1px solid var(--border-light);
            transition: all 0.2s ease;
        }

        .info-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .info-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
        }

        .info-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--text-primary);
        }

        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-pending { background: var(--warning-light); color: var(--warning-color); }
        .status-confirmed { background: var(--success-light); color: var(--success-color); }
        .status-cancelled { background: var(--danger-light); color: var(--danger-color); }
        .status-in-progress { background: var(--info-light); color: var(--info-color); }
        .status-completed { background: var(--success-light); color: var(--success-color); }
        .status-paid { background: var(--success-light); color: var(--success-color); }
        .status-overdue { background: var(--danger-light); color: var(--danger-color); }

        .info-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .info-detail i {
            color: var(--primary-color);
            width: 16px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
            color: var(--text-muted);
        }

        .empty-state h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .empty-state a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .rental-highlight {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            border: 2px solid var(--success-color);
        }

        .rental-highlight .info-title {
            color: var(--success-color);
            font-size: 1.25rem;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .quick-action-btn {
            padding: 1rem;
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            text-decoration: none;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            font-weight: 600;
        }

        .quick-action-btn:hover {
            border-color: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .quick-action-btn i {
            width: 24px;
            height: 24px;
        }

        /* Available Units Grid Styles */
        .units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1.5rem;
        }

        .unit-card {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .unit-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-xl);
        }

        .unit-image-placeholder {
            height: 120px;
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .unit-available-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Unit Card Content */
        .unit-card-body {
            padding: 1.25rem;
        }

        .unit-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .unit-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
        }

        .unit-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-color);
        }

        .unit-price small {
            font-size: 0.75rem;
        }

        .unit-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .unit-spec {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        .unit-amenities {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .amenity-tag {
            padding: 0.25rem 0.5rem;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 0.5rem;
            font-size: 0.75rem;
            color: var(--primary-color);
            font-weight: 500;
        }

        .unit-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .btn-details {
            flex: 1;
            padding: 0.75rem;
            background: var(--light-color);
            color: var(--text-primary);
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-details:hover {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: white;
        }

        .btn-reserve {
            flex: 1;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-reserve:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .unit-deposit {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-light);
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .view-all-btn {
            text-align: center;
            margin-top: 2rem;
        }

        .view-all-btn a {
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            box-shadow: var(--shadow-md);
            transition: all 0.2s ease;
        }

        .view-all-btn a:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Quick Payment Grid */
        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .payment-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-sm);
        }

        .payment-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .payment-amount {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .payment-due {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .btn-pay-now {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 100%);
            color: white;
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            display: block;
            transition: all 0.2s ease;
        }

        .btn-pay-now:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .quick-payment-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.02) 100%);
            border: 2px solid var(--success-color);
        }

        .notification-date {
            font-size: 0.75rem;
            color: var(--text-muted);
        }






        /* Enhanced Aesthetic Styles */
        .dashboard-container {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .customer-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            position: relative;
            overflow: hidden;
        }

        .customer-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .header-content {
            position: relative;
            z-index: 3;
        }

        .header-content h1 {
            background: linear-gradient(45deg, #ffffff, #f0f9ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
            animation: fadeInUp 0.8s ease-out;
        }

        .header-content p {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-actions {
            animation: fadeInDown 0.8s ease-out 0.4s both;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header-btn {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .header-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .dashboard-section {
            margin-bottom: 4rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .section-title {
            position: relative;
            margin-bottom: 2rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--secondary-color) 50%, #f093fb 100%);
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(99, 102, 241, 0.1) 50%, rgba(240, 147, 251, 0.1) 100%);
            border: 1px solid rgba(79, 70, 229, 0.2);
        }

        .stat-value {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(241, 245, 249, 0.8) 100%);
            border-radius: var(--radius-xl) var(--radius-xl) 0 0;
        }

        .card-title {
            background: linear-gradient(45deg, var(--text-primary), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .info-item {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .info-item:hover::before {
            opacity: 1;
        }

        .rental-highlight {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.05) 100%);
            border: 2px solid var(--success-color);
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.2);
        }

        .rental-highlight .info-title {
            background: linear-gradient(45deg, var(--success-color), #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .status-badge {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .status-pending { 
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.2)); 
            color: #d97706;
            border-color: rgba(245, 158, 11, 0.3);
        }
        .status-confirmed { 
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); 
            color: #059669;
            border-color: rgba(16, 185, 129, 0.3);
        }
        .status-cancelled { 
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); 
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.3);
        }
        .status-in-progress { 
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.2)); 
            color: #2563eb;
            border-color: rgba(59, 130, 246, 0.3);
        }
        .status-completed { 
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); 
            color: #059669;
            border-color: rgba(16, 185, 129, 0.3);
        }
        .status-paid { 
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(5, 150, 105, 0.2)); 
            color: #059669;
            border-color: rgba(16, 185, 129, 0.3);
        }
        .status-overdue { 
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.2)); 
            color: #dc2626;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .unit-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .unit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), #f093fb);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .unit-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .unit-card:hover::before {
            opacity: 1;
        }

        .unit-image-placeholder {
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 50%, #81d4fa 100%);
            position: relative;
            overflow: hidden;
        }

        .unit-image-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.5;
        }

        .unit-available-badge {
            background: linear-gradient(135deg, var(--success-color), #059669);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            backdrop-filter: blur(10px);
        }

        .unit-price {
            background: linear-gradient(45deg, var(--success-color), #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .amenity-tag {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(99, 102, 241, 0.1));
            border: 1px solid rgba(79, 70, 229, 0.2);
            backdrop-filter: blur(10px);
            transition: all 0.2s ease;
        }

        .amenity-tag:hover {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(99, 102, 241, 0.2));
            transform: translateY(-2px);
        }

        .btn-details {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-details:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-reserve {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, #f093fb 100%);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-reserve:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }

        .quick-action-btn {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .quick-action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .quick-action-btn:hover::before {
            left: 100%;
        }

        .quick-action-btn:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.3);
        }

        .payment-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .payment-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
        }

        .btn-pay-now {
            background: linear-gradient(135deg, var(--success-color) 0%, #059669 50%, #047857 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-pay-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .quick-payment-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.02) 100%);
            border: 2px solid var(--success-color);
            box-shadow: 0 8px 32px rgba(16, 185, 129, 0.2);
        }

        .empty-state {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.8) 0%, rgba(241, 245, 249, 0.8) 100%);
            border: 2px dashed rgba(0, 0, 0, 0.1);
            border-radius: var(--radius-xl);
        }

        .empty-state i {
            background: linear-gradient(45deg, var(--text-muted), var(--primary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Priority indicators with enhanced styling */
        .priority-high {
            border-left: 4px solid var(--danger-color);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05) 0%, rgba(220, 38, 38, 0.02) 100%);
        }

        .priority-medium {
            border-left: 4px solid var(--warning-color);
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(217, 119, 6, 0.02) 100%);
        }

        .priority-low {
            border-left: 4px solid var(--success-color);
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.02) 100%);
        }

        /* Responsive enhancements */
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .dashboard-container {
                padding: 1rem 0.5rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }

        /* Loading animation for dynamic content */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        /* Reservations & Units Organization */
        .reservations-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .reservation-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .reservation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, var(--primary-color), var(--secondary-color));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .reservation-card:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .reservation-card:hover::before {
            opacity: 1;
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .reservation-title {
            font-weight: 600;
            font-size: 1.125rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reservation-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .reservation-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .reservation-detail i {
            color: var(--primary-color);
            width: 16px;
        }

        .btn-primary {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            text-decoration: none;
            border-radius: var(--radius-lg);
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        /* Enhanced Units Grid */
        .units-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .unit-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border-radius: var(--radius-xl);
        }

        .unit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), #f093fb);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .unit-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .unit-card:hover::before {
            opacity: 1;
        }

        .unit-image-placeholder {
            height: 140px;
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 50%, #81d4fa 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .unit-image-placeholder::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.3"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.5;
        }

        .unit-available-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.375rem 0.875rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            backdrop-filter: blur(10px);
        }

        .unit-card-body {
            padding: 1.5rem;
        }

        .unit-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .unit-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }

        .unit-price {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--success-color), #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .unit-price small {
            font-size: 0.75rem;
        }

        .unit-specs {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }

        .unit-spec {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.02);
            border-radius: var(--radius-md);
        }

        .unit-spec i {
            color: var(--primary-color);
            width: 16px;
        }

        .unit-amenities {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .amenity-tag {
            padding: 0.375rem 0.75rem;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(99, 102, 241, 0.1));
            border: 1px solid rgba(79, 70, 229, 0.2);
            border-radius: var(--radius-md);
            font-size: 0.75rem;
            color: var(--primary-color);
            font-weight: 500;
            backdrop-filter: blur(10px);
            transition: all 0.2s ease;
        }

        .amenity-tag:hover {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(99, 102, 241, 0.2));
            transform: translateY(-2px);
        }

        .unit-actions {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .btn-details {
            flex: 1;
            padding: 0.875rem;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text-primary);
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            border: 1px solid rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-details:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .btn-reserve {
            flex: 1;
            padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 50%, #f093fb 100%);
            color: white;
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-reserve:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }

        .unit-deposit {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-light);
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-secondary);
            background: rgba(0, 0, 0, 0.02);
            padding: 0.75rem;
            border-radius: var(--radius-md);
        }

        .view-all-btn {
            text-align: center;
            margin-top: 2rem;
        }

        .view-all-btn a {
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }

        .view-all-btn a:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        }

        /* Search Section - matching customer.php */
        .search-section {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            padding: 1.25rem;
            margin-bottom: 2rem;
            border: 1px solid var(--border-light);
        }

        .search-container {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 300px;
            position: relative;
        }

        .search-input input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            outline: none;
            transition: all 0.2s ease;
        }

        .search-input input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .search-input .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-lg);
            background: white;
            color: var(--text-secondary);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }

        .filter-btn.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .units-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .shopee-product-card {
            background: white;
            border-radius: var(--radius-xl);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 1px solid var(--border-light);
        }

        .shopee-product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, #e0f2fe 0%, #b3e5fc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            opacity: 0.7;
        }

        .product-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .product-actions {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .shopee-product-card:hover .product-actions {
            opacity: 1;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(10px);
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: scale(1.1);
        }

        .product-info {
            padding: 1rem;
        }

        .product-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .product-specs {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: var(--text-secondary);
            background: rgba(0, 0, 0, 0.05);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
        }

        .spec-item i {
            color: var(--primary-color);
        }

        .product-amenities {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .amenity-chip {
            padding: 0.25rem 0.5rem;
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary-color);
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 500;
        }

        .product-price {
            margin-bottom: 0.75rem;
        }

        .price-main {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--success-color);
            margin-bottom: 0.25rem;
        }

        .price-period {
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--text-secondary);
        }

        .price-deposit {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .stars {
            display: flex;
            gap: 0.125rem;
            color: #fbbf24;
        }

        .rating-text {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .product-actions-bottom {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view-details {
            flex: 1;
            padding: 0.75rem;
            background: var(--light-color);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .btn-view-details:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-reserve-now {
            flex: 1;
            padding: 0.75rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: var(--radius-lg);
            text-decoration: none;
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        }

        .btn-reserve-now:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
        }

        .load-more-section {
            text-align: center;
            margin-top: 2rem;
        }

        .load-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .load-more-btn:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .empty-marketplace {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-secondary);
        }

        .empty-marketplace i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-marketplace h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        /* Reservations Sidebar */
        .reservations-sidebar {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        .reservation-item {
            background: white;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .reservation-item:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .reservation-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .reservation-title {
            font-weight: 600;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .reservation-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .reservation-detail {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .reservation-detail i {
            color: var(--primary-color);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .units-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .customer-header h1 {
                font-size: 2rem;
            }

            .search-container {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                min-width: auto;
            }

            .filter-buttons {
                justify-content: center;
            }

            .units-grid {
                grid-template-columns: 1fr;
            }

            .unit-details {
                grid-template-columns: 1fr;
            }

            .unit-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Customer Header -->
    <div class="customer-header">
        <div class="header-actions">
            <a href="customer.php" class="header-btn">
                <i data-lucide="home" width="18"></i>
                Browse Units
            </a>
            <a href="customer_notifications.php" class="header-btn notification-badge">
                <i data-lucide="bell" width="18"></i>
                Notifications
                <?php if ($unreadCount > 0): ?>
                    <span class="unread-count"><?php echo $unreadCount; ?></span>
                <?php endif; ?>
            </a>
            <a href="customer_logout.php" class="header-btn">
                <i data-lucide="log-out" width="18"></i>
                Logout
            </a>
        </div>
        <div class="header-content">
            <h1>Welcome Back, <?php echo htmlspecialchars($customer['name']); ?>!</h1>
            <p>Manage your rentals, payments, and reservations all in one place</p>
        </div>
    </div>

    <div class="dashboard-container">
        <!-- Current Status Section -->
        <?php if ($tenantData): ?>
        <div class="dashboard-section">
            <h2 class="section-title">
                <i data-lucide="home" width="28"></i>
                Current Status
            </h2>
            <div class="content-grid">
                <!-- Current Rental -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="home" width="24" height="24"></i>
                            Current Rental
                        </div>
                        <a href="customer_payment.php" class="card-header-action">
                            <i data-lucide="dollar-sign" width="16"></i>
                            Make Payment
                        </a>
                    </div>
                    
                    <div class="info-item rental-highlight">
                        <div class="info-header">
                            <div class="info-title">Unit <?php echo htmlspecialchars($tenantData['unit_number']); ?></div>
                            <span class="status-badge status-confirmed">Active</span>
                        </div>
                        <div class="info-details">
                            <div class="info-detail">
                                <i data-lucide="calendar" width="16"></i>
                                <span>Move-in: <?php echo formatDate($tenantData['move_in_date']); ?></span>
                            </div>
                            <div class="info-detail">
                                <i data-lucide="dollar-sign" width="16"></i>
                                <span><strong>Rent: <?php echo formatCurrency($tenantData['monthly_rent']); ?>/month</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Payments -->
                <?php if (!empty($upcomingPayments)): ?>
                <div class="dashboard-card compact-card">
                    <div class="card-header">
                        <div class="card-title">
                            <i data-lucide="dollar-sign" width="24" height="24"></i>
                            Upcoming Payments
                        </div>
                        <a href="customer_payment.php" class="card-header-action">
                            View All
                            <i data-lucide="arrow-right" width="16"></i>
                        </a>
                    </div>
                    
                    <?php foreach (array_slice($upcomingPayments, 0, 3) as $payment): ?>
                        <div class="info-item priority-<?php echo $payment['status'] === 'overdue' ? 'high' : 'medium'; ?>">
                            <div class="info-header">
                                <div class="info-title"><?php echo formatCurrency($payment['amount']); ?></div>
                                <span class="status-badge status-<?php echo $payment['status']; ?>"><?php echo ucfirst($payment['status']); ?></span>
                            </div>
                            <div class="info-details">
                                <div class="info-detail">
                                    <i data-lucide="calendar" width="16"></i>
                                    <span>Due: <?php echo formatDate($payment['due_date']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Maintenance Requests -->
            <?php if (!empty($maintenanceData)): ?>
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="card-title">
                        <i data-lucide="wrench" width="24" height="24"></i>
                        Recent Maintenance Requests
                    </div>
                    <a href="customer_maintenance.php" class="card-header-action">
                        View All
                        <i data-lucide="arrow-right" width="16"></i>
                    </a>
                </div>
                
                <?php foreach (array_slice($maintenanceData, 0, 3) as $req): ?>
                    <div class="info-item priority-<?php echo strtolower($req['priority']); ?>">
                        <div class="info-header">
                            <div class="info-title"><?php echo htmlspecialchars($req['title']); ?></div>
                            <span class="status-badge status-<?php echo str_replace('_', '-', $req['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $req['status'])); ?></span>
                        </div>
                        <div class="info-details">
                            <div class="info-detail">
                                <i data-lucide="calendar" width="16"></i>
                                <span>Requested: <?php echo formatDate($req['requested_date']); ?></span>
                            </div>
                            <div class="info-detail">
                                <i data-lucide="alert-circle" width="16"></i>
                                <span>Priority: <?php echo ucfirst($req['priority']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Units Section (layout copied from customer.php) -->
        <div class="dashboard-section">
            <h2 class="section-title">
                <i data-lucide="home" width="28"></i>
                Available Units
            </h2>
            
            <!-- Search Section -->
            <div class="search-section">
                <div class="search-container">
                    <div class="search-input">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" placeholder="Search by unit number, location, bedrooms, or features..." id="unitSearch">
                    </div>
                    <div class="filter-buttons">
                        <button class="filter-btn active">All Units</button>
                        <button class="filter-btn">Studio</button>
                        <button class="filter-btn">1 Bedroom</button>
                        <button class="filter-btn">2 Bedrooms</button>
                        <button class="filter-btn">3+ Bedrooms</button>
                    </div>
                </div>
            </div>

            <!-- Units Grid -->
            <div class="units-grid">
                <?php if ($availableUnits && $availableUnits->num_rows > 0): ?>
                    <?php while($unit = $availableUnits->fetch_assoc()): 
                        $bedroomText = $unit['bedrooms'] == 1 ? '1 Bedroom' : ($unit['bedrooms'] == 0 ? 'Studio' : $unit['bedrooms'] . ' Bedrooms');
                        $bathroomText = $unit['bathrooms'] == 1 ? '1 Bathroom' : $unit['bathrooms'] . ' Bathrooms';
                        $areaText = $unit['area_sqm'] ? $unit['area_sqm'] . ' sqm' : 'N/A';
                        $rentAmount = formatCurrency($unit['monthly_rent']);
                        $amenities = !empty($unit['amenities']) ? explode(',', $unit['amenities']) : [];
                        $location = htmlspecialchars($unit['location'] ?? 'Kagay an View, Cagayan de Oro');
                        $capacity = $unit['capacity'] ?? 2;
                    ?>
                        <div class="unit-card">
                            <div class="unit-image">
                                <div class="unit-image-placeholder">
                                    <i data-lucide="home" width="48" height="48"></i>
                                </div>
                                <div class="unit-badge">Available</div>
                            </div>
                            <div class="unit-content">
                                <div class="unit-header">
                                    <h3 class="unit-title">Unit <?php echo htmlspecialchars($unit['unit_number']); ?></h3>
                                    <div class="unit-price"><?php echo $rentAmount; ?>/month</div>
                                </div>
                                <div class="unit-details">
                                    <div class="detail-item">
                                        <i data-lucide="bed" width="16"></i>
                                        <span><?php echo $bedroomText; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i data-lucide="bath" width="16"></i>
                                        <span><?php echo $bathroomText; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i data-lucide="maximize" width="16"></i>
                                        <span><?php echo $areaText; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i data-lucide="map-pin" width="16"></i>
                                        <span><?php echo $location; ?></span>
                                    </div>
                                    <div class="detail-item">
                                        <i data-lucide="users" width="16"></i>
                                        <span>Up to <?php echo $capacity; ?> people</span>
                                    </div>
                                </div>
                                <?php if (!empty($amenities)): ?>
                                    <div class="unit-amenities" style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin: 1rem 0;">
                                        <?php foreach (array_slice($amenities, 0, 4) as $amenity): ?>
                                            <span style="display: flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; background: rgba(79, 70, 229, 0.1); border-radius: 0.5rem; font-size: 0.75rem; color: var(--primary-color);">
                                                <i data-lucide="check" width="12"></i>
                                                <?php echo htmlspecialchars(trim($amenity)); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="unit-actions">
                                    <a href="unit_details.php?id=<?php echo $unit['id']; ?>" class="btn-primary" style="text-decoration: none;">
                                        <i data-lucide="eye" width="16"></i>
                                        View Details
                                    </a>
                                    <a href="booking.php?unit_id=<?php echo $unit['id']; ?>" class="btn-secondary" style="text-decoration: none;">
                                        <i data-lucide="calendar-check" width="16"></i>
                                        Reserve
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-units">
                        <i data-lucide="home"></i>
                        <h3>No Available Units</h3>
                        <p>All units are currently occupied. Please check back later or contact us for updates.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- My Reservations Sidebar -->
        <?php if ($reservationsResult && $reservationsResult->num_rows > 0): ?>
        <div class="dashboard-section">
            <h2 class="section-title">
                <i data-lucide="calendar-check" width="28"></i>
                My Reservations
            </h2>
            
            <div class="reservations-sidebar">
                <?php while ($res = $reservationsResult->fetch_assoc()): ?>
                    <div class="reservation-item">
                        <div class="reservation-header">
                            <div class="reservation-title">
                                <i data-lucide="home" width="18"></i>
                                Unit <?php echo htmlspecialchars($res['unit_number']); ?>
                            </div>
                            <span class="status-badge status-<?php echo $res['status']; ?>"><?php echo ucfirst($res['status']); ?></span>
                        </div>
                        <div class="reservation-details">
                            <div class="reservation-detail">
                                <i data-lucide="calendar" width="14"></i>
                                <span><?php echo formatDate($res['move_in_date']); ?></span>
                            </div>
                            <div class="reservation-detail">
                                <i data-lucide="dollar-sign" width="14"></i>
                                <span><?php echo formatCurrency($res['monthly_rent']); ?>/mo</span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

        

    </div>
    
    <script>
        lucide.createIcons();
        
        // Shopee-style filtering and search
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('unitSearch');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.shopee-product-card');
            
            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    productCards.forEach(card => {
                        const title = card.querySelector('.product-title').textContent.toLowerCase();
                        const specs = card.querySelector('.product-specs').textContent.toLowerCase();
                        const amenities = card.querySelector('.product-amenities')?.textContent.toLowerCase() || '';
                        
                        if (title.includes(searchTerm) || specs.includes(searchTerm) || amenities.includes(searchTerm)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
            
            // Filter functionality
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    productCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>


