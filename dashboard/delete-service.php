<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $service_id = $_GET['id'];
    
    // Get service details to delete associated image
    $get_service = $conn->prepare("SELECT image_url FROM services WHERE id = ?");
    $get_service->bind_param("i", $service_id);
    $get_service->execute();
    $service_result = $get_service->get_result();
    
    if ($service_result->num_rows > 0) {
        $service = $service_result->fetch_assoc();
        
        // Delete the image file if it exists and is not a URL
        if (!empty($service['image_url']) && !filter_var($service['image_url'], FILTER_VALIDATE_URL) && file_exists($service['image_url'])) {
            unlink($service['image_url']);
        }
        
        // Delete the service (cascading will handle projects and case studies)
        $delete_service = $conn->prepare("DELETE FROM services WHERE id = ?");
        $delete_service->bind_param("i", $service_id);
        
        if ($delete_service->execute()) {
            $_SESSION['success'] = "Service deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting service: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Service not found.";
    }
}

header("Location: manage-services.php");
exit();
?>
