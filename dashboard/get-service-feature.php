<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get feature ID from URL parameter
$feature_id = $_GET['id'] ?? null;

if (!$feature_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Feature ID is required']);
    exit();
}

// Get feature data
$query = "SELECT * FROM service_features WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $feature_id);
$stmt->execute();
$result = $stmt->get_result();
$feature = $result->fetch_assoc();

if (!$feature) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Feature not found']);
    exit();
}

// Return feature data as JSON
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'feature' => $feature
]);
?>
