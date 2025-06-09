<?php
include 'config.php';

$title = $_POST['title'] ?? '';
$link_url = $_POST['link_url'] ?? '';
$image = $_FILES['image'];

if ($title && $link_url && $image['tmp_name']) {
    $uploadDir = 'iot/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $filename = 'dev_' . uniqid() . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($image['tmp_name'], $targetPath)) {
        $stmt = $conn->prepare("INSERT INTO iot_projects (title, link_url, image_path, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $title, $link_url, $targetPath);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: manage-iot.php");
exit();