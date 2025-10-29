<?php
// Authentication and session management functions
session_start();

include 'db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

// Require login - redirect to login page if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

// Get current customer ID
function getCustomerId() {
    return $_SESSION['customer_id'] ?? null;
}

// Get current customer data
function getCurrentCustomer() {
    global $conn;
    if (!isLoggedIn()) {
        return null;
    }
    
    $customerId = getCustomerId();
    $stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Login function
function loginCustomer($emailOrPhone, $password) {
    global $conn;
    
    // Check if customers table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'customers'");
    if (!$tableCheck || $tableCheck->num_rows == 0) {
        return ['success' => false, 'message' => 'Database not set up. Please run customer_portal_setup.sql first.'];
    }
    
    // Check if input is email or phone
    $isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);
    
    try {
        if ($isEmail) {
            $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ? AND status = 'active'");
        } else {
            $stmt = $conn->prepare("SELECT * FROM customers WHERE phone = ? AND status = 'active'");
        }
        $stmt->bind_param("s", $emailOrPhone);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $customer = $result->fetch_assoc();
            if (password_verify($password, $customer['password'])) {
                $_SESSION['customer_id'] = $customer['id'];
                $_SESSION['customer_name'] = $customer['name'];
                $_SESSION['customer_email'] = $customer['email'];
                return ['success' => true, 'customer' => $customer];
            }
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Database error. Please contact administrator.'];
    }
    
    return ['success' => false, 'message' => 'Invalid email/phone or password'];
}

// Register new customer
function registerCustomer($data) {
    global $conn;
    
    // Check if customers table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'customers'");
    if (!$tableCheck || $tableCheck->num_rows == 0) {
        return ['success' => false, 'message' => 'Database not set up. Please run customer_portal_setup.sql in phpMyAdmin first. Go to http://localhost/phpmyadmin and import the file.'];
    }
    
    try {
        // Check if email or phone already exists
        $checkStmt = $conn->prepare("SELECT id FROM customers WHERE email = ? OR phone = ?");
        $checkStmt->bind_param("ss", $data['email'], $data['phone']);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            return ['success' => false, 'message' => 'Email or phone number already registered'];
        }
        
        // Hash password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Insert customer
        $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, password, id_type, id_number) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $data['name'], $data['email'], $data['phone'], $hashedPassword, $data['id_type'], $data['id_number']);
        
        if ($stmt->execute()) {
            $customerId = $conn->insert_id;
            
            // Auto login
            $_SESSION['customer_id'] = $customerId;
            $_SESSION['customer_name'] = $data['name'];
            $_SESSION['customer_email'] = $data['email'];
            
            return ['success' => true, 'customer_id' => $customerId];
        }
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
    }
    
    return ['success' => false, 'message' => 'Registration failed. Please try again.'];
}

// Generate password reset token
function generateResetToken($emailOrPhone) {
    global $conn;
    
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    $isEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL);
    if ($isEmail) {
        $stmt = $conn->prepare("UPDATE customers SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    } else {
        $stmt = $conn->prepare("UPDATE customers SET reset_token = ?, reset_token_expiry = ? WHERE phone = ?");
    }
    
    $stmt->bind_param("sss", $token, $expiry, $emailOrPhone);
    
    if ($stmt->execute() && $stmt->affected_rows > 0) {
        return ['success' => true, 'token' => $token];
    }
    
    return ['success' => false, 'message' => 'Email or phone not found'];
}

// Reset password with token
function resetPassword($token, $newPassword) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT id FROM customers WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        $updateStmt = $conn->prepare("UPDATE customers SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $customer['id']);
        
        if ($updateStmt->execute()) {
            return ['success' => true];
        }
    }
    
    return ['success' => false, 'message' => 'Invalid or expired reset token'];
}

// Logout function
function logoutCustomer() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

// Send notification to customer
function sendNotification($customerId, $type, $title, $message, $link = null) {
    global $conn;
    
    // Check if table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'customer_notifications'");
    if (!$tableCheck || $tableCheck->num_rows == 0) {
        return false; // Table doesn't exist yet
    }
    
    try {
        $stmt = $conn->prepare("INSERT INTO customer_notifications (customer_id, type, title, message, link) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $customerId, $type, $title, $message, $link);
        return $stmt->execute();
    } catch (Exception $e) {
        return false;
    }
}

// Get unread notification count
function getUnreadNotificationCount($customerId) {
    global $conn;
    
    // Check if table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'customer_notifications'");
    if (!$tableCheck || $tableCheck->num_rows == 0) {
        return 0;
    }
    
    try {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM customer_notifications WHERE customer_id = ? AND is_read = FALSE");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] ?? 0;
    } catch (Exception $e) {
        return 0;
    }
}
?>

