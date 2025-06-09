<?php
    include 'config.php';
    
    $id = $_POST['id'];
    $title = $_POST['title'];
    $link = $_POST['link_url'];
    
    if ($id && $title && $link) {
        // Get old image path
        $oldImagePath = '';
        $result = $conn->query("SELECT image_path FROM frontend_projects WHERE id = $id");
        if ($result && $row = $result->fetch_assoc()) {
            $oldImagePath = $row['image_path'];
        }
    
        // Handle new image if uploaded
        $imagePath = $oldImagePath;
        if (!empty($_FILES['image']['tmp_name'])) {
            $uploadDir = 'frontend/';
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $filename;
        
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                if (file_exists($oldImagePath)) unlink($oldImagePath); // remove old image
                $imagePath = $targetPath;
            }
        }
    
        $stmt = $conn->prepare("UPDATE frontend_projects SET title = ?, link_url = ?, image_path = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $link, $imagePath, $id);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: manage-frontend.php");
    exit;
?>
