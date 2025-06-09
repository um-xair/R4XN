<?php
    include 'config.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $link = $_POST['link'] ?? '';
        $imagePath = '';
    
        if (!$id || !$name || !$link) {
            die("Missing required fields.");
        }
    
        // If new image is uploaded
        if (!empty($_FILES['image']['tmp_name'])) {
            $uploadDir = 'dev/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newName = 'dev_' . uniqid() . '.' . $ext;
            $targetPath = $uploadDir . $newName;
        
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            
                // Optional: delete old image if needed (fetch it first)
                $res = $conn->query("SELECT image_path FROM developers WHERE id=$id");
                if ($res && $old = $res->fetch_assoc()) {
                    if (file_exists($old['image_path'])) unlink($old['image_path']);
                }
            
                $stmt = $conn->prepare("UPDATE developers SET name=?, link=?, image_path=? WHERE id=?");
                $stmt->bind_param("sssi", $name, $link, $imagePath, $id);
            } else {
                die("Image upload failed.");
            }
        } else {
            // No image uploaded
            $stmt = $conn->prepare("UPDATE developers SET name=?, link=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $link, $id);
        }
    
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: manage-dev.php");
    exit();
?>