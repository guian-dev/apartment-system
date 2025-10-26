<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $tenantId = intval($_POST['id']);
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // First, check if tenant has any payments
        $paymentCheck = $conn->query("SELECT COUNT(*) as count FROM payments WHERE tenant_id = $tenantId");
        $paymentCount = $paymentCheck->fetch_assoc()['count'];
        
        if ($paymentCount > 0) {
            // If tenant has payments, just mark as inactive instead of deleting
            $stmt = $conn->prepare("UPDATE tenants SET status = 'inactive', move_out_date = CURDATE() WHERE id = ?");
            $stmt->bind_param("i", $tenantId);
            $stmt->execute();
            $stmt->close();
            
            // Also update the unit status to available
            $unitUpdate = $conn->prepare("UPDATE units u 
                                         INNER JOIN tenants t ON u.id = t.unit_id 
                                         SET u.status = 'available' 
                                         WHERE t.id = ?");
            $unitUpdate->bind_param("i", $tenantId);
            $unitUpdate->execute();
            $unitUpdate->close();
            
            echo json_encode(['success' => true, 'message' => 'Tenant marked as inactive']);
        } else {
            // If no payments, safe to delete
            $stmt = $conn->prepare("DELETE FROM tenants WHERE id = ?");
            $stmt->bind_param("i", $tenantId);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode(['success' => true, 'message' => 'Tenant deleted successfully']);
        }
        
        $conn->commit();
        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
