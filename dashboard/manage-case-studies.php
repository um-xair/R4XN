<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get project_id from URL parameter
$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    header("Location: manage-services.php");
    exit();
}

// Get project details
$project_query = "SELECT p.*, s.name as service_name FROM projects p 
                  LEFT JOIN services s ON p.service_id = s.id 
                  WHERE p.id = ?";
$project_stmt = $conn->prepare($project_query);
$project_stmt->bind_param("i", $project_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();
$project = $project_result->fetch_assoc();

if (!$project) {
    header("Location: manage-services.php");
    exit();
}

// Get all case studies for this project
$case_studies_query = "SELECT * FROM case_studies WHERE project_id = ? ORDER BY created_at DESC";
$case_studies_stmt = $conn->prepare($case_studies_query);
$case_studies_stmt->bind_param("i", $project_id);
$case_studies_stmt->execute();
$case_studies_result = $case_studies_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Case Studies - <?php echo htmlspecialchars($project['name']); ?> - R4XN Dashboard</title>
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
                        <button onclick="window.location.href='manage-projects.php?service_id=<?php echo $project['service_id']; ?>'" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                        </button>
                    </div>
                    <h1 class="text-2xl font-bold mb-1">Manage Case Studies - <?php echo htmlspecialchars($project['name']); ?></h1>
                    <p class="text-xs text-gray-400">Add and manage case studies for <?php echo htmlspecialchars($project['name']); ?> project</p>
                </div>
                <button onclick="openAddCaseStudyModal()" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    <i class="fas fa-plus mr-2"></i>Add New Case Study
                </button>
            </div>

            <!-- Case Studies Grid -->
            <div class="mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php while ($case_study = $case_studies_result->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col bg-white">
                        <div class="relative">
                            <img src="<?php echo !empty($case_study['hero_image_url']) && !filter_var($case_study['hero_image_url'], FILTER_VALIDATE_URL) ? htmlspecialchars($case_study['hero_image_url']) : htmlspecialchars($case_study['hero_image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($case_study['title']); ?>" 
                                 class="w-full h-48 object-cover rounded-md">
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $case_study['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst($case_study['status']); ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex-grow py-4">
                            <h3 class="text-lg font-semibold mb-2">
                                <?php echo htmlspecialchars($case_study['title']); ?>
                            </h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                <?php echo htmlspecialchars($case_study['description']); ?>
                            </p>
                            
                            <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
                                <span>Button: <?php echo htmlspecialchars($case_study['preview_button_text']); ?></span>
                                <?php if (!empty($case_study['preview_button_url'])): ?>
                                <a href="<?php echo htmlspecialchars($case_study['preview_button_url']); ?>" target="_blank" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-external-link-alt mr-1"></i>View
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <button onclick="editCaseStudy(<?php echo $case_study['id']; ?>)" 
                                    class="bg-blue-100 text-blue-700 px-4 py-3 rounded-md text-sm font-medium hover:bg-blue-200 transition-colors">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>
                            <button onclick="deleteCaseStudy(<?php echo $case_study['id']; ?>)" 
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

    <!-- Add Case Study Modal -->
    <div id="addCaseStudyModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/>
                            <circle cx="12" cy="13" r="3"/>
                        </svg>
                    </div>
                    Add New Case Study
                </h2>
                <button onclick="closeAddCaseStudyModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="addCaseStudyForm" action="upload-case-study.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                
                <div>
                    <label class="block mb-1 font-medium">Case Study Title</label>
                    <input type="text" name="title" required placeholder="Enter case study title" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" rows="4" required placeholder="Enter case study description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label for="caseStudyImageInput" class="block mb-1 font-medium">Upload Hero Image:</label>
                    <label for="caseStudyImageInput" id="caseStudyUploadBox"
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
                    <input type="file" name="image" id="caseStudyImageInput" accept="image/*" class="hidden" onchange="previewCaseStudyImage(this, 'caseStudyImagePreview')" />
                    <div id="caseStudyPreviewContainer" class="mt-4 hidden">
                        <img id="caseStudyImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                    </div>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Preview Button Text</label>
                    <input type="text" name="preview_button_text" value="Preview Project" placeholder="Enter button text" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Preview Button URL (optional)</label>
                    <input type="url" name="preview_button_url" placeholder="Enter button URL" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Features (comma separated)</label>
                    <textarea name="features" rows="3" placeholder="Enter features separated by commas (e.g., Payment Processing, Inventory Management, Customer Analytics)" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter features separated by commas. They will be displayed as colorful tags.</p>
                </div>
                
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Add Case Study</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Case Study Modal -->
    <div id="editCaseStudyModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center border-b pb-4 mb-6">
                <h2 class="text-xl font-semibold flex items-center gap-3">
                    <div class="bg-black p-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                            <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    Edit Case Study
                </h2>
                <button onclick="closeEditCaseStudyModal()" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <form id="editCaseStudyForm" action="upload-case-study.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" id="editCaseStudyId">
                <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
                
                <div>
                    <label class="block mb-1 font-medium">Case Study Title</label>
                    <input type="text" name="title" id="editCaseStudyTitle" required placeholder="Enter case study title" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Description</label>
                    <textarea name="description" id="editCaseStudyDescription" rows="4" required placeholder="Enter case study description" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Current Hero Image</label>
                    <img id="editCaseStudyCurrentImage" src="" alt="Current Image" class="w-48 h-32 object-cover rounded-md mb-4 hidden">
                    
                    <label for="editCaseStudyImageInput" class="block mb-1 font-medium">Upload New Hero Image (optional):</label>
                    <label for="editCaseStudyImageInput" id="editCaseStudyUploadBox"
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
                    <input type="file" name="image" id="editCaseStudyImageInput" accept="image/*" class="hidden" onchange="previewEditCaseStudyImage(this, 'editCaseStudyImagePreview')" />
                    <div id="editCaseStudyPreviewContainer" class="mt-4 hidden">
                        <img id="editCaseStudyImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                    </div>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Preview Button Text</label>
                    <input type="text" name="preview_button_text" id="editPreviewButtonText" placeholder="Enter button text" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Preview Button URL (optional)</label>
                    <input type="url" name="preview_button_url" id="editPreviewButtonUrl" placeholder="Enter button URL" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Features (comma separated)</label>
                    <textarea name="features" id="editFeatures" rows="3" placeholder="Enter features separated by commas (e.g., Payment Processing, Inventory Management, Customer Analytics)" class="border border-gray-300 rounded-md px-4 py-3 w-full"></textarea>
                    <p class="text-xs text-gray-500 mt-1">Enter features separated by commas. They will be displayed as colorful tags.</p>
                </div>
                
                <div>
                    <label class="block mb-1 font-medium">Status</label>
                    <select name="status" id="editCaseStudyStatus" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="pt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditCaseStudyModal()" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-md">Cancel</button>
                    <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Update Case Study</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddCaseStudyModal() {
            document.getElementById('addCaseStudyModal').classList.remove('hidden');
            document.getElementById('addCaseStudyModal').classList.add('flex');
        }
        
        function closeAddCaseStudyModal() {
            document.getElementById('addCaseStudyModal').classList.add('hidden');
            document.getElementById('addCaseStudyModal').classList.remove('flex');
        }
        
        function closeEditCaseStudyModal() {
            document.getElementById('editCaseStudyModal').classList.add('hidden');
            document.getElementById('editCaseStudyModal').classList.remove('flex');
        }
        
        function editCaseStudy(caseStudyId) {
            // Fetch case study data and populate the edit modal
            fetch(`get-case-study.php?id=${caseStudyId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const caseStudy = data.case_study;
                        const features = data.features;
                        
                        // Populate the edit form
                        document.getElementById('editCaseStudyId').value = caseStudy.id;
                        document.getElementById('editCaseStudyTitle').value = caseStudy.title;
                        document.getElementById('editCaseStudyDescription').value = caseStudy.description;
                        document.getElementById('editPreviewButtonText').value = caseStudy.preview_button_text || '';
                        document.getElementById('editPreviewButtonUrl').value = caseStudy.preview_button_url || '';
                        document.getElementById('editCaseStudyStatus').value = caseStudy.status;
                        document.getElementById('editFeatures').value = features.join(', ');
                        
                        // Show current image if exists
                        if (caseStudy.hero_image_url) {
                            document.getElementById('editCaseStudyCurrentImage').src = caseStudy.hero_image_url;
                            document.getElementById('editCaseStudyCurrentImage').classList.remove('hidden');
                        } else {
                            document.getElementById('editCaseStudyCurrentImage').classList.add('hidden');
                        }
                        
                        // Show the modal
                        document.getElementById('editCaseStudyModal').classList.remove('hidden');
                        document.getElementById('editCaseStudyModal').classList.add('flex');
                    } else {
                        alert('Error loading case study data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading case study data');
                });
        }
        
        function deleteCaseStudy(caseStudyId) {
            if (confirm('Are you sure you want to delete this case study?')) {
                window.location.href = `delete-case-study.php?id=${caseStudyId}&project_id=<?php echo $project_id; ?>`;
            }
        }

        // Close modal when clicking outside
        document.getElementById('addCaseStudyModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('addCaseStudyModal')) {
                closeAddCaseStudyModal();
            }
        });

        document.getElementById('editCaseStudyModal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('editCaseStudyModal')) {
                closeEditCaseStudyModal();
            }
        });

        // Image preview function for case study upload
        function previewCaseStudyImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('caseStudyPreviewContainer');
            const uploadBox = document.getElementById('caseStudyUploadBox');
            
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

        // Image preview function for edit case study upload
        function previewEditCaseStudyImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const previewContainer = document.getElementById('editCaseStudyPreviewContainer');
            const uploadBox = document.getElementById('editCaseStudyUploadBox');
            
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
