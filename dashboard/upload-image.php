<?php
    session_start();
    require 'config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $uploadDir = 'index-assets/';
        $fileName = uniqid() . "-" . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
    
        // Move the uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            $stmt = $conn->prepare("INSERT INTO project_images (image_path, created_at) VALUES (?, NOW())");
            $stmt->bind_param("s", $targetPath);
            $stmt->execute();
            $stmt->close();
        
            header("Location: manage-project.php?uploaded=1");
            exit;
        } else {
            echo "Upload failed!";
        }
    }
?>