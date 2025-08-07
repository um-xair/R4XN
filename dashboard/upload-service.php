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
        handleAddService($conn);
    } elseif ($action === 'update') {
        handleUpdateService($conn);
    }
}

function handleAddService($conn) {
    $name = $_POST['name'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $description = $_POST['description'] ?? '';
    $sort_order = $_POST['sort_order'] ?? 0;
    
    // Validate required fields
    if (empty($name) || empty($slug) || empty($description)) {
        $_SESSION['error'] = "Name, slug, and description are required.";
        header("Location: manage-services.php");
        exit();
    }
    
    // Check if slug already exists
    $check_slug = $conn->prepare("SELECT id FROM services WHERE slug = ?");
    $check_slug->bind_param("s", $slug);
    $check_slug->execute();
    $result = $check_slug->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "A service with this slug already exists.";
        header("Location: manage-services.php");
        exit();
    }
    
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'services/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
            header("Location: manage-services.php");
            exit();
        }
        
        $file_name = 'service_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: manage-services.php");
            exit();
        }
    }
    
    // Insert new service
    $stmt = $conn->prepare("INSERT INTO services (name, slug, description, image_url, sort_order) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $slug, $description, $image_url, $sort_order);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Service added successfully!";
    } else {
        $_SESSION['error'] = "Error adding service: " . $conn->error;
    }
    
    header("Location: manage-services.php");
    exit();
}

function handleUpdateService($conn) {
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $description = $_POST['description'] ?? '';
    $sort_order = $_POST['sort_order'] ?? 0;
    $status = $_POST['status'] ?? 'active';
    
    // Validate required fields
    if (empty($id) || empty($name) || empty($slug) || empty($description)) {
        $_SESSION['error'] = "ID, name, slug, and description are required.";
        header("Location: manage-services.php");
        exit();
    }
    
    // Check if slug already exists for other services
    $check_slug = $conn->prepare("SELECT id FROM services WHERE slug = ? AND id != ?");
    $check_slug->bind_param("si", $slug, $id);
    $check_slug->execute();
    $result = $check_slug->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "A service with this slug already exists.";
        header("Location: manage-services.php");
        exit();
    }
    
    // Get current image URL
    $current_image = '';
    $get_image = $conn->prepare("SELECT image_url FROM services WHERE id = ?");
    $get_image->bind_param("i", $id);
    $get_image->execute();
    $image_result = $get_image->get_result();
    if ($image_result->num_rows > 0) {
        $current_image = $image_result->fetch_assoc()['image_url'];
    }
    
    // Handle image upload
    $image_url = $current_image;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'services/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($file_extension, $allowed_extensions)) {
            $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF, and WebP files are allowed.";
            header("Location: manage-services.php");
            exit();
        }
        
        $file_name = 'service_' . time() . '_' . uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            // Delete old image if it exists and is not a URL
            if (!empty($current_image) && !filter_var($current_image, FILTER_VALIDATE_URL) && file_exists($current_image)) {
                unlink($current_image);
            }
            $image_url = $upload_path;
        } else {
            $_SESSION['error'] = "Error uploading image.";
            header("Location: manage-services.php");
            exit();
        }
    }
    
    // Update service
    $stmt = $conn->prepare("UPDATE services SET name = ?, slug = ?, description = ?, image_url = ?, sort_order = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssssisi", $name, $slug, $description, $image_url, $sort_order, $status, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Service updated successfully!";
        header("Location: manage-services.php");
    } else {
        $_SESSION['error'] = "Error updating service: " . $conn->error;
        header("Location: manage-services.php");
    }
    
    exit();
}
?>
