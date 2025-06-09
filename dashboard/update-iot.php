<?php
    include 'config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $link_url = trim($_POST['link_url']);
    
        // Validate required fields
        if (empty($title) || empty($link_url)) {
            die("Title and Link URL are required.");
        }
    
        // Fetch current project to get existing image path
        $stmt = $conn->prepare("SELECT image_path FROM iot_projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            die("Project not found.");
        }
    
        $project = $result->fetch_assoc();
        $currentImagePath = $project['image_path'];
    
        // Handle image upload if new file was selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'iot/';
            $tmpName = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
            if (!in_array($fileExt, $allowedExt)) {
                die("Invalid image file type.");
            }
        
            // Create unique file name to prevent overwriting
            $newFileName = uniqid('iot_') . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;
        
            if (!move_uploaded_file($tmpName, $destination)) {
                die("Failed to upload new image.");
            }
        
            // Delete old image file if it exists and is different
            if ($currentImagePath && file_exists($currentImagePath) && $currentImagePath !== $destination) {
                unlink($currentImagePath);
            }
        
            // Update DB with new image path
            $imagePathToSave = $destination;
        } else {
            // No new image uploaded, keep current path
            $imagePathToSave = $currentImagePath;
        }
    
        // Update project data in database
        $updateStmt = $conn->prepare("UPDATE iot_projects SET title = ?, link_url = ?, image_path = ? WHERE id = ?");
        $updateStmt->bind_param("sssi", $title, $link_url, $imagePathToSave, $id);
    
        if ($updateStmt->execute()) {
            header('Location: manage-iot.php?success=updated');
            exit;
        } else {
            die("Failed to update project.");
        }
    } else {
        die("Invalid request.");
    }
    