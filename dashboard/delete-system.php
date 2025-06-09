<?php
    include 'config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
    
        // Get image path to delete file
        $stmt = $conn->prepare("SELECT image_path FROM system_projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($image_path);
        if ($stmt->fetch()) {
            $stmt->close();
        
            // Delete image file
            $file_path = __DIR__ . '/' . $image_path;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        
            // Delete DB record
            $stmt_del = $conn->prepare("DELETE FROM system_projects WHERE id = ?");
            $stmt_del->bind_param("i", $id);
            $stmt_del->execute();
            $stmt_del->close();
        
            header("Location: manage-system.php?deleted=1");
            exit;
        } else {
            $stmt->close();
            die('Project not found.');
        }
    }
?>