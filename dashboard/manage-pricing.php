<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'pricing_error.log');

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

// Log page access
error_log("Manage Pricing page accessed at " . date('Y-m-d H:i:s'));

// Handle delete operations
if (isset($_GET['delete_plan']) && is_numeric($_GET['delete_plan'])) {
    $plan_id = $_GET['delete_plan'];
    $stmt = $conn->prepare("DELETE FROM pricing_plans WHERE id = ?");
    $stmt->bind_param("i", $plan_id);
    if ($stmt->execute()) {
        error_log("Pricing plan deleted: ID " . $plan_id);
        header("Location: manage-pricing.php?success=plan_deleted");
        exit;
    }
}

if (isset($_GET['delete_service']) && is_numeric($_GET['delete_service'])) {
    $service_id = $_GET['delete_service'];
    $stmt = $conn->prepare("DELETE FROM service_pricing WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    if ($stmt->execute()) {
        error_log("Service pricing deleted: ID " . $service_id);
        header("Location: manage-pricing.php?success=service_deleted");
        exit;
    }
}

// Handle status toggle
if (isset($_GET['toggle_plan_status']) && is_numeric($_GET['toggle_plan_status'])) {
    $plan_id = $_GET['toggle_plan_status'];
    $stmt = $conn->prepare("UPDATE pricing_plans SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = ?");
    $stmt->bind_param("i", $plan_id);
    if ($stmt->execute()) {
        error_log("Pricing plan status toggled: ID " . $plan_id);
        header("Location: manage-pricing.php?success=status_updated");
        exit;
    }
}

if (isset($_GET['toggle_service_status']) && is_numeric($_GET['toggle_service_status'])) {
    $service_id = $_GET['toggle_service_status'];
    $stmt = $conn->prepare("UPDATE service_pricing SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = ?");
    $stmt->bind_param("i", $service_id);
    if ($stmt->execute()) {
        error_log("Service pricing status toggled: ID " . $service_id);
        header("Location: manage-pricing.php?success=status_updated");
        exit;
    }
}

// Handle popular toggle
if (isset($_GET['toggle_popular']) && is_numeric($_GET['toggle_popular'])) {
    $plan_id = $_GET['toggle_popular'];
    $stmt = $conn->prepare("UPDATE pricing_plans SET is_popular = CASE WHEN is_popular = 1 THEN 0 ELSE 1 END WHERE id = ?");
    $stmt->bind_param("i", $plan_id);
    if ($stmt->execute()) {
        error_log("Pricing plan popular status toggled: ID " . $plan_id);
        header("Location: manage-pricing.php?success=popular_updated");
        exit;
    }
}

// Fetch pricing plans
$plans_query = "SELECT * FROM pricing_plans ORDER BY sort_order, name";
$plans_result = $conn->query($plans_query);

// Fetch service pricing
$services_query = "SELECT * FROM service_pricing ORDER BY sort_order, service_name";
$services_result = $conn->query($services_query);

error_log("Pricing data retrieved successfully");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no" />
<title>Manage Pricing - Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        font-family: "Poppins", sans-serif;
    }
    html {
        overflow-x: hidden;
    }
    ::-webkit-scrollbar {
        display: none;
    }
</style>
</head>
<body class="bg-[#F5F5FD]">
    <div class="flex h-screen overflow-hidden">

        <?php include 'sidebar.php';?>

        <main class="flex-1 p-6 w-full overflow-y-auto min-w-0 relative">

            <!-- Header with title left, add button right -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-xl sm:text-2xl font-bold mb-1">Manage Pricing</h1>
                    <p class="text-xs text-gray-400">Add, edit, and manage pricing plans and service-specific pricing</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button onclick="openModal('add-plan-modal')" class="bg-black text-white px-4 sm:px-6 py-3 rounded-md w-full sm:w-auto text-sm sm:text-base" type="button">
                        <i class="fas fa-plus mr-2"></i>Add Plan
                    </button>
                    <button onclick="openModal('add-service-modal')" class="bg-green-600 text-white px-4 sm:px-6 py-3 rounded-md w-full sm:w-auto text-sm sm:text-base" type="button">
                        <i class="fas fa-plus mr-2"></i>Add Service
                    </button>
                </div>
            </div>



            <!-- Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-4 sm:space-x-8 overflow-x-auto">
                        <button onclick="showTab('pricing-plans')" class="tab-button active py-2 px-1 border-b-2 border-blue-500 font-medium text-xs sm:text-sm text-blue-600 whitespace-nowrap">
                            Pricing Plans
                        </button>
                        <button onclick="showTab('service-pricing')" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-xs sm:text-sm text-gray-500 hover:text-gray-700 whitespace-nowrap">
                            Service Pricing
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Pricing Plans Tab -->
            <div id="pricing-plans" class="tab-content">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <?php if ($plans_result && $plans_result->num_rows > 0): ?>
                        <?php while ($plan = $plans_result->fetch_assoc()): ?>
                            <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                                <!-- Plan Header -->
                                <div class="p-3 sm:p-4 border-b border-gray-100">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 gap-2 sm:gap-0">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-base sm:text-lg text-gray-900"><?= htmlspecialchars($plan['name']) ?></h3>
                                            <p class="text-xl sm:text-2xl font-bold text-blue-600">RM <?= number_format($plan['price'], 2) ?></p>
                                        </div>
                                        <div class="flex flex-wrap gap-1 sm:gap-2">
                                            <span class="px-2 sm:px-3 py-1 text-xs rounded-full <?= $plan['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= ucfirst($plan['status']) ?>
                                            </span>
                                            <?php if ($plan['is_popular']): ?>
                                                <span class="px-2 sm:px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-star mr-1"></i>Popular
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <p class="text-xs sm:text-sm text-gray-600 mb-3"><?= htmlspecialchars($plan['description']) ?></p>
                                    
                                    <!-- Features Preview -->
                                    <div class="flex flex-wrap gap-1 sm:gap-2">
                                        <?php 
                                        $features = explode(',', $plan['features']);
                                        foreach (array_slice($features, 0, 3) as $feature): ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                                <?= htmlspecialchars(trim($feature)) ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($features) > 3): ?>
                                            <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">
                                                +<?= count($features) - 3 ?> more
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="p-4">
                                    <div class="flex flex-col sm:flex-row gap-2 pt-3 border-t border-gray-100">
                                        <button onclick="editPlan(<?= $plan['id'] ?>)" class="bg-blue-600 text-white py-3 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex-1 flex items-center justify-center">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </button>
                                        
                                        <a href="?toggle_plan_status=<?= $plan['id'] ?>" class="border border-gray-300 text-gray-700 py-3 px-4 rounded-lg text-sm font-medium text-center hover:bg-gray-50 transition-colors flex-1 flex items-center justify-center">
                                            <i class="fas fa-toggle-<?= $plan['status'] === 'active' ? 'on' : 'off' ?> mr-2"></i><?= $plan['status'] === 'active' ? 'Active' : 'Inactive' ?>
                                        </a>
                                        
                                        <a href="?toggle_popular=<?= $plan['id'] ?>" class="border border-yellow-300 text-yellow-700 py-3 px-4 rounded-lg text-sm font-medium text-center hover:bg-yellow-50 transition-colors flex-1 flex items-center justify-center">
                                            <i class="fas fa-star mr-2"></i>Popular
                                        </a>
                                    </div>
                                    
                                    <div class="flex gap-2 mt-2">
                                        <a href="?delete_plan=<?= $plan['id'] ?>" onclick="return confirm('Are you sure you want to delete this plan?')" class="border border-red-300 text-red-700 py-3 px-4 rounded-lg text-sm font-medium text-center w-full hover:bg-red-50 transition-colors flex items-center justify-center">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-pricing-tag text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No pricing plans found. Add your first plan!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Service Pricing Tab -->
            <div id="service-pricing" class="tab-content hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    <?php if ($services_result && $services_result->num_rows > 0): ?>
                        <?php while ($service = $services_result->fetch_assoc()): ?>
                            <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                                <!-- Service Header -->
                                <div class="p-3 sm:p-4 border-b border-gray-100">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-3 gap-2 sm:gap-0">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br <?= $service['color_gradient'] ?> rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                                <i class="<?= $service['icon_class'] ?> text-white text-sm sm:text-base"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="font-semibold text-base sm:text-lg text-gray-900 truncate"><?= htmlspecialchars($service['service_name']) ?></h3>
                                                <span class="px-2 sm:px-3 py-1 text-xs rounded-full <?= $service['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= ucfirst($service['status']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-xs sm:text-sm text-gray-600 mb-3"><?= htmlspecialchars($service['service_description']) ?></p>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="p-4">
                                    <div class="flex flex-col sm:flex-row gap-2 pt-3 border-t border-gray-100">
                                        <button onclick="editService(<?= $service['id'] ?>)" class="bg-green-600 text-white py-3 px-4 rounded-lg text-sm font-medium hover:bg-green-700 transition-colors flex-1 flex items-center justify-center">
                                            <i class="fas fa-edit mr-2"></i>Edit
                                        </button>
                                        
                                        <a href="?toggle_service_status=<?= $service['id'] ?>" class="border border-gray-300 text-gray-700 py-3 px-4 rounded-lg text-sm font-medium text-center hover:bg-gray-50 transition-colors flex-1 flex items-center justify-center">
                                            <i class="fas fa-toggle-<?= $service['status'] === 'active' ? 'on' : 'off' ?> mr-2"></i><?= $service['status'] === 'active' ? 'Active' : 'Inactive' ?>
                                        </a>
                                        
                                        <button onclick="manageServiceItems(<?= $service['id'] ?>)" class="bg-purple-600 text-white py-3 px-4 rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors flex-1 flex items-center justify-center">
                                            <i class="fas fa-list mr-2"></i>Items
                                        </button>
                                    </div>
                                    
                                    <div class="flex gap-2 mt-2">
                                        <a href="?delete_service=<?= $service['id'] ?>" onclick="return confirm('Are you sure you want to delete this service?')" class="border border-red-300 text-red-700 py-3 px-4 rounded-lg text-sm font-medium text-center w-full hover:bg-red-50 transition-colors flex items-center justify-center">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-cogs text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500">No service pricing found. Add your first service!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Plan Modal -->
    <div id="add-plan-modal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-md">
                        <i class="fas fa-tag text-white"></i>
                    </div>
                    <span>Add Pricing Plan</span>
                </h2>
                <button onclick="closeModal('add-plan-modal')" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="upload-pricing.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="add_plan">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
                        <input type="text" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (RM) *</label>
                        <input type="number" name="price" step="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe the pricing plan..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features (comma-separated)</label>
                        <textarea name="features" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Feature 1, Feature 2, Feature 3..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_popular" id="is_popular" class="mr-2">
                            <label for="is_popular" class="text-sm text-gray-700">Mark as Popular</label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('add-plan-modal')" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Add Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div id="add-service-modal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-green-600 p-2 rounded-md">
                        <i class="fas fa-cogs text-white"></i>
                    </div>
                    <span>Add Service Pricing</span>
                </h2>
                <button onclick="closeModal('add-service-modal')" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="upload-pricing.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="add_service">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Service Name *</label>
                        <input type="text" name="service_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                        <input type="text" name="icon_class" value="fas fa-code" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="service_description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe the service..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Gradient</label>
                        <select name="color_gradient" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="from-blue-500 to-purple-600">Blue to Purple</option>
                            <option value="from-green-500 to-teal-600">Green to Teal</option>
                            <option value="from-orange-500 to-red-600">Orange to Red</option>
                            <option value="from-indigo-500 to-blue-600">Indigo to Blue</option>
                            <option value="from-purple-500 to-pink-600">Purple to Pink</option>
                            <option value="from-teal-500 to-cyan-600">Teal to Cyan</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('add-service-modal')" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                        Add Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Plan Modal -->
    <div id="edit-plan-modal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-md">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <span>Edit Pricing Plan</span>
                </h2>
                <button onclick="closeModal('edit-plan-modal')" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="upload-pricing.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="update_plan">
                <input type="hidden" name="plan_id" id="edit_plan_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Plan Name *</label>
                        <input type="text" name="name" id="edit_plan_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (RM) *</label>
                        <input type="number" name="price" id="edit_plan_price" step="0.01" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="edit_plan_description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe the pricing plan..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Features (comma-separated)</label>
                        <textarea name="features" id="edit_plan_features" rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Feature 1, Feature 2, Feature 3..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_popular" id="edit_plan_is_popular" class="mr-2">
                            <label for="edit_plan_is_popular" class="text-sm text-gray-700">Mark as Popular</label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('edit-plan-modal')" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Update Plan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div id="edit-service-modal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-green-600 p-2 rounded-md">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <span>Edit Service Pricing</span>
                </h2>
                <button onclick="closeModal('edit-service-modal')" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form action="upload-pricing.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="update_service">
                <input type="hidden" name="service_id" id="edit_service_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Service Name *</label>
                        <input type="text" name="service_name" id="edit_service_name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Icon Class</label>
                        <input type="text" name="icon_class" id="edit_service_icon_class" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="service_description" id="edit_service_description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe the service..."></textarea>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Gradient</label>
                        <select name="color_gradient" id="edit_service_color_gradient" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="from-blue-500 to-purple-600">Blue to Purple</option>
                            <option value="from-green-500 to-teal-600">Green to Teal</option>
                            <option value="from-orange-500 to-red-600">Orange to Red</option>
                            <option value="from-indigo-500 to-blue-600">Indigo to Blue</option>
                            <option value="from-purple-500 to-pink-600">Purple to Pink</option>
                            <option value="from-teal-500 to-cyan-600">Teal to Cyan</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal('edit-service-modal')" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Items Modal -->
    <div id="service-items-modal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-purple-600 p-2 rounded-md">
                        <i class="fas fa-list text-white"></i>
                    </div>
                    <div>
                        <span id="service_items_title">Service Items</span>
                        <p id="service_items_description" class="text-sm text-gray-600 font-normal"></p>
                    </div>
                </h2>
                <button onclick="closeModal('service-items-modal')" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Add New Item Form -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium mb-4">Add New Item</h3>
                <form action="upload-service-item.php" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="add_item">
                    <input type="hidden" name="service_id" id="add_item_service_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                            <input type="text" name="item_name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price (RM) *</label>
                            <input type="number" name="price" step="0.01" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="2" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Describe this service item..."></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">
                            Add Item
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Items List -->
            <div>
                <h3 class="text-lg font-medium mb-4">Current Items</h3>
                <div id="service-items-list" class="space-y-2">
                    <!-- Items will be loaded here dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Show selected tab content
            document.getElementById(tabName).classList.remove('hidden');
            
            // Add active class to clicked button
            event.target.classList.add('active', 'border-blue-500', 'text-blue-600');
            event.target.classList.remove('border-transparent', 'text-gray-500');
        }

        // Modal functionality
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Edit functions
        function editPlan(id) {
            // Fetch plan data via AJAX
            fetch(`get-plan-data.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const plan = data.plan;
                        document.getElementById('edit_plan_id').value = plan.id;
                        document.getElementById('edit_plan_name').value = plan.name;
                        document.getElementById('edit_plan_price').value = plan.price;
                        document.getElementById('edit_plan_description').value = plan.description;
                        document.getElementById('edit_plan_features').value = plan.features;
                        document.getElementById('edit_plan_is_popular').checked = plan.is_popular == 1;
                        openModal('edit-plan-modal');
                    } else {
                        showToast('Failed to load plan data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load plan data', 'error');
                });
        }

        function editService(id) {
            // Fetch service data via AJAX
            fetch(`get-service-data.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const service = data.service;
                        document.getElementById('edit_service_id').value = service.id;
                        document.getElementById('edit_service_name').value = service.service_name;
                        document.getElementById('edit_service_description').value = service.service_description;
                        document.getElementById('edit_service_icon_class').value = service.icon_class;
                        document.getElementById('edit_service_color_gradient').value = service.color_gradient;
                        openModal('edit-service-modal');
                    } else {
                        showToast('Failed to load service data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load service data', 'error');
                });
        }

        function manageServiceItems(id) {
            // Fetch service items via AJAX
            fetch(`get-service-items.php?service_id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const service = data.service;
                        const items = data.items;
                        
                        // Populate service info
                        document.getElementById('service_items_title').textContent = service.service_name;
                        document.getElementById('service_items_description').textContent = service.service_description;
                        
                        // Clear existing items
                        const itemsContainer = document.getElementById('service-items-list');
                        itemsContainer.innerHTML = '';
                        
                        // Add items to the list
                        items.forEach(item => {
                            const itemHtml = `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">${item.item_name}</h4>
                                        <p class="text-sm text-gray-600">${item.description || ''}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-blue-600">RM ${parseFloat(item.price).toFixed(2)}</span>
                                        <button onclick="editServiceItem(${item.id})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteServiceItem(${item.id})" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                            itemsContainer.innerHTML += itemHtml;
                        });
                        
                        // Store service ID for adding new items
                        document.getElementById('add_item_service_id').value = service.id;
                        
                        openModal('service-items-modal');
                    } else {
                        showToast('Failed to load service items', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Failed to load service items', 'error');
                });
        }
        
        function editServiceItem(itemId) {
            // TODO: Implement edit service item functionality
            alert('Edit service item functionality will be implemented');
        }
        
        function deleteServiceItem(itemId) {
            if (confirm('Are you sure you want to delete this item?')) {
                fetch(`delete-service-item.php?id=${itemId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast('Service item deleted successfully', 'success');
                            // Refresh the items list
                            const serviceId = document.getElementById('add_item_service_id').value;
                            manageServiceItems(serviceId);
                        } else {
                            showToast('Failed to delete service item', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Failed to delete service item', 'error');
                    });
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        });
    </script>
    
    <!-- Toast Message -->
    <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 pointer-events-none opacity-0 transition-opacity duration-300"></div>

    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;

            if (type === 'error') {
                toast.classList.remove('bg-green-600', 'bg-green-500');
                toast.classList.add('bg-red-600');
            } else {
                toast.classList.remove('bg-red-600');
                toast.classList.add('bg-green-500');
            }

            toast.classList.remove('opacity-0', 'pointer-events-none');
            toast.classList.add('opacity-100', 'pointer-events-auto');

            setTimeout(() => {
                toast.classList.remove('opacity-100', 'pointer-events-auto');
                toast.classList.add('opacity-0', 'pointer-events-none');
            }, 3000);
        }

        <?php if (isset($_GET['success'])): ?>
            <?php
            $success = $_GET['success'];
            switch ($success) {
                case 'plan_deleted':
                    echo "showToast('Pricing plan deleted successfully.', 'success');";
                    break;
                case 'service_deleted':
                    echo "showToast('Service pricing deleted successfully.', 'success');";
                    break;
                case 'status_updated':
                    echo "showToast('Status updated successfully.', 'success');";
                    break;
                case 'popular_updated':
                    echo "showToast('Popular status updated successfully.', 'success');";
                    break;
                case 'plan_added':
                    echo "showToast('Pricing plan added successfully.', 'success');";
                    break;
                case 'service_added':
                    echo "showToast('Service pricing added successfully.', 'success');";
                    break;
                case 'plan_updated':
                    echo "showToast('Pricing plan updated successfully.', 'success');";
                    break;
                case 'service_updated':
                    echo "showToast('Service pricing updated successfully.', 'success');";
                    break;
                case 'service_item_added':
                    echo "showToast('Service item added successfully.', 'success');";
                    break;
                case 'service_item_updated':
                    echo "showToast('Service item updated successfully.', 'success');";
                    break;
                default:
                    echo "showToast('Operation completed successfully.', 'success');";
            }
            ?>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            showToast("<?= htmlspecialchars($_GET['error']) ?>", "error");
        <?php endif; ?>
    </script>
</body>
</html> 