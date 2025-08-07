<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get all services with their project counts
$services_query = "SELECT s.*, COUNT(p.id) as project_count 
                  FROM services s 
                  LEFT JOIN projects p ON s.id = p.service_id 
                  GROUP BY s.id 
                  ORDER BY s.sort_order";
$services_result = $conn->query($services_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services - R4XN Dashboard</title>
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
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                        </div>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-2xl font-bold mb-1">Manage Services</h1>
                    <p class="text-xs text-gray-400">Manage your services, projects, and case studies — all your service portfolio management in one place</p>
                </div>
                <button onclick="openAddServiceModal()" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    <i class="fas fa-plus mr-2"></i>Add New Service
                </button>
            </div>

            <!-- Services Grid -->
            <div class="mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php while ($service = $services_result->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col bg-white">
                        <div class="relative">
                            <img src="<?php echo !empty($service['image_url']) && !filter_var($service['image_url'], FILTER_VALIDATE_URL) ? htmlspecialchars($service['image_url']) : htmlspecialchars($service['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($service['name']); ?>" 
                                 class="w-full h-48 object-cover rounded-md">
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $service['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst($service['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex-grow py-4">
                            <h3 class="text-lg font-semibold mb-2">
                                <?php echo htmlspecialchars($service['name']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($service['description']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span><?php echo $service['project_count']; ?> Projects</span>
                                <span>Slug: <?php echo htmlspecialchars($service['slug']); ?></span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <button onclick="viewProjects(<?php echo $service['id']; ?>)" 
                                    class="bg-gray-100 text-gray-700 px-4 py-3 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                                <i class="fas fa-folder mr-2"></i>Projects
                            </button>
                            <button onclick="editService(<?php echo $service['id']; ?>)" 
                                    class="bg-blue-100 text-blue-700 px-4 py-3 rounded-md text-sm font-medium hover:bg-blue-200 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>
                            <button onclick="deleteService(<?php echo $service['id']; ?>)" 
                                    class="bg-red-100 text-red-700 px-4 py-3 rounded-md text-sm font-medium hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Service Modal -->
    <div id="addServiceModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Add New Service
                </h2>
                <button onclick="closeAddServiceModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="addServiceForm" action="upload-service.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block mb-1 font-medium">Service Name</label>
                    <input type="text" name="name" required placeholder="Enter service name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Slug</label>
                    <input type="text" name="slug" required placeholder="Enter URL slug" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" rows="4" required placeholder="Enter service description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label for="serviceImageInput" class="block mb-1 font-medium">Upload Service Image:</label>
                    <label for="serviceImageInput" id="serviceUploadBox"
                        class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
                            <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                            <path d="m14 19.5 3-3 3 3"/>
                            <path d="M17 22v-5.5"/>
                            <circle cx="9" cy="9" r="2"/>
                        </svg>
                        <span>Upload Image Here</span>
                    </label>
                    <input type="file" name="image" id="serviceImageInput" accept="image/*" class="hidden" onchange="previewServiceImage(this, 'serviceImagePreview')" />
                    <div id="servicePreviewContainer" class="mt-4 hidden">
                        <img id="serviceImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                    </div>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Add Service</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div id="editServiceModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Edit Service
                </h2>
                <button onclick="closeEditServiceModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="editServiceForm" action="upload-service.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editServiceId">
                
                <div>
                    <label class="block mb-1 font-medium">Service Name</label>
                    <input type="text" name="name" id="editServiceName" required placeholder="Enter service name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Slug</label>
                    <input type="text" name="slug" id="editServiceSlug" required placeholder="Enter URL slug" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" id="editServiceDescription" rows="4" required placeholder="Enter service description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Current Image</label>
                    <img id="editServiceCurrentImage" src="" alt="Current Image" class="w-48 h-32 object-cover rounded-md mb-4 hidden">
                    
                    <label for="editServiceImageInput" class="block mb-1 font-medium">Upload New Image (optional):</label>
                    <label for="editServiceImageInput" id="editServiceUploadBox"
                        class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
                            <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                            <path d="m14 19.5 3-3 3 3"/>
                            <path d="M17 22v-5.5"/>
                            <circle cx="9" cy="9" r="2"/>
                        </svg>
                        <span>Upload New Image Here</span>
                    </label>
                    <input type="file" name="image" id="editServiceImageInput" accept="image/*" class="hidden" onchange="previewEditServiceImage(this, 'editServiceImagePreview')" />
                    <div id="editServicePreviewContainer" class="mt-4 hidden">
                        <img id="editServiceImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                    </div>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Sort Order</label>
                    <input type="number" name="sort_order" id="editServiceSortOrder" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Status</label>
                    <select name="status" id="editServiceStatus" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditServiceModal()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-md">Cancel</button>
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Update Service</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddServiceModal() {
            document.getElementById('addServiceModal').classList.remove('hidden');
            document.getElementById('addServiceModal').classList.add('flex');
        }
        
        function closeAddServiceModal() {
            document.getElementById('addServiceModal').classList.add('hidden');
            document.getElementById('addServiceModal').classList.remove('flex');
        }
        
        function closeEditServiceModal() {
            document.getElementById('editServiceModal').classList.add('hidden');
            document.getElementById('editServiceModal').classList.remove('flex');
        }
        
        function viewProjects(serviceId) {
            window.location.href = `manage-projects.php?service_id=${serviceId}`;
        }
        
        function editService(serviceId) {
            // Fetch service data and populate the edit modal
            fetch(`get-service.php?id=${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const service = data.service;
                        
                        // Populate the edit form
                        document.getElementById('editServiceId').value = service.id;
                        document.getElementById('editServiceName').value = service.name;
                        document.getElementById('editServiceSlug').value = service.slug;
                        document.getElementById('editServiceDescription').value = service.description;
                        document.getElementById('editServiceSortOrder').value = service.sort_order;
                        document.getElementById('editServiceStatus').value = service.status;
                        
                        // Show current image if exists
                        if (service.image_url) {
                            document.getElementById('editServiceCurrentImage').src = service.image_url;
                            document.getElementById('editServiceCurrentImage').classList.remove('hidden');
                        } else {
                            document.getElementById('editServiceCurrentImage').classList.add('hidden');
                        }
                        
                        // Show the modal
                        document.getElementById('editServiceModal').classList.remove('hidden');
                        document.getElementById('editServiceModal').classList.add('flex');
                    } else {
                        alert('Error loading service data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading service data');
                });
        }
        
        function deleteService(serviceId) {
            if (confirm('Are you sure you want to delete this service? This will also delete all associated projects and case studies.')) {
                window.location.href = `delete-service.php?id=${serviceId}`;
            }
        }

        // Close modal when clicking outside
        document.getElementById('addServiceModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('addServiceModal')) {
                closeAddServiceModal();
            }
        });

        document.getElementById('editServiceModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('editServiceModal')) {
                closeEditServiceModal();
            }
        });

        // Image preview function for service upload
        function previewServiceImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('servicePreviewContainer');
            const uploadBox = document.getElementById('serviceUploadBox');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadBox.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                previewContainer.classList.add('hidden');
                uploadBox.classList.remove('hidden');
            }
        }

        // Image preview function for edit service upload
        function previewEditServiceImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('editServicePreviewContainer');
            const uploadBox = document.getElementById('editServiceUploadBox');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                    uploadBox.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                previewContainer.classList.add('hidden');
                uploadBox.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>
