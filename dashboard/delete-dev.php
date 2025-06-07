<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = intval($_POST['id']);

  $stmt = $conn->prepare("SELECT image_path FROM developers WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->bind_result($image_path);
  if (!$stmt->fetch()) {
    $stmt->close();
    die('Developer not found.');
  }
  $stmt->close();

  // Delete image file
  $file = __DIR__ . '/' . $image_path;
  if (file_exists($file)) {
    unlink($file);
  }

  // Delete DB entry
  $stmt = $conn->prepare("DELETE FROM developers WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();

  header("Location: manage-dev.php");
  exit;
}
?>
