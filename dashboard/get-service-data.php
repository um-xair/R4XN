<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'get_service_data_error.log');

require 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Log request
error_log("Get service data request started at " . date('Y-m-d H:i:s'));

try {
    // Check if ID is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("Invalid service ID provided");
    }

    $service_id = (int)$_GET['id'];
    
    // Fetch service data
    $stmt = $conn->prepare("SELECT * FROM service_pricing WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Service not found");
    }
    
    $service = $result->fetch_assoc();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'service' => $service
    ]);
    
    error_log("Service data retrieved successfully for ID: " . $service_id);
    
} catch (Exception $e) {
    error_log("Get service data error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 