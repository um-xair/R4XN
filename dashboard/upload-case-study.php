<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    
    if ($action === 'add') {
        handleAddCaseStudy($conn);
    } elseif ($action === 'update') {
        handleUpdateCaseStudy($conn);
    }
}

function handleAddCaseStudy($conn) {
    $project_id = $_POST['project_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $preview_button_text = $_POST['preview_button_text'] ?? 'Preview Project';
    $preview_button_url = $_POST['preview_button_url'] ?? '';
    $features = $_POST['features'] ?? '';
    
    // Validate required fields
    if (empty($project_id) || empty($title) || empty($description)) {
        $_SESSION['error'] = "Project ID, title, and description are required.";
        header("Location: manage-case-studies.php?project_id=" . $project_id);
        exit();
    }
    
    // Handle image upload
    $hero_image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'case-studies/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
            header("Location: manage-case-studies.php?project_id=" . $project_id);
            exit();
        }
        
        $file_name = 'case_study_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $hero_image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: manage-case-studies.php?project_id=" . $project_id);
            exit();
        }
    }
    
    // Insert new case study
    $stmt = $conn->prepare("INSERT INTO case_studies (project_id, title, description, hero_image_url, preview_button_text, preview_button_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $project_id, $title, $description, $hero_image_url, $preview_button_text, $preview_button_url);
    
    if ($stmt->execute()) {
        $case_study_id = $conn->insert_id;
        
        // Process features if provided
        if (!empty($features)) {
            $feature_array = array_map('trim', explode(',', $features));
            $color_classes = [
                'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
                'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
                'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200',
                'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200',
                'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200',
                'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200',
                'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
                'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200'
            ];
            
            foreach ($feature_array as $index => $feature) {
                $trimmed_feature = trim($feature);
                if (!empty($trimmed_feature)) {
                    $color_class = $color_classes[$index % count($color_classes)];
                    $sort_order = $index + 1;
                    $feature_stmt = $conn->prepare("INSERT INTO case_study_features (case_study_id, feature_name, color_class, sort_order) VALUES (?, ?, ?, ?)");
                    $feature_stmt->bind_param("issi", $case_study_id, $trimmed_feature, $color_class, $sort_order);
                    $feature_stmt->execute();
                }
            }
        }
        
        $_SESSION['success'] = "Case study added successfully!";
    } else {
        $_SESSION['error'] = "Error adding case study: " . $conn->error;
    }
    
    header("Location: manage-case-studies.php?project_id=" . $project_id);
    exit();
}

function handleUpdateCaseStudy($conn) {
    $id = $_POST['id'] ?? '';
    $project_id = $_POST['project_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $preview_button_text = $_POST['preview_button_text'] ?? 'Preview Project';
    $preview_button_url = $_POST['preview_button_url'] ?? '';
    $features = $_POST['features'] ?? '';
    $status = $_POST['status'] ?? 'active';
    
    // Validate required fields
    if (empty($id) || empty($project_id) || empty($title) || empty($description)) {
        $_SESSION['error'] = "ID, project ID, title, and description are required.";
        header("Location: manage-case-studies.php?project_id=" . $project_id);
        exit();
    }
    
    // Get current image URL
    $current_image = '';
    $get_image = $conn->prepare("SELECT hero_image_url FROM case_studies WHERE id = ?");
    $get_image->bind_param("i", $id);
    $get_image->execute();
    $image_result = $get_image->get_result();
    if ($image_result->num_rows > 0) {
        $current_image = $image_result->fetch_assoc()['hero_image_url'];
    }
    
    // Handle image upload
    $hero_image_url = $current_image;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'case-studies/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
            header("Location: manage-case-studies.php?project_id=" . $project_id);
            exit();
        }
        
        $file_name = 'case_study_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // Delete old image if it exists and is not a URL
            if (!empty($current_image) && !filter_var($current_image, FILTER_VALIDATE_URL) && file_exists($current_image)) {
                unlink($current_image);
            }
            $hero_image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: manage-case-studies.php?project_id=" . $project_id);
            exit();
        }
    }
    
    // Update case study
    $stmt = $conn->prepare("UPDATE case_studies SET project_id = ?, title = ?, description = ?, hero_image_url = ?, preview_button_text = ?, preview_button_url = ?, status = ? WHERE id = ?");
    $stmt->bind_param("issssssi", $project_id, $title, $description, $hero_image_url, $preview_button_text, $preview_button_url, $status, $id);
    
    if ($stmt->execute()) {
        // Update features if provided
        if (!empty($features)) {
            // Delete existing features
            $delete_features = $conn->prepare("DELETE FROM case_study_features WHERE case_study_id = ?");
            $delete_features->bind_param("i", $id);
            $delete_features->execute();
            
            // Add new features
            $feature_array = array_map('trim', explode(',', $features));
            $color_classes = [
                'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200',
                'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200',
                'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200',
                'bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200',
                'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200',
                'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200',
                'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200',
                'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200'
            ];
            
            foreach ($feature_array as $index => $feature) {
                $trimmed_feature = trim($feature);
                if (!empty($trimmed_feature)) {
                    $color_class = $color_classes[$index % count($color_classes)];
                    $sort_order = $index + 1;
                    $feature_stmt = $conn->prepare("INSERT INTO case_study_features (case_study_id, feature_name, color_class, sort_order) VALUES (?, ?, ?, ?)");
                    $feature_stmt->bind_param("issi", $id, $trimmed_feature, $color_class, $sort_order);
                    $feature_stmt->execute();
                }
            }
        }
        
        $_SESSION['success'] = "Case study updated successfully!";
        header("Location: manage-case-studies.php?project_id=" . $project_id);
    } else {
        $_SESSION['error'] = "Error updating case study: " . $conn->error;
        header("Location: manage-case-studies.php?project_id=" . $project_id);
    }
    
    exit();
}
?>
