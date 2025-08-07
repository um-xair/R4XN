<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $service_id = $_GET['id'];
    
    // Get service details
    $service_query = "SELECT * FROM services WHERE id = ?";
    $service_stmt = $conn->prepare($service_query);
    $service_stmt->bind_param("i", $service_id);
    $service_stmt->execute();
    $service_result = $service_stmt->get_result();
    
    if ($service_result->num_rows > 0) {
        $service = $service_result->fetch_assoc();
        
        // Return service data as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'service' => $service
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Service not found'
        ]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>
