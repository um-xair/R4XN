<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_FILES['new_image'])) {
    $id = intval($_POST['id']);
    $file = $_FILES['new_image'];

    // Get current image path
    $stmt = $conn->prepare("SELECT image_path FROM project_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($oldPath);
    $stmt->fetch();
    $stmt->close();

    // Delete old file if exists
    if ($oldPath && file_exists($oldPath)) {
        unlink($oldPath);
    }

    // Save new file
    $targetDir = "index-assets/";
    $filename = uniqid() . "-" . basename($file['name']);
    $targetFile = $targetDir . $filename;
    $dbPath = "index-assets/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // Update path in DB
        $stmt = $conn->prepare("UPDATE project_images SET image_path = ?, created_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $dbPath, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage-project.php?success=Image+updated");
        exit;
    } else {
        header("Location: manage-project.php?error=Upload+failed");
        exit;
    }
} else {
    header("Location: manage-project.php?error=Invalid+request");
    exit;
}
?>
