<?php
header('Content-Type: application/json; charset=utf-8');
include 'db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$tenant_id = isset($_POST['tenant_id']) ? intval($_POST['tenant_id']) : 0;
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0.0;
$payment_method = isset($_POST['payment_method']) ? $conn->real_escape_string(trim($_POST['payment_method'])) : '';
$reference = isset($_POST['reference_number']) ? $conn->real_escape_string(trim($_POST['reference_number'])) : null;
$notes = isset($_POST['notes']) ? $conn->real_escape_string(trim($_POST['notes'])) : null;

if ($tenant_id <= 0 || $amount <= 0 || empty($payment_method)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing or invalid fields']);
    exit;
}

// Determine unit_id from tenant record
$stmt = $conn->prepare("SELECT unit_id FROM tenants WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB prepare failed: ' . $conn->error]);
    exit;
}
$stmt->bind_param('i', $tenant_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Tenant not found']);
    $stmt->close();
    exit;
}
$row = $res->fetch_assoc();
$unit_id = $row['unit_id'] ? intval($row['unit_id']) : null;
$stmt->close();

// Insert payment (mark as paid)
$pStmt = $conn->prepare("INSERT INTO payments (tenant_id, unit_id, amount, payment_date, due_date, payment_method, status, reference_number, notes) VALUES (?, ?, ?, CURDATE(), CURDATE(), ?, 'paid', ?, ?)");
if (!$pStmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB prepare failed: ' . $conn->error]);
    exit;
}
$unitBind = $unit_id === null ? null : $unit_id;
$pStmt->bind_param('iidsss', $tenant_id, $unitBind, $amount, $payment_method, $reference, $notes);

// Workaround for nullable unit_id binding: if null, bind as null via SQL
// If unit_id is null, re-prepare query without unit_id
if ($unit_id === null) {
    $pStmt->close();
    $pStmt = $conn->prepare("INSERT INTO payments (tenant_id, unit_id, amount, payment_date, due_date, payment_method, status, reference_number, notes) VALUES (?, NULL, ?, CURDATE(), CURDATE(), ?, 'paid', ?, ?)");
    if (!$pStmt) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'DB prepare failed: ' . $conn->error]);
        exit;
    }
    $pStmt->bind_param('idss', $tenant_id, $amount, $payment_method, $reference, $notes);
}

if ($pStmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Payment recorded successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $pStmt->error]);
}

$pStmt->close();
exit;
?>
