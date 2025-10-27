<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Personal Information
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    // Employment Information
    $position = trim($_POST['position'] ?? '');
    $department = $_POST['department'] ?? null;
    $hire_date = $_POST['hire_date'] ?? null;
    $employment_type = $_POST['employment_type'] ?? 'full-time';
    $salary = $_POST['salary'] ?? null;
    $status = $_POST['status'] ?? 'active';
    
    // Additional Information
    $emergency_contact_name = trim($_POST['emergency_contact_name'] ?? '');
    $emergency_contact_phone = trim($_POST['emergency_contact_phone'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($position) || empty($email) || empty($phone) || empty($hire_date)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all required fields']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format']);
        exit;
    }

    // Check if email already exists
    $checkEmail = $conn->prepare("SELECT id FROM staff WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    if ($checkEmail->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists']);
        exit;
    }
    $checkEmail->close();

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO staff (first_name, last_name, email, phone, address, position, department, hire_date, employment_type, salary, status, emergency_contact_name, emergency_contact_phone, notes, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    
    $stmt->bind_param("sssssssssdssss", 
        $first_name,
        $last_name,
        $email, 
        $phone, 
        $address,
        $position, 
        $department, 
        $hire_date, 
        $employment_type, 
        $salary, 
        $status, 
        $emergency_contact_name, 
        $emergency_contact_phone,
        $notes
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true, 
            'message' => 'Staff member added successfully',
            'staff_id' => $conn->insert_id
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>