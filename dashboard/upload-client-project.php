<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'upload_error.log');

require 'config.php';

// Log upload request
error_log("Upload request started at " . date('Y-m-d H:i:s'));

// Function to generate unique filename
function generateUniqueFilename($originalName, $extension) {
    $timestamp = time();
    $randomString = bin2hex(random_bytes(8));
    return $timestamp . '_' . $randomString . '.' . $extension;
}

// Function to validate image
function validateImage($file) {
    $allowedTypes = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 
        'image/webp', 'image/bmp', 'image/tiff', 'image/svg+xml'
    ];
    $maxSize = 10 * 1024 * 1024; // 10MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return "Invalid file type. Only JPG, JPEG, PNG, GIF, WebP, BMP, TIFF, and SVG images are allowed.";
    }
    
    if ($file['size'] > $maxSize) {
        return "File size too large. Maximum size is 10MB.";
    }
    
    return null;
}

// Function to resize and optimize image
function processImage($sourcePath, $destinationPath) {
    $imageInfo = getimagesize($sourcePath);
    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $type = $imageInfo[2];
    
    // For SVG files, just copy the file
    if ($type === IMAGETYPE_UNKNOWN && pathinfo($sourcePath, PATHINFO_EXTENSION) === 'svg') {
        return copy($sourcePath, $destinationPath);
    }
    
    // Create image resource based on type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $source = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $source = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_GIF:
            $source = imagecreatefromgif($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $source = imagecreatefromwebp($sourcePath);
            break;
        case IMAGETYPE_BMP:
            $source = imagecreatefromwbmp($sourcePath);
            break;
        default:
            // For unsupported types, try to copy the original file
            return copy($sourcePath, $destinationPath);
    }
    
    if (!$source) {
        return false;
    }
    
    // Calculate new dimensions (max 1200x800)
    $maxWidth = 1200;
    $maxHeight = 800;
    
    if ($width > $maxWidth || $height > $maxHeight) {
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
    } else {
        $newWidth = $width;
        $newHeight = $height;
    }
    
    // Create new image
    $destination = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency for PNG and GIF
    if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagealphablending($destination, false);
        imagesavealpha($destination, true);
        $transparent = imagecolorallocatealpha($destination, 255, 255, 255, 127);
        imagefilledrectangle($destination, 0, 0, $newWidth, $newHeight, $transparent);
    }
    
    // Resize image
    imagecopyresampled($destination, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Save image
    $success = false;
    switch ($type) {
        case IMAGETYPE_JPEG:
            $success = imagejpeg($destination, $destinationPath, 85);
            break;
        case IMAGETYPE_PNG:
            $success = imagepng($destination, $destinationPath, 8);
            break;
        case IMAGETYPE_GIF:
            $success = imagegif($destination, $destinationPath);
            break;
        case IMAGETYPE_WEBP:
            $success = imagewebp($destination, $destinationPath, 85);
            break;
        case IMAGETYPE_BMP:
            $success = imagewbmp($destination, $destinationPath);
            break;
    }
    
    // Clean up
    imagedestroy($source);
    imagedestroy($destination);
    
    return $success;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $website_type = trim($_POST['website_type'] ?? '');
    $client_name = trim($_POST['client_name'] ?? '');
    $timeline = trim($_POST['timeline'] ?? '');
    $website_url = trim($_POST['website_url'] ?? '');
    $status = $_POST['status'] ?? 'active';
    $featured = intval($_POST['featured'] ?? 0);
    $project_id = intval($_POST['project_id'] ?? 0);
    
    // Validate required fields
    $errors = [];
    if (empty($title)) $errors[] = "Project title is required.";
    if (empty($description)) $errors[] = "Project description is required.";
    if (empty($website_type)) $errors[] = "Website type is required.";
    if (empty($client_name)) $errors[] = "Client name is required.";
    if (empty($timeline)) $errors[] = "Timeline is required.";
    if (empty($website_url)) $errors[] = "Website URL is required.";
    
    // Validate URL format
    if (!empty($website_url) && !filter_var($website_url, FILTER_VALIDATE_URL)) {
        $errors[] = "Please enter a valid website URL.";
    }
    
    // Handle image upload
    $image_path = '';
    $isUpdate = !empty($project_id);
    
    if ($isUpdate) {
        // For updates, image is optional
        if (!empty($_FILES['image']['name'])) {
            $imageError = validateImage($_FILES['image']);
            if ($imageError) {
                $errors[] = $imageError;
            }
        }
    } else {
        // For new projects, image is required
        if (empty($_FILES['image']['name'])) {
            $errors[] = "Project image is required.";
        } else {
            $imageError = validateImage($_FILES['image']);
            if ($imageError) {
                $errors[] = $imageError;
            }
        }
    }
    
    if (empty($errors)) {
        error_log("No validation errors, proceeding with upload/update");
        try {
            // Handle image upload if provided
            if (!empty($_FILES['image']['name'])) {
                error_log("Image upload detected: " . $_FILES['image']['name']);
                $uploadDir = 'index-assets/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $filename = generateUniqueFilename($_FILES['image']['name'], $fileExtension);
                $uploadPath = $uploadDir . $filename;
                
                // Process and save image
                error_log("Processing image: " . $uploadPath);
                if (processImage($_FILES['image']['tmp_name'], $uploadPath)) {
                    $image_path = $uploadPath;
                    error_log("Image processed successfully: " . $image_path);
                } else {
                    error_log("Failed to process image: " . $uploadPath);
                    throw new Exception("Failed to process image.");
                }
            }
            
            if ($isUpdate) {
                error_log("Updating existing project ID: " . $project_id);
                // Update existing project
                if (!empty($image_path)) {
                    // Get old image path to delete
                    $stmt = $conn->prepare("SELECT image_path FROM client_projects WHERE id = ?");
                    $stmt->bind_param("i", $project_id);
                    $stmt->execute();
                    $stmt->bind_result($old_image_path);
                    $stmt->fetch();
                    $stmt->close();
                    
                    // Delete old image if exists
                    if ($old_image_path && file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                    
                    // Update with new image
                    $stmt = $conn->prepare("UPDATE client_projects SET title = ?, description = ?, website_type = ?, client_name = ?, timeline = ?, website_url = ?, status = ?, featured = ?, image_path = ? WHERE id = ?");
                    $stmt->bind_param("sssssssssi", $title, $description, $website_type, $client_name, $timeline, $website_url, $status, $featured, $image_path, $project_id);
                } else {
                    // Update without changing image
                    $stmt = $conn->prepare("UPDATE client_projects SET title = ?, description = ?, website_type = ?, client_name = ?, timeline = ?, website_url = ?, status = ?, featured = ? WHERE id = ?");
                    $stmt->bind_param("ssssssssi", $title, $description, $website_type, $client_name, $timeline, $website_url, $status, $featured, $project_id);
                }
                
                $success = $stmt->execute();
                $stmt->close();
                
                if ($success) {
                    error_log("Project updated successfully: " . $project_id);
                    header("Location: manage-client-project.php?updated=1");
                    exit;
                } else {
                    error_log("Failed to update project: " . $project_id);
                    throw new Exception("Failed to update project.");
                }
            } else {
                error_log("Inserting new project");
                // Insert new project
                $stmt = $conn->prepare("INSERT INTO client_projects (title, description, website_type, client_name, timeline, website_url, status, featured, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $title, $description, $website_type, $client_name, $timeline, $website_url, $status, $featured, $image_path);
                
                $success = $stmt->execute();
                $stmt->close();
                
                if ($success) {
                    error_log("New project added successfully");
                    header("Location: manage-client-project.php?added=1");
                    exit;
                } else {
                    error_log("Failed to add new project");
                    throw new Exception("Failed to add project.");
                }
            }
            
        } catch (Exception $e) {
            error_log("Exception caught: " . $e->getMessage());
            // Clean up uploaded file if there was an error
            if (!empty($image_path) && file_exists($image_path)) {
                unlink($image_path);
                error_log("Cleaned up uploaded file: " . $image_path);
            }
            
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
    
    // If there were errors, redirect back with error message
    if (!empty($errors)) {
        $errorMessage = implode(" ", $errors);
        error_log("Validation errors: " . $errorMessage);
        header("Location: manage-client-project.php?error=" . urlencode($errorMessage));
        exit;
    }
    
    // Debug: Log the request
    error_log("Upload request received: " . print_r($_POST, true));
    error_log("Files: " . print_r($_FILES, true));
} else {
    // If not POST request, redirect to manage page
    header("Location: manage-client-project.php");
    exit;
}
?> 