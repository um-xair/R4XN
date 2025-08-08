<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get feature ID and service ID from URL parameters
$feature_id = $_GET['id'] ?? null;
$service_id = $_GET['service_id'] ?? null;

if (!$feature_id || !$service_id) {
    $_SESSION['error'] = "Invalid request parameters.";
    header("Location: manage-services.php");
    exit();
}

// Delete the feature
$query = "DELETE FROM service_features WHERE id = ? AND service_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $feature_id, $service_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Feature deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting feature: " . $conn->error;
}

header("Location: manage-service-features.php?service_id=" . $service_id);
exit();
?>
