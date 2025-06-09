<?php 
    include 'config.php';
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = $_POST['title'];
        $link_url = $_POST['link_url'];
    
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            // This is relative to upload-frontend.php
            $uploadDir = 'frontend/'; // means /dashboard/frontend/
            
            // This is what will be stored in DB and used by frontend.php
            $publicPath = 'dashboard/frontend/';
        
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
        
            $fileTmp = $_FILES['image']['tmp_name'];
            $fileName = basename($_FILES['image']['name']);
            $newFileName = time() . '_' . $fileName;
            $uploadPath = $uploadDir . $newFileName;
            $imagePathForDB = $publicPath . $newFileName;
        
            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $stmt = $conn->prepare("INSERT INTO frontend_projects (title, link_url, image_path) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $title, $link_url, $imagePathForDB);
                if ($stmt->execute()) {
                    header("Location: manage-frontend.php?success=1");
                    exit;
                } else {
                    echo "Database error: " . $stmt->error;
                }
            } else {
                echo "Failed to move uploaded file.";
            }
        } else {
            echo "Image upload error: " . $_FILES['image']['error'];
        }
    } else {
        echo "Invalid request method.";
    }
?>
