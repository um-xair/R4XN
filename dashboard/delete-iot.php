<?php
include 'config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Get image path
    $result = $conn->query("SELECT image_path FROM iot_projects WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        if (file_exists($row['image_path'])) {
            unlink($row['image_path']);
        }
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM iot_projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: manage-iot.php");
exit();
