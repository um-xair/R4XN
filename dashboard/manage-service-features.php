<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get service_id from URL parameter
$service_id = $_GET['service_id'] ?? null;

if (!$service_id) {
    header("Location: manage-services.php");
    exit();
}

// Get service details
$service_query = "SELECT * FROM services WHERE id = ?";
$service_stmt = $conn->prepare($service_query);
$service_stmt->bind_param("i", $service_id);
$service_stmt->execute();
$service_result = $service_stmt->get_result();
$service = $service_result->fetch_assoc();

if (!$service) {
    header("Location: manage-services.php");
    exit();
}

// Get all features for this service
$features_query = "SELECT * FROM service_features WHERE service_id = ? ORDER BY sort_order";
$features_stmt = $conn->prepare($features_query);
$features_stmt->bind_param("i", $service_id);
$features_stmt->execute();
$features_result = $features_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Service Features - <?php echo htmlspecialchars($service['name']); ?> - R4XN Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        <?php include 'sidebar.php'; ?>
        
        <main class="flex-1 p-6 w-full overflow-y-auto min-w-0 relative">
            <!-- Toast Message -->
            <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 pointer-events-none opacity-0 transition-opacity duration-300"></div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4 sm:gap-0 px-2 sm:px-0">
                <div>
                    <h1 class="text-2xl font-bold mb-1">
                        Manage Service Features – <?php echo htmlspecialchars($service['name']); ?>
                    </h1>
                    <p class="text-xs text-gray-400">
                        Add and manage features for <?php echo htmlspecialchars($service['name']); ?> service
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <button onclick="window.location.href='manage-services.php'" class="bg-transparent border border-black text-black px-6 py-3 rounded-md">
                        Back to Services
                    </button>
                    <button onclick="openAddFeatureModal()" class="bg-black text-white px-6 py-3 rounded-md" type="button">
                        <i class="fas fa-plus mr-2"></i>Add New Feature
                    </button>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php while ($feature = $features_result->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col bg-white">
                        <div class="flex-grow py-4">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center <?php 
                                    $colorClass = $feature['color_class'];
                                    if (strpos($colorClass, 'blue') !== false) {
                                        echo 'bg-gradient-to-br from-blue-500 to-blue-600';
                                    } elseif (strpos($colorClass, 'green') !== false) {
                                        echo 'bg-gradient-to-br from-green-500 to-green-600';
                                    } elseif (strpos($colorClass, 'purple') !== false) {
                                        echo 'bg-gradient-to-br from-purple-500 to-purple-600';
                                    } elseif (strpos($colorClass, 'yellow') !== false) {
                                        echo 'bg-gradient-to-br from-yellow-500 to-yellow-600';
                                    } elseif (strpos($colorClass, 'red') !== false) {
                                        echo 'bg-gradient-to-br from-red-500 to-red-600';
                                    } elseif (strpos($colorClass, 'indigo') !== false) {
                                        echo 'bg-gradient-to-br from-indigo-500 to-indigo-600';
                                    } elseif (strpos($colorClass, 'pink') !== false) {
                                        echo 'bg-gradient-to-br from-pink-500 to-pink-600';
                                    } elseif (strpos($colorClass, 'orange') !== false) {
                                        echo 'bg-gradient-to-br from-orange-500 to-orange-600';
                                    } else {
                                        echo 'bg-gradient-to-br from-blue-500 to-blue-600';
                                    }
                                ?>">
                                    <?php echo $feature['icon_svg']; ?>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold">
                                        <?php echo htmlspecialchars($feature['feature_name']); ?>
                                    </h3>
                                    <span class="text-sm text-gray-500">Sort: <?php echo $feature['sort_order']; ?></span>
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($feature['feature_description']); ?>
                            </p>
                            
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-sm text-gray-600">
                                    Color: <?php 
                                        $colorName = explode(' ', $feature['color_class'])[0];
                                        $colorName = str_replace(['bg-', '-100'], '', $colorName);
                                        echo ucfirst($colorName);
                                    ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Edit Button -->
                            <button type="button" onclick="editFeature(<?php echo $feature['id']; ?>)" class="bg-black text-white py-3 rounded-md">
                                Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="delete-service-feature.php" method="GET" onsubmit="return confirm('Delete this feature?')" class="flex-1">
                                <input type="hidden" name="id" value="<?php echo $feature['id']; ?>">
                                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                                <button type="submit" class="w-full border border-gray-400 text-black py-3 rounded-md text-center">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Feature Modal -->
    <div id="addFeatureModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Add New Feature
                </h2>
                <button onclick="closeAddFeatureModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="addFeatureForm" action="upload-service-feature.php" method="POST" class="space-y-4">
                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                
                <div>
                    <label class="block mb-1 font-medium">Feature Name</label>
                    <input type="text" name="feature_name" required placeholder="Enter feature name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Feature Description</label>
                    <textarea name="feature_description" rows="4" required placeholder="Enter feature description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Icon SVG (optional)</label>
                    <textarea name="icon_svg" rows="3" placeholder="Enter SVG icon code" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter SVG icon code. Example: &lt;svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"&gt;&lt;path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/&gt;&lt;/svg&gt;</p>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Color Class</label>
                    <select name="color_class" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        <option value="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">Blue</option>
                        <option value="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">Green</option>
                        <option value="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">Purple</option>
                        <option value="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">Yellow</option>
                        <option value="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200">Red</option>
                        <option value="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">Indigo</option>
                        <option value="bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200">Pink</option>
                        <option value="bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200">Orange</option>
                    </select>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Add Feature</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Feature Modal -->
    <div id="editFeatureModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Edit Feature
                </h2>
                <button onclick="closeEditFeatureModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="editFeatureForm" action="upload-service-feature.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editFeatureId">
                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                
                <div>
                    <label class="block mb-1 font-medium">Feature Name</label>
                    <input type="text" name="feature_name" id="editFeatureName" required placeholder="Enter feature name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Feature Description</label>
                    <textarea name="feature_description" id="editFeatureDescription" rows="4" required placeholder="Enter feature description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Icon SVG (optional)</label>
                    <textarea name="icon_svg" id="editIconSvg" rows="3" placeholder="Enter SVG icon code" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter SVG icon code. Example: &lt;svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"&gt;&lt;path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/&gt;&lt;/svg&gt;</p>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Color Class</label>
                    <select name="color_class" id="editColorClass" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        <option value="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200">Blue</option>
                        <option value="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200">Green</option>
                        <option value="bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200">Purple</option>
                        <option value="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200">Yellow</option>
                        <option value="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-200">Red</option>
                        <option value="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200">Indigo</option>
                        <option value="bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200">Pink</option>
                        <option value="bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-200">Orange</option>
                    </select>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Sort Order</label>
                    <input type="number" name="sort_order" id="editSortOrder" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditFeatureModal()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-md">Cancel</button>
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Update Feature</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddFeatureModal() {
            document.getElementById('addFeatureModal').classList.remove('hidden');
            document.getElementById('addFeatureModal').classList.add('flex');
        }
        
        function closeAddFeatureModal() {
            document.getElementById('addFeatureModal').classList.add('hidden');
            document.getElementById('addFeatureModal').classList.remove('flex');
        }
        
        function closeEditFeatureModal() {
            document.getElementById('editFeatureModal').classList.add('hidden');
            document.getElementById('editFeatureModal').classList.remove('flex');
        }
        
        function editFeature(featureId) {
            // Fetch feature data and populate the edit modal
            fetch(`get-service-feature.php?id=${featureId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const feature = data.feature;
                        
                        // Populate the edit form
                        document.getElementById('editFeatureId').value = feature.id;
                        document.getElementById('editFeatureName').value = feature.feature_name;
                        document.getElementById('editFeatureDescription').value = feature.feature_description;
                        document.getElementById('editIconSvg').value = feature.icon_svg || '';
                        document.getElementById('editColorClass').value = feature.color_class;
                        document.getElementById('editSortOrder').value = feature.sort_order;
                        
                        // Show the modal
                        document.getElementById('editFeatureModal').classList.remove('hidden');
                        document.getElementById('editFeatureModal').classList.add('flex');
                    } else {
                        alert('Error loading feature data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading feature data');
                });
        }
        
        function deleteFeature(featureId) {
            if (confirm('Are you sure you want to delete this feature?')) {
                window.location.href = `delete-service-feature.php?id=${featureId}&service_id=<?php echo $service_id; ?>`;
            }
        }

        // Close modal when clicking outside
        document.getElementById('addFeatureModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('addFeatureModal')) {
                closeAddFeatureModal();
            }
        });

        document.getElementById('editFeatureModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('editFeatureModal')) {
                closeEditFeatureModal();
            }
        });

        // Toast message functions
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = type === 'success' ? '✓' : '✕';
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            
            toast.innerHTML = `<span class="text-lg">${icon}</span><span>${message}</span>`;
            toast.className = `fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 pointer-events-none opacity-0 transition-opacity duration-300 ${bgColor}`;
            
            // Show toast
            setTimeout(() => {
                toast.classList.remove('opacity-0');
                toast.classList.add('opacity-100');
            }, 100);
            
            // Hide toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
            }, 3000);
        }

        // Show toast on page load if there are session messages
        document.addEventListener('DOMContentLoaded', function() {
            // Check for session messages and show toast
            <?php if (isset($_SESSION['success'])): ?>
                showToast('<?php echo addslashes($_SESSION['success']); ?>', 'success');
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                showToast('<?php echo addslashes($_SESSION['error']); ?>', 'error');
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>
