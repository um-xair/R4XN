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

// Get all projects for this service
$projects_query = "SELECT * FROM projects WHERE service_id = ? ORDER BY sort_order";
$projects_stmt = $conn->prepare($projects_query);
$projects_stmt->bind_param("i", $service_id);
$projects_stmt->execute();
$projects_result = $projects_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects - <?php echo htmlspecialchars($service['name']); ?> - R4XN Dashboard</title>
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
                    <div class="flex items-center gap-4 mb-2">
                        <button onclick="window.location.href='manage-services.php'" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Services
                        </button>
                    </div>
                    <h1 class="text-2xl font-bold mb-1">Manage Projects - <?php echo htmlspecialchars($service['name']); ?></h1>
                    <p class="text-xs text-gray-400">Add and manage projects for <?php echo htmlspecialchars($service['name']); ?> service</p>
                </div>
                <button onclick="openAddProjectModal()" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    <i class="fas fa-plus mr-2"></i>Add New Project
                </button>
            </div>

            <!-- Projects Grid -->
            <div class="mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php while ($project = $projects_result->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col bg-white">
                        <div class="relative">
                            <img src="<?php echo !empty($project['image_url']) && !filter_var($project['image_url'], FILTER_VALIDATE_URL) ? htmlspecialchars($project['image_url']) : htmlspecialchars($project['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($project['name']); ?>" 
                                 class="w-full h-48 object-cover rounded-md">
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $project['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst($project['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex-grow py-4">
                            <h3 class="text-lg font-semibold mb-2">
                                <?php echo htmlspecialchars($project['name']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($project['description']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Sort: <?php echo $project['sort_order']; ?></span>
                                <?php if (!empty($project['project_url'])): ?>
                                <a href="<?php echo htmlspecialchars($project['project_url']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-external-link-alt mr-1"></i>View
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-2">
                            <button onclick="viewCaseStudies(<?php echo $project['id']; ?>)" 
                                    class="bg-gray-100 text-gray-700 px-4 py-3 rounded-md text-sm font-medium hover:bg-gray-200 transition-colors">
                                <i class="fas fa-folder mr-2"></i>Case Studies
                            </button>
                            <button onclick="editProject(<?php echo $project['id']; ?>)" 
                                    class="bg-blue-100 text-blue-700 px-4 py-3 rounded-md text-sm font-medium hover:bg-blue-200 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>
                            <button onclick="deleteProject(<?php echo $project['id']; ?>)" 
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

    <!-- Add Project Modal -->
    <div id="addProjectModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                            <circle cx="12" cy="13" r="3"/>
                        </svg>
                    </div>
                    Add New Project
                </h2>
                <button onclick="closeAddProjectModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="addProjectForm" action="upload-project.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                
                <div>
                    <label class="block mb-1 font-medium">Project Name</label>
                    <input type="text" name="name" required placeholder="Enter project name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" rows="4" required placeholder="Enter project description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label for="projectImageInput" class="block mb-1 font-medium">Upload Project Image:</label>
                    <label for="projectImageInput" id="projectUploadBox"
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
                    <input type="file" name="image" id="projectImageInput" accept="image/*" class="hidden" onchange="previewProjectImage(this, 'projectImagePreview')" />
                    <div id="projectPreviewContainer" class="mt-4 hidden">
                        <img id="projectImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                    </div>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Project URL (optional)</label>
                    <input type="url" name="project_url" placeholder="Enter project URL" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Case Study URL (optional)</label>
                    <input type="url" name="case_study_url" placeholder="Enter case study URL" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Add Project</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Project Modal -->
    <div id="editProjectModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Edit Project
                </h2>
                <button onclick="closeEditProjectModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="editProjectForm" action="upload-project.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editProjectId">
                <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
                
                <div>
                    <label class="block mb-1 font-medium">Project Name</label>
                    <input type="text" name="name" id="editProjectName" required placeholder="Enter project name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" id="editProjectDescription" rows="4" required placeholder="Enter project description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Current Image</label>
                    <img id="editProjectCurrentImage" src="" alt="Current Image" class="w-48 h-32 object-cover rounded-md mb-4 hidden">
                    
                    <label for="editProjectImageInput" class="block mb-1 font-medium">Upload New Image (optional):</label>
                    <label for="editProjectImageInput" id="editProjectUploadBox"
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
                    <input type="file" name="image" id="editProjectImageInput" accept="image/*" class="hidden" onchange="previewEditProjectImage(this, 'editProjectImagePreview')" />
                    <div id="editProjectPreviewContainer" class="mt-4 hidden">
                        <img id="editProjectImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                    </div>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Project URL (optional)</label>
                    <input type="url" name="project_url" id="editProjectUrl" placeholder="Enter project URL" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Case Study URL (optional)</label>
                    <input type="url" name="case_study_url" id="editCaseStudyUrl" placeholder="Enter case study URL" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Sort Order</label>
                    <input type="number" name="sort_order" id="editProjectSortOrder" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Status</label>
                    <select name="status" id="editProjectStatus" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditProjectModal()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-md">Cancel</button>
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Update Project</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddProjectModal() {
            document.getElementById('addProjectModal').classList.remove('hidden');
            document.getElementById('addProjectModal').classList.add('flex');
        }
        
        function closeAddProjectModal() {
            document.getElementById('addProjectModal').classList.add('hidden');
            document.getElementById('addProjectModal').classList.remove('flex');
        }
        
        function closeEditProjectModal() {
            document.getElementById('editProjectModal').classList.add('hidden');
            document.getElementById('editProjectModal').classList.remove('flex');
        }
        
        function viewCaseStudies(projectId) {
            window.location.href = `manage-case-studies.php?project_id=${projectId}`;
        }
        
        function editProject(projectId) {
            // Fetch project data and populate the edit modal
            fetch(`get-project.php?id=${projectId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const project = data.project;
                        
                        // Populate the edit form
                        document.getElementById('editProjectId').value = project.id;
                        document.getElementById('editProjectName').value = project.name;
                        document.getElementById('editProjectDescription').value = project.description;
                        document.getElementById('editProjectUrl').value = project.project_url || '';
                        document.getElementById('editCaseStudyUrl').value = project.case_study_url || '';
                        document.getElementById('editProjectSortOrder').value = project.sort_order;
                        document.getElementById('editProjectStatus').value = project.status;
                        
                        // Show current image if exists
                        if (project.image_url) {
                            document.getElementById('editProjectCurrentImage').src = project.image_url;
                            document.getElementById('editProjectCurrentImage').classList.remove('hidden');
                        } else {
                            document.getElementById('editProjectCurrentImage').classList.add('hidden');
                        }
                        
                        // Show the modal
                        document.getElementById('editProjectModal').classList.remove('hidden');
                        document.getElementById('editProjectModal').classList.add('flex');
                    } else {
                        alert('Error loading project data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading project data');
                });
        }
        
        function deleteProject(projectId) {
            if (confirm('Are you sure you want to delete this project? This will also delete all associated case studies.')) {
                window.location.href = `delete-project.php?id=${projectId}&service_id=<?php echo $service_id; ?>`;
            }
        }

        // Close modal when clicking outside
        document.getElementById('addProjectModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('addProjectModal')) {
                closeAddProjectModal();
            }
        });

        document.getElementById('editProjectModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('editProjectModal')) {
                closeEditProjectModal();
            }
        });

        // Image preview function for project upload
        function previewProjectImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('projectPreviewContainer');
            const uploadBox = document.getElementById('projectUploadBox');
            
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

        // Image preview function for edit project upload
        function previewEditProjectImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('editProjectPreviewContainer');
            const uploadBox = document.getElementById('editProjectUploadBox');
            
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
