<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'delete_service_item_error.log');

require 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Log request
error_log("Delete service item request started at " . date('Y-m-d H:i:s'));

try {
    // Check if ID is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("Invalid item ID provided");
    }

    $item_id = (int)$_GET['id'];
    
    // Delete the item
    $stmt = $conn->prepare("DELETE FROM service_pricing_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete service item: " . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception("Service item not found");
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Service item deleted successfully'
    ]);
    
    error_log("Service item deleted successfully: ID " . $item_id);
    
} catch (Exception $e) {
    error_log("Delete service item error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 