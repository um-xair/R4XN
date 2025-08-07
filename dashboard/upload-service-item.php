<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'upload_service_item_error.log');

require 'config.php';

// Log upload request
error_log("Service item upload request started at " . date('Y-m-d H:i:s'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add_item':
                handleAddServiceItem($conn);
                break;
            case 'update_item':
                handleUpdateServiceItem($conn);
                break;
            default:
                throw new Exception("Invalid action specified");
        }
    } catch (Exception $e) {
        error_log("Service item upload error: " . $e->getMessage());
        header("Location: manage-pricing.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: manage-pricing.php");
    exit;
}

function handleAddServiceItem($conn) {
    error_log("Adding new service item");
    
    // Validate required fields
    $service_id = $_POST['service_id'] ?? '';
    $item_name = trim($_POST['item_name'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    
    if (!is_numeric($service_id)) {
        throw new Exception("Invalid service ID");
    }
    
    if (empty($item_name)) {
        throw new Exception("Item name is required");
    }
    
    if (!is_numeric($price) || $price <= 0) {
        throw new Exception("Valid price is required");
    }
    
    // Get next sort order
    $sort_order = 1;
    $result = $conn->query("SELECT MAX(sort_order) as max_order FROM service_pricing_items WHERE service_id = $service_id");
    if ($result && $row = $result->fetch_assoc()) {
        $sort_order = ($row['max_order'] ?? 0) + 1;
    }
    
    // Insert new item
    $stmt = $conn->prepare("INSERT INTO service_pricing_items (service_id, item_name, price, description, sort_order, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("isdsi", $service_id, $item_name, $price, $description, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add service item: " . $stmt->error);
    }
    
    error_log("Service item added successfully: " . $item_name);
    header("Location: manage-pricing.php?success=service_item_added");
    exit;
}

function handleUpdateServiceItem($conn) {
    error_log("Updating service item");
    
    $item_id = $_POST['item_id'] ?? '';
    $item_name = trim($_POST['item_name'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    
    if (!is_numeric($item_id)) {
        throw new Exception("Invalid item ID");
    }
    
    if (empty($item_name)) {
        throw new Exception("Item name is required");
    }
    
    if (!is_numeric($price) || $price <= 0) {
        throw new Exception("Valid price is required");
    }
    
    // Update item
    $stmt = $conn->prepare("UPDATE service_pricing_items SET item_name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $item_name, $price, $description, $item_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update service item: " . $stmt->error);
    }
    
    error_log("Service item updated successfully: ID " . $item_id);
    header("Location: manage-pricing.php?success=service_item_updated");
    exit;
}
?> 