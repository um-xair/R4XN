<?php
include 'config.php';

$id = $_POST['id'] ?? null;

if ($id) {
    // Optionally delete the image file from server
    $result = $conn->query("SELECT image_path FROM frontend_projects WHERE id = $id");
    $row = $result->fetch_assoc();
    if ($row && file_exists($row['image_path'])) {
        unlink($row['image_path']);
    }

    $conn->query("DELETE FROM frontend_projects WHERE id = $id");
}

header("Location: manage-frontend.php");
exit;
?>
