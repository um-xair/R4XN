<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $case_study_id = $_GET['id'];
    
    // Get case study details to delete associated image
    $get_case_study = $conn->prepare("SELECT hero_image_url FROM case_studies WHERE id = ?");
    $get_case_study->bind_param("i", $case_study_id);
    $get_case_study->execute();
    $case_study_result = $get_case_study->get_result();
    
    if ($case_study_result->num_rows > 0) {
        $case_study = $case_study_result->fetch_assoc();
        
        // Delete the image file if it exists and is not a URL
        if (!empty($case_study['hero_image_url']) && !filter_var($case_study['hero_image_url'], FILTER_VALIDATE_URL) && file_exists($case_study['hero_image_url'])) {
            unlink($case_study['hero_image_url']);
        }
        
        // Delete the case study (cascading will handle features)
        $delete_case_study = $conn->prepare("DELETE FROM case_studies WHERE id = ?");
        $delete_case_study->bind_param("i", $case_study_id);
        
        if ($delete_case_study->execute()) {
            $_SESSION['success'] = "Case study deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting case study: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Case study not found.";
    }
}

// Redirect back to the manage-case-studies page with project_id if available
$project_id = $_GET['project_id'] ?? '';
if (!empty($project_id)) {
    header("Location: manage-case-studies.php?project_id=" . $project_id);
} else {
    header("Location: manage-services.php");
}
exit();
?>
