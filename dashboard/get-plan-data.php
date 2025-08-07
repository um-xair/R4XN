<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'get_plan_data_error.log');

require 'config.php';

// Set JSON header
header('Content-Type: application/json');

// Log request
error_log("Get plan data request started at " . date('Y-m-d H:i:s'));

try {
    // Check if ID is provided
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        throw new Exception("Invalid plan ID provided");
    }

    $plan_id = (int)$_GET['id'];
    
    // Fetch plan data
    $stmt = $conn->prepare("SELECT * FROM pricing_plans WHERE id = ?");
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Plan not found");
    }
    
    $plan = $result->fetch_assoc();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'plan' => $plan
    ]);
    
    error_log("Plan data retrieved successfully for ID: " . $plan_id);
    
} catch (Exception $e) {
    error_log("Get plan data error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?> 