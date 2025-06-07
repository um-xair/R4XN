<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $link = trim($_POST['link']);

  if (empty($name) || empty($link)) {
    die('All fields required.');
  }

  if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    die('Image upload failed.');
  }

  $image = $_FILES['image'];
  $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

  if (!in_array($image['type'], $allowed_types)) {
    die('Invalid image type.');
  }

  $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
  $filename = uniqid('dev_', true) . '.' . $ext;
  $upload_dir = __DIR__ . '/dev/';
  $upload_path = $upload_dir . $filename;

  if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
  }

  if (!move_uploaded_file($image['tmp_name'], $upload_path)) {
    die('Failed to move uploaded file.');
  }

  $image_path = 'dashboard/dev/' . $filename;

  $stmt = $conn->prepare("INSERT INTO developers (name, link, image_path) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $link, $image_path);
  $stmt->execute();
  $stmt->close();

  header("Location: manage-dev.php");
  exit;
}
?>
