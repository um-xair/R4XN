<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $link_url = trim($_POST['link_url']);

    if (empty($title) || empty($link_url) || !isset($_FILES['image'])) {
        die('Please fill all required fields and upload an image.');
    }

    $image = $_FILES['image'];

    if ($image['error'] !== UPLOAD_ERR_OK) {
        die('Error uploading the image.');
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($image['type'], $allowed_types)) {
        die('Invalid image type. Allowed types: jpg, png, gif, webp.');
    }

    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    $new_filename = uniqid('system_', true) . '.' . $ext;
    $upload_dir = __DIR__ . '/system/'; // dashboard/system/
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $upload_path = $upload_dir . $new_filename;
    if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
        die('Failed to move uploaded file.');
    }

    $image_path = 'system/' . $new_filename; // store relative path in DB

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO system_projects (title, link_url, image_path, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $title, $link_url, $image_path);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-system.php?success=1");
    exit;
}
?>
