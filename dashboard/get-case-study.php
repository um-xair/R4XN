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
    $case_study_id = $_GET['id'];
    
    // Get case study details
    $case_study_query = "SELECT * FROM case_studies WHERE id = ?";
    $case_study_stmt = $conn->prepare($case_study_query);
    $case_study_stmt->bind_param("i", $case_study_id);
    $case_study_stmt->execute();
    $case_study_result = $case_study_stmt->get_result();
    
    if ($case_study_result->num_rows > 0) {
        $case_study = $case_study_result->fetch_assoc();
        
        // Get features for this case study
        $features_query = "SELECT feature_name FROM case_study_features WHERE case_study_id = ? ORDER BY sort_order";
        $features_stmt = $conn->prepare($features_query);
        $features_stmt->bind_param("i", $case_study_id);
        $features_stmt->execute();
        $features_result = $features_stmt->get_result();
        
        $features = [];
        while ($feature = $features_result->fetch_assoc()) {
            $features[] = $feature['feature_name'];
        }
        
        // Return case study data as JSON
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'case_study' => $case_study,
            'features' => $features
        ]);
    } else {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Case study not found'
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
