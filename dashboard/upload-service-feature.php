<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function handleAddFeature($conn) {
    $service_id = $_POST['service_id'];
    $feature_name = trim($_POST['feature_name']);
    $feature_description = trim($_POST['feature_description']);
    $icon_svg = trim($_POST['icon_svg'] ?? '');
    $color_class = $_POST['color_class'];
    $sort_order = (int)$_POST['sort_order'];
    
    // Validate required fields
    if (empty($feature_name) || empty($feature_description)) {
        $_SESSION['error'] = "Feature name and description are required.";
        header("Location: manage-service-features.php?service_id=" . $service_id);
        exit();
    }
    
    // Insert feature
    $query = "INSERT INTO service_features (service_id, feature_name, feature_description, icon_svg, color_class, sort_order) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issssi", $service_id, $feature_name, $feature_description, $icon_svg, $color_class, $sort_order);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Feature added successfully!";
    } else {
        $_SESSION['error'] = "Error adding feature: " . $conn->error;
    }
    
    header("Location: manage-service-features.php?service_id=" . $service_id);
    exit();
}

function handleUpdateFeature($conn) {
    $id = $_POST['id'];
    $service_id = $_POST['service_id'];
    $feature_name = trim($_POST['feature_name']);
    $feature_description = trim($_POST['feature_description']);
    $icon_svg = trim($_POST['icon_svg'] ?? '');
    $color_class = $_POST['color_class'];
    $sort_order = (int)$_POST['sort_order'];
    
    // Validate required fields
    if (empty($feature_name) || empty($feature_description)) {
        $_SESSION['error'] = "Feature name and description are required.";
        header("Location: manage-service-features.php?service_id=" . $service_id);
        exit();
    }
    
    // Update feature
    $query = "UPDATE service_features SET feature_name = ?, feature_description = ?, icon_svg = ?, color_class = ?, sort_order = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssii", $feature_name, $feature_description, $icon_svg, $color_class, $sort_order, $id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Feature updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating feature: " . $conn->error;
    }
    
    header("Location: manage-service-features.php?service_id=" . $service_id);
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        handleUpdateFeature($conn);
    } else {
        handleAddFeature($conn);
    }
} else {
    header("Location: manage-services.php");
    exit();
}
?>
