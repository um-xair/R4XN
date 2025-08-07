<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'get_service_items_error.log');

require 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Log request
error_log("Get service items request started at " . date('Y-m-d H:i:s'));

try {
    // Check if service_id is provided
    if (!isset($_GET['service_id']) || !is_numeric($_GET['service_id'])) {
        throw new Exception("Invalid service ID provided");
    }

    $service_id = (int)$_GET['service_id'];
    
    // Fetch service data
    $service_stmt = $conn->prepare("SELECT * FROM service_pricing WHERE id = ?");
    $service_stmt->bind_param("i", $service_id);
    $service_stmt->execute();
    $service_result = $service_stmt->get_result();
    
    if ($service_result->num_rows === 0) {
        throw new Exception("Service not found");
    }
    
    $service = $service_result->fetch_assoc();
    
    // Fetch service items
    $items_stmt = $conn->prepare("SELECT * FROM service_pricing_items WHERE service_id = ? ORDER BY sort_order, item_name");
    $items_stmt->bind_param("i", $service_id);
    $items_stmt->execute();
    $items_result = $items_stmt->get_result();
    
    $items = [];
    while ($item = $items_result->fetch_assoc()) {
        $items[] = $item;
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'service' => $service,
        'items' => $items
    ]);
    
    error_log("Service items retrieved successfully for service ID: " . $service_id);
    
} catch (Exception $e) {
    error_log("Get service items error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 