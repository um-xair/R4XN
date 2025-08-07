<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'upload_pricing_error.log');

require 'config.php';

// Log upload request
error_log("Pricing upload request started at " . date('Y-m-d H:i:s'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add_plan':
                handleAddPlan($conn);
                break;
            case 'add_service':
                handleAddService($conn);
                break;
            case 'update_plan':
                handleUpdatePlan($conn);
                break;
            case 'update_service':
                handleUpdateService($conn);
                break;
            default:
                throw new Exception("Invalid action specified");
        }
    } catch (Exception $e) {
        error_log("Pricing upload error: " . $e->getMessage());
        header("Location: manage-pricing.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: manage-pricing.php");
    exit;
}

function handleAddPlan($conn) {
    error_log("Adding new pricing plan");
    
    // Validate required fields
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    
    if (empty($name)) {
        throw new Exception("Plan name is required");
    }
    
    if (!is_numeric($price) || $price <= 0) {
        throw new Exception("Valid price is required");
    }
    
    // Get next sort order
    $sort_order = 1;
    $result = $conn->query("SELECT MAX(sort_order) as max_order FROM pricing_plans");
    if ($result && $row = $result->fetch_assoc()) {
        $sort_order = ($row['max_order'] ?? 0) + 1;
    }
    
    // Insert new plan
    $stmt = $conn->prepare("INSERT INTO pricing_plans (name, price, description, features, is_popular, sort_order, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("sdssii", $name, $price, $description, $features, $is_popular, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add pricing plan: " . $stmt->error);
    }
    
    error_log("Pricing plan added successfully: " . $name);
    header("Location: manage-pricing.php?success=plan_added");
    exit;
}

function handleAddService($conn) {
    error_log("Adding new service pricing");
    
    // Validate required fields
    $service_name = trim($_POST['service_name'] ?? '');
    $service_description = trim($_POST['service_description'] ?? '');
    $icon_class = trim($_POST['icon_class'] ?? 'fas fa-code');
    $color_gradient = trim($_POST['color_gradient'] ?? 'from-blue-500 to-purple-600');
    
    if (empty($service_name)) {
        throw new Exception("Service name is required");
    }
    
    // Get next sort order
    $sort_order = 1;
    $result = $conn->query("SELECT MAX(sort_order) as max_order FROM service_pricing");
    if ($result && $row = $result->fetch_assoc()) {
        $sort_order = ($row['max_order'] ?? 0) + 1;
    }
    
    // Insert new service
    $stmt = $conn->prepare("INSERT INTO service_pricing (service_name, service_description, icon_class, color_gradient, sort_order, status) VALUES (?, ?, ?, ?, ?, 'active')");
    $stmt->bind_param("ssssi", $service_name, $service_description, $icon_class, $color_gradient, $sort_order);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add service pricing: " . $stmt->error);
    }
    
    $service_id = $conn->insert_id;
    
    // Add default pricing items if provided
    if (isset($_POST['pricing_items']) && is_array($_POST['pricing_items'])) {
        foreach ($_POST['pricing_items'] as $index => $item) {
            if (!empty($item['name']) && !empty($item['price'])) {
                $item_name = trim($item['name']);
                $item_price = $item['price'];
                $item_description = trim($item['description'] ?? '');
                
                $stmt = $conn->prepare("INSERT INTO service_pricing_items (service_id, item_name, price, description, sort_order, status) VALUES (?, ?, ?, ?, ?, 'active')");
                $stmt->bind_param("isdsi", $service_id, $item_name, $item_price, $item_description, $index);
                $stmt->execute();
            }
        }
    }
    
    // Add default features if provided
    if (isset($_POST['features']) && is_array($_POST['features'])) {
        foreach ($_POST['features'] as $index => $feature) {
            if (!empty($feature)) {
                $feature_name = trim($feature);
                
                $stmt = $conn->prepare("INSERT INTO service_features (service_id, feature_name, sort_order, status) VALUES (?, ?, ?, 'active')");
                $stmt->bind_param("isi", $service_id, $feature_name, $index);
                $stmt->execute();
            }
        }
    }
    
    error_log("Service pricing added successfully: " . $service_name);
    header("Location: manage-pricing.php?success=service_added");
    exit;
}

function handleUpdatePlan($conn) {
    error_log("Updating pricing plan");
    error_log("POST data received: " . print_r($_POST, true));
    
    $plan_id = $_POST['plan_id'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $features = trim($_POST['features'] ?? '');
    $is_popular = isset($_POST['is_popular']) ? 1 : 0;
    
    if (!is_numeric($plan_id)) {
        throw new Exception("Invalid plan ID");
    }
    
    if (empty($name)) {
        throw new Exception("Plan name is required");
    }
    
    if (!is_numeric($price) || $price <= 0) {
        throw new Exception("Valid price is required");
    }
    
    // Update plan without changing the status - preserve current status
    $stmt = $conn->prepare("UPDATE pricing_plans SET name = ?, price = ?, description = ?, features = ?, is_popular = ? WHERE id = ?");
    $stmt->bind_param("sdssii", $name, $price, $description, $features, $is_popular, $plan_id);
    
    error_log("Update plan - Plan ID: " . $plan_id);
    error_log("Binding parameters: name='$name', price='$price', description='$description', features='$features', is_popular='$is_popular', plan_id='$plan_id'");
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update pricing plan: " . $stmt->error);
    }
    
    error_log("Pricing plan updated successfully: ID " . $plan_id);
    header("Location: manage-pricing.php?success=plan_updated");
    exit;
}

function handleUpdateService($conn) {
    error_log("Updating service pricing");
    
    $service_id = $_POST['service_id'] ?? '';
    $service_name = trim($_POST['service_name'] ?? '');
    $service_description = trim($_POST['service_description'] ?? '');
    $icon_class = trim($_POST['icon_class'] ?? 'fas fa-code');
    $color_gradient = trim($_POST['color_gradient'] ?? 'from-blue-500 to-purple-600');
    
    if (!is_numeric($service_id)) {
        throw new Exception("Invalid service ID");
    }
    
    if (empty($service_name)) {
        throw new Exception("Service name is required");
    }
    
    // Update service without changing the status - preserve current status
    $stmt = $conn->prepare("UPDATE service_pricing SET service_name = ?, service_description = ?, icon_class = ?, color_gradient = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $service_name, $service_description, $icon_class, $color_gradient, $service_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to update service pricing: " . $stmt->error);
    }
    
    // Update pricing items if provided
    if (isset($_POST['pricing_items']) && is_array($_POST['pricing_items'])) {
        // Delete existing items
        $stmt = $conn->prepare("DELETE FROM service_pricing_items WHERE service_id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        
        // Insert new items
        foreach ($_POST['pricing_items'] as $index => $item) {
            if (!empty($item['name']) && !empty($item['price'])) {
                $item_name = trim($item['name']);
                $item_price = $item['price'];
                $item_description = trim($item['description'] ?? '');
                
                $stmt = $conn->prepare("INSERT INTO service_pricing_items (service_id, item_name, price, description, sort_order, status) VALUES (?, ?, ?, ?, ?, 'active')");
                $stmt->bind_param("isdsi", $service_id, $item_name, $item_price, $item_description, $index);
                $stmt->execute();
            }
        }
    }
    
    // Update features if provided
    if (isset($_POST['features']) && is_array($_POST['features'])) {
        // Delete existing features
        $stmt = $conn->prepare("DELETE FROM service_features WHERE service_id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        
        // Insert new features
        foreach ($_POST['features'] as $index => $feature) {
            if (!empty($feature)) {
                $feature_name = trim($feature);
                
                $stmt = $conn->prepare("INSERT INTO service_features (service_id, feature_name, sort_order, status) VALUES (?, ?, ?, 'active')");
                $stmt->bind_param("isi", $service_id, $feature_name, $index);
                $stmt->execute();
            }
        }
    }
    
    error_log("Service pricing updated successfully: ID " . $service_id);
    header("Location: manage-pricing.php?success=service_updated");
    exit;
}
?> 