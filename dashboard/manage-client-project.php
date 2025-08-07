<?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', 'dashboard_error.log');

    // Session management
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    include 'config.php';
    
    // Log page access
    error_log("Manage Client Projects page accessed at " . date('Y-m-d H:i:s'));

    // Handle delete
    if (isset($_GET['delete'])) {
        $id = intval($_GET['delete']);
        error_log("Delete request for project ID: " . $id);

        // Get image path from DB
        $stmt = $conn->prepare("SELECT image_path FROM client_projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($path);
        $stmt->fetch();
        $stmt->close();

        // Delete file from filesystem
        if ($path && file_exists($path)) {
            unlink($path);
            error_log("Deleted image file: " . $path);
        }

        // Delete from DB
        $stmt = $conn->prepare("DELETE FROM client_projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        error_log("Project deleted successfully: " . $id);
        header("Location: manage-client-project.php?deleted=1");
        exit;
    }

    // Handle status toggle
    if (isset($_GET['toggle_status'])) {
        $id = intval($_GET['toggle_status']);
        error_log("Status toggle request for project ID: " . $id);
        
        $stmt = $conn->prepare("UPDATE client_projects SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        error_log("Status updated successfully for project ID: " . $id);
        header("Location: manage-client-project.php?status_updated=1");
        exit;
    }

    // Handle featured toggle
    if (isset($_GET['toggle_featured'])) {
        $id = intval($_GET['toggle_featured']);
        error_log("Featured toggle request for project ID: " . $id);
        
        $stmt = $conn->prepare("UPDATE client_projects SET featured = NOT featured WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        error_log("Featured status updated successfully for project ID: " . $id);
        header("Location: manage-client-project.php?featured_updated=1");
        exit;
    }

    // Get projects
    $result = $conn->query("SELECT * FROM client_projects ORDER BY sort_order ASC, created_at DESC");
    $projects = $result->fetch_all(MYSQLI_ASSOC);
    error_log("Retrieved " . count($projects) . " projects from database");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no" />
<title>Manage Client Projects - Dashboard</title>
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
                    <h1 class="text-2xl font-bold mb-1">Manage Client Projects</h1>
                    <p class="text-xs text-gray-400">Add, edit, and manage client project details and showcase</p>
                </div>
                <button id="openAddModalBtn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    <i class="fas fa-plus mr-2"></i>Add New Project
                </button>
            </div>
            
            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($projects as $project): ?>
                    <div class="bg-white rounded-lg overflow-hidden border border-gray-200">
                        <!-- Project Image -->
                        <div class="relative h-auto aspect-[1/1] overflow-hidden">
                            <img src="<?= htmlspecialchars($project['image_path']) ?>" 
                                 alt="<?= htmlspecialchars($project['title']) ?>" 
                                 class="w-full h-full object-cover">
                            
                            <!-- Status Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="px-6 py-2 text-xs rounded-full <?= $project['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= ucfirst($project['status']) ?>
                                </span>
                            </div>
                            
                            <!-- Featured Badge -->
                            <?php if ($project['featured']): ?>
                                <div class="absolute top-4 right-4">
                                    <span class="px-6 py-2 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-star mr-1"></i>Featured
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Project Details -->
                        <div class="p-4">
                            <h3 class="font-semibold text-lg mb-2 text-gray-900"><?= htmlspecialchars($project['title']) ?></h3>
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?= htmlspecialchars($project['description']) ?></p>
                            
                            <!-- Project Info -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-20">Type:</span>
                                    <span class="font-medium"><?= htmlspecialchars($project['website_type']) ?></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-20">Client:</span>
                                    <span class="font-medium"><?= htmlspecialchars($project['client_name']) ?></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-20">Timeline:</span>
                                    <span class="font-medium"><?= htmlspecialchars($project['timeline']) ?></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-20">Website:</span>
                                    <a href="<?= htmlspecialchars($project['website_url']) ?>" 
                                       target="_blank" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">
                                        <?= htmlspecialchars(parse_url($project['website_url'], PHP_URL_HOST) ?: $project['website_url']) ?>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <button class="editBtn bg-blue-600 text-white py-3 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors flex-1 flex items-center justify-center"
                                        data-id="<?= $project['id'] ?>"
                                        data-title="<?= htmlspecialchars($project['title']) ?>"
                                        data-description="<?= htmlspecialchars($project['description']) ?>"
                                        data-websitetype="<?= htmlspecialchars($project['website_type']) ?>"
                                        data-clientname="<?= htmlspecialchars($project['client_name']) ?>"
                                        data-timeline="<?= htmlspecialchars($project['timeline']) ?>"
                                        data-websiteurl="<?= htmlspecialchars($project['website_url']) ?>"
                                        data-imagepath="<?= htmlspecialchars($project['image_path']) ?>"
                                        data-featured="<?= $project['featured'] ?>"
                                        data-status="<?= $project['status'] ?>">
                                    <i class="fas fa-edit mr-2"></i>Edit
                                </button>
                                
                                <a href="?toggle_status=<?= $project['id'] ?>" 
                                   class="border border-gray-300 text-gray-700 py-3 px-4 rounded-lg text-sm font-medium text-center hover:bg-gray-50 transition-colors flex-1 flex items-center justify-center">
                                    <i class="fas fa-toggle-<?= $project['status'] === 'active' ? 'on' : 'off' ?> mr-2"></i><?= $project['status'] === 'active' ? 'Active' : 'Inactive' ?>
                                </a>
                                
                                <a href="?toggle_featured=<?= $project['id'] ?>" 
                                   class="border border-yellow-300 text-yellow-700 py-3 px-4 rounded-lg text-sm font-medium text-center hover:bg-yellow-50 transition-colors flex-1 flex items-center justify-center">
                                    <i class="fas fa-star mr-2"></i>Featured
                                </a>
                            </div>
                            
                            <div class="flex gap-2 mt-2">
                                <a href="?delete=<?= $project['id'] ?>" 
                                   onclick="return confirm('Are you sure you want to delete this project?')" 
                                   class="border border-red-300 text-red-700 py-3 px-4 rounded-lg text-sm font-medium text-center w-full hover:bg-red-50 transition-colors flex items-center justify-center">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Add/Edit Modal -->
            <div id="projectModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-md p-8 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
                    <div class="flex justify-between items-center border-b pb-4 mb-6">
                        <h2 class="text-xl font-semibold flex items-center gap-3">
                            <div class="bg-black p-2 rounded-md">
                                <i class="fas fa-project-diagram text-white"></i>
                            </div>
                            <span id="modalTitle">Add New Project</span>
                        </h2>
                        <button id="closeModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <form method="POST" action="upload-client-project.php" enctype="multipart/form-data" id="projectForm" class="space-y-6">
                        <input type="hidden" name="project_id" id="projectId">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Project Title *</label>
                                <input type="text" name="title" id="title" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="website_type" class="block text-sm font-medium text-gray-700 mb-2">Website Type *</label>
                                <select name="website_type" id="website_type" required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Type</option>
                                    <option value="E-Commerce">E-Commerce</option>
                                    <option value="Corporate">Corporate</option>
                                    <option value="Portfolio">Portfolio</option>
                                    <option value="Blog">Blog</option>
                                    <option value="Restaurant">Restaurant</option>
                                    <option value="Landing Page">Landing Page</option>
                                    <option value="Web Application">Web Application</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">Client Name *</label>
                                <input type="text" name="client_name" id="client_name" required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div>
                                <label for="timeline" class="block text-sm font-medium text-gray-700 mb-2">Timeline *</label>
                                <input type="text" name="timeline" id="timeline" required 
                                       placeholder="e.g., Jan 2025 – Mar 2025"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="website_url" class="block text-sm font-medium text-gray-700 mb-2">Website URL *</label>
                                <input type="url" name="website_url" id="website_url" required 
                                       placeholder="https://example.com"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Project Description *</label>
                                <textarea name="description" id="description" rows="4" required 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Describe the project, features, technologies used, etc."></textarea>
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" id="status" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="featured" class="block text-sm font-medium text-gray-700 mb-2">Featured</label>
                                <select name="featured" id="featured" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Project Image *</label>
                                <input type="file" name="image" id="image" accept="image/*,.jpg,.jpeg,.png,.gif,.webp,.bmp,.tiff,.svg" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: JPG, JPEG, PNG, GIF, WebP, BMP, TIFF, SVG. Recommended size: 800x600px or larger</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" id="cancelBtn" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit" class="bg-black text-white px-6 py-2 rounded-md hover:bg-gray-800">
                                <span id="submitBtnText">Add Project</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </main>
            
        <script>
            // Modal functionality
            const modal = document.getElementById('projectModal');
            const openBtn = document.getElementById('openAddModalBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const modalTitle = document.getElementById('modalTitle');
            const submitBtnText = document.getElementById('submitBtnText');
            const projectForm = document.getElementById('projectForm');
            const projectId = document.getElementById('projectId');
            
            // Form fields
            const titleField = document.getElementById('title');
            const descriptionField = document.getElementById('description');
            const websiteTypeField = document.getElementById('website_type');
            const clientNameField = document.getElementById('client_name');
            const timelineField = document.getElementById('timeline');
            const websiteUrlField = document.getElementById('website_url');
            const statusField = document.getElementById('status');
            const featuredField = document.getElementById('featured');
            const imageField = document.getElementById('image');
            
            // Open modal for adding new project
            openBtn.addEventListener('click', () => {
                modalTitle.textContent = 'Add New Project';
                submitBtnText.textContent = 'Add Project';
                projectForm.reset();
                projectId.value = '';
                modal.classList.remove('hidden');
            });
            
            // Close modal
            function closeModal() {
                modal.classList.add('hidden');
                projectForm.reset();
            }
            
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);
            
            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
            
            // Edit project functionality
            document.querySelectorAll('.editBtn').forEach(button => {
                button.addEventListener('click', () => {
                    const data = button.dataset;
                    
                    modalTitle.textContent = 'Edit Project';
                    submitBtnText.textContent = 'Update Project';
                    
                    projectId.value = data.id;
                    titleField.value = data.title;
                    descriptionField.value = data.description;
                    websiteTypeField.value = data.websitetype;
                    clientNameField.value = data.clientname;
                    timelineField.value = data.timeline;
                    websiteUrlField.value = data.websiteurl;
                    statusField.value = data.status;
                    featuredField.value = data.featured === '1' ? '1' : '0';
                    
                    modal.classList.remove('hidden');
                });
            });
        </script>
    
        <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 bg-green-500 pointer-events-none opacity-0"></div>

    </div>

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

            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-full');
            toast.classList.add('opacity-100', 'pointer-events-auto');

            setTimeout(() => {
                toast.classList.remove('opacity-100', 'pointer-events-auto');
                toast.classList.add('opacity-0', 'pointer-events-none', 'translate-x-full');
            }, 3000);
        }

        <?php if (isset($_GET['deleted'])): ?>
            showToast("Project deleted successfully.", "success");
        <?php elseif (isset($_GET['status_updated'])): ?>
            showToast("Project status updated successfully.", "success");
        <?php elseif (isset($_GET['featured_updated'])): ?>
            showToast("Project featured status updated successfully.", "success");
        <?php elseif (isset($_GET['added'])): ?>
            showToast("Project added successfully.", "success");
        <?php elseif (isset($_GET['updated'])): ?>
            showToast("Project updated successfully.", "success");
        <?php elseif (isset($_GET['error'])): ?>
            showToast("<?= htmlspecialchars($_GET['error']) ?>", "error");
        <?php endif; ?>
    </script>

</body>
</html> 