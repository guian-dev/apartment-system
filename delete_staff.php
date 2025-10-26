<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $staffId = intval($_POST['id']);
    
    try {
        // Check if staff member is assigned to any maintenance requests
        $maintenanceCheck = $conn->query("SELECT COUNT(*) as count FROM maintenance_requests WHERE assigned_staff_id = $staffId");
        $maintenanceCount = $maintenanceCheck->fetch_assoc()['count'];
        
        if ($maintenanceCount > 0) {
            // If staff has assigned maintenance requests, mark as inactive instead of deleting
            $stmt = $conn->prepare("UPDATE staff SET status = 'inactive' WHERE id = ?");
            $stmt->bind_param("i", $staffId);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode(['success' => true, 'message' => 'Staff member marked as inactive']);
        } else {
            // Safe to delete
            $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
            $stmt->bind_param("i", $staffId);
            $stmt->execute();
            $stmt->close();
            
            echo json_encode(['success' => true, 'message' => 'Staff member deleted successfully']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
