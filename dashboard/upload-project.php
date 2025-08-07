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
        handleAddProject($conn);
    } elseif ($action === 'update') {
        handleUpdateProject($conn);
    }
}

function handleAddProject($conn) {
    $service_id = $_POST['service_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $project_url = $_POST['project_url'] ?? '';
    $case_study_url = $_POST['case_study_url'] ?? '';
    $sort_order = $_POST['sort_order'] ?? 0;
    
    // Validate required fields
    if (empty($service_id) || empty($name) || empty($description)) {
        $_SESSION['error'] = "Service ID, name, and description are required.";
        header("Location: manage-projects.php?service_id=" . $service_id);
        exit();
    }
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'projects/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
            header("Location: manage-projects.php?service_id=" . $service_id);
            exit();
        }
        
        $file_name = 'project_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: manage-projects.php?service_id=" . $service_id);
            exit();
        }
    }
    
    // Insert new project
    $stmt = $conn->prepare("INSERT INTO projects (service_id, name, description, image_url, project_url, case_study_url, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssi", $service_id, $name, $description, $image_url, $project_url, $case_study_url, $sort_order);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Project added successfully!";
    } else {
        $_SESSION['error'] = "Error adding project: " . $conn->error;
    }
    
    header("Location: manage-projects.php?service_id=" . $service_id);
    exit();
}

function handleUpdateProject($conn) {
    $id = $_POST['id'] ?? '';
    $service_id = $_POST['service_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $project_url = $_POST['project_url'] ?? '';
    $case_study_url = $_POST['case_study_url'] ?? '';
    $sort_order = $_POST['sort_order'] ?? 0;
    $status = $_POST['status'] ?? 'active';
    
    // Validate required fields
    if (empty($id) || empty($service_id) || empty($name) || empty($description)) {
        $_SESSION['error'] = "ID, service ID, name, and description are required.";
        header("Location: manage-projects.php?service_id=" . $service_id);
        exit();
    }
    
    // Get current image URL
    $current_image = '';
    $get_image = $conn->prepare("SELECT image_url FROM projects WHERE id = ?");
    $get_image->bind_param("i", $id);
    $get_image->execute();
    $image_result = $get_image->get_result();
    if ($image_result->num_rows > 0) {
        $current_image = $image_result->fetch_assoc()['image_url'];
    }
    
    // Handle image upload
    $image_url = $current_image;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'projects/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
            header("Location: manage-projects.php?service_id=" . $service_id);
            exit();
        }
        
        $file_name = 'project_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // Delete old image if it exists and is not a URL
            if (!empty($current_image) && !filter_var($current_image, FILTER_VALIDATE_URL) && file_exists($current_image)) {
                unlink($current_image);
            }
            $image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: manage-projects.php?service_id=" . $service_id);
            exit();
        }
    }
    
    // Update project
    $stmt = $conn->prepare("UPDATE projects SET service_id = ?, name = ?, description = ?, image_url = ?, project_url = ?, case_study_url = ?, sort_order = ?, status = ? WHERE id = ?");
    $stmt->bind_param("isssssisi", $service_id, $name, $description, $image_url, $project_url, $case_study_url, $sort_order, $status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Project updated successfully!";
        header("Location: manage-projects.php?service_id=" . $service_id);
    } else {
        $_SESSION['error'] = "Error updating project: " . $conn->error;
        header("Location: manage-projects.php?service_id=" . $service_id);
    }
    
    exit();
}
?>
