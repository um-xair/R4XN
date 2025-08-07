<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $project_id = $_GET['id'];
    
    // Get project details to delete associated image
    $get_project = $conn->prepare("SELECT image_url FROM projects WHERE id = ?");
    $get_project->bind_param("i", $project_id);
    $get_project->execute();
    $project_result = $get_project->get_result();
    
    if ($project_result->num_rows > 0) {
        $project = $project_result->fetch_assoc();
        
        // Delete the image file if it exists and is not a URL
        if (!empty($project['image_url']) && !filter_var($project['image_url'], FILTER_VALIDATE_URL) && file_exists($project['image_url'])) {
            unlink($project['image_url']);
        }
        
        // Delete the project (cascading will handle case studies)
        $delete_project = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $delete_project->bind_param("i", $project_id);
        
        if ($delete_project->execute()) {
            $_SESSION['success'] = "Project deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting project: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Project not found.";
    }
}

// Redirect back to the manage-projects page with service_id if available
$service_id = $_GET['service_id'] ?? '';
if (!empty($service_id)) {
    header("Location: manage-projects.php?service_id=" . $service_id);
} else {
    header("Location: manage-services.php");
}
exit();
?>
