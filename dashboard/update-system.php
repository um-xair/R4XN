<?php
    include 'config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $title = trim($_POST['title']);
        $link_url = trim($_POST['link_url']);
    
        if (empty($title) || empty($link_url)) {
            die('Title and Link URL are required.');
        }
    
        // Get current image path
        $stmt = $conn->prepare("SELECT image_path FROM system_projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($current_image_path);
        if (!$stmt->fetch()) {
            $stmt->close();
            die('Project not found.');
        }
        $stmt->close();
    
        $new_image_path = $current_image_path;
    
        // Check if a new image was uploaded
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES['image'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($image['type'], $allowed_types)) {
                die('Invalid image type. Allowed types: jpg, png, gif, webp.');
            }
        
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('system_', true) . '.' . $ext;
            $upload_dir = __DIR__ . '/system/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $upload_path = $upload_dir . $new_filename;
        
            if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
                die('Failed to move uploaded file.');
            }
        
            // Delete old image file
            $old_file_path = __DIR__ . '/' . $current_image_path;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        
            $new_image_path = 'system/' . $new_filename;
        }
    
        // Update DB record
        $stmt = $conn->prepare("UPDATE system_projects SET title = ?, link_url = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $link_url, $new_image_path, $id);
        $stmt->execute();
        $stmt->close();
    
        header("Location: manage-system.php?updated=1");
        exit;
    }
?>