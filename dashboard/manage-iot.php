<?php
include 'config.php';

$projects = $conn->query("SELECT * FROM iot_projects ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no" />
    <title>Dashboard</title>
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

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-2xl font-bold mb-1">Manage IoT Projects</h1>
                    <p class="text-xs text-gray-400">Upload new IoT projects — all your IoT portfolio management in one place</p>
                </div>
                <button id="openIotModalBtn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Upload IoT Project
                </button>
            </div>

            <!-- Modal Overlay -->
            <div id="iotModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
					<div class="flex justify-between items-center border-b pb-4 mb-6">
                	    <h2 class="text-xl font-semibold flex items-center gap-3">
                	        <div class="bg-black p-2 rounded-md">
                	            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
                	                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                	                <circle cx="9" cy="7" r="4"/>
                	                <line x1="19" x2="19" y1="8" y2="14"/>
                	                <line x1="22" x2="16" y1="11" y2="11"/>
                	            </svg>
                	        </div>
                	        Upload IoT Project
                	    </h2>
                	    <button id="closeIotModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                	        <i class="fa-solid fa-xmark"></i>
                	    </button>
                	</div>
                    <form action="upload-iot.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
							<label class="block mb-1 font-medium">Title</label>
                            <input type="text" name="title" required placeholder="Project Name" class="border p-2 rounded w-full">
						</div>
						<div>
							<label class="block mb-1 font-medium">Link URL</label>
                            <input type="url" name="link_url" required placeholder="Project Link" class="border p-2 rounded w-full">
						</div>
						<div>
						    <label for="iotImageInput" class="block mb-1 font-medium">Upload New Image:</label>
						    <label for="iotImageInput" id="iotUploadBox"
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
						    <input type="file" name="image" id="iotImageInput" accept="image/*" class="hidden" required onchange="previewImage(this, 'iotImagePreview')" />
						    <div id="iotPreviewContainer" class="mt-4 hidden">
						        <img id="iotImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
						    </div>
						</div>

						<div class="pt-4 flex justify-end">
                        	<button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Upload Project</button>
						</div>
                    </form>
                </div>
            </div>

            <!-- Projects List -->
            <div class="mx-auto">
			    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
			        <?php while ($project = $projects->fetch_assoc()): ?>
			            <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col">
			                <!-- Image wrapped in link -->
			                <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" rel="noopener noreferrer">
			                    <img src="iot/<?= htmlspecialchars(basename($project['image_path'])) ?>" 
			                         class="w-full h-full object-cover rounded-md" alt="<?= htmlspecialchars($project['title']) ?>">
			                </a>
			                <!-- Text content -->
			                <div class="flex-grow py-4">
			                    <h3 class="text-lg font-semibold"><?= htmlspecialchars($project['title']) ?></h3>
			                    <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" rel="noopener noreferrer" 
			                       class="text-sm text-blue-400 underline break-all">
			                        <?= htmlspecialchars($project['link_url']) ?>
			                    </a>
			                </div>
			                <!-- Buttons -->
			                <div class="grid grid-cols-2 gap-2">
			                    <button type="button" onclick='openEditModal(<?= json_encode($project) ?>)' 
			                            class="bg-black text-white py-3 rounded-md">
			                        Edit
			                    </button>
			                    <form method="POST" action="delete-iot.php" onsubmit="return confirm('Are you sure you want to delete this project?');">
			                        <input type="hidden" name="id" value="<?= $project['id'] ?>">
			                        <button type="submit" class="w-full border border-gray-400 text-black py-3 rounded-md text-center">
			                            Delete
			                        </button>
			                    </form>
			                </div>
			            </div>
			        <?php endwhile; ?>
			    </div>
			</div>

			<!-- Edit IoT Project Modal -->
			<div id="editIotModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
			    <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
			        <div class="flex justify-between items-center border-b pb-4 mb-6">
			            <h2 class="text-xl font-semibold flex items-center gap-3">
			                <div class="bg-black p-2 rounded-md">
			                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white" viewBox="0 0 24 24">
			                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
			                        <circle cx="9" cy="7" r="4"/>
			                        <line x1="19" x2="19" y1="8" y2="14"/>
			                        <line x1="22" x2="16" y1="11" y2="11"/>
			                    </svg>
			                </div>
			                Edit IoT Project
			            </h2>
			            <button id="closeEditIotModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
			                <i class="fa-solid fa-xmark"></i>
			            </button>
			        </div>

			        <form id="editIotForm" action="update-iot.php" method="POST" enctype="multipart/form-data" class="space-y-4">
			            <input type="hidden" name="id" id="editProjectId" required>

			            <div>
			                <label for="editTitle" class="block mb-1 font-medium">Title</label>
			                <input type="text" name="title" id="editTitle" required placeholder="Project Name" class="border border-gray-300 rounded-md px-4 py-3 w-full">
			            </div>

			            <div>
			                <label for="editLinkUrl" class="block mb-1 font-medium">Link URL</label>
			                <input type="url" name="link_url" id="editLinkUrl" required placeholder="Project Link" class="border border-gray-300 rounded-md px-4 py-3 w-full">
			            </div>

			            <div>
			                <label for="editImage" class="block mb-1 font-medium">Upload New Image (optional)</label>
			                <label for="editImage" id="editUploadBox" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
			                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
			                        <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
			                        <path d="m14 19.5 3-3 3 3"/>
			                        <path d="M17 22v-5.5"/>
			                        <circle cx="9" cy="9" r="2"/>
			                    </svg>
			                    <span>Upload New Image Here</span>
			                </label>
			                <input type="file" name="image" id="editImage" accept="image/*" class="hidden" onchange="previewImage(this, 'editIotImagePreview', 'editIotPreviewContainer', 'editUploadBox')">
			                <div id="editIotPreviewContainer" class="mt-4 hidden">
			                    <img id="editIotImagePreview" src="" alt="Preview" class="w-full rounded-md">
			                </div>
			            </div>

			            <div class="pt-4 flex justify-end">
			                <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Save Changes</button>
			            </div>
			        </form>
			    </div>
			</div>

			<script>
			    const editModal = document.getElementById('editIotModal');
			    const closeEditBtn = document.getElementById('closeEditIotModalBtn');
			    const cancelEditBtn = document.getElementById('cancelEditIotBtn');

			    function openEditModal(project) {
			        editModal.classList.remove('hidden');
			        editModal.classList.add('flex');
				
			        document.getElementById('editProjectId').value = project.id;
			        document.getElementById('editTitle').value = project.title;
			        document.getElementById('editLinkUrl').value = project.link_url;
				
			        // Set preview image src and show preview container
			        const previewContainer = document.getElementById('editIotPreviewContainer');
			        const previewImg = document.getElementById('editIotImagePreview');
			        previewImg.src = 'iot/' + project.image_path.split('/').pop();  // Adjust path as needed
			        previewContainer.classList.remove('hidden');
				
			        // Reset file input & show upload box
			        document.getElementById('editImage').value = '';
			        document.getElementById('editUploadBox').classList.remove('hidden');
			    }
			
			    closeEditBtn.addEventListener('click', () => {
			        editModal.classList.add('hidden');
			        editModal.classList.remove('flex');
			    });
			
			    cancelEditBtn.addEventListener('click', () => {
			        editModal.classList.add('hidden');
			        editModal.classList.remove('flex');
			    });
			
			    function previewImage(input, previewImgId, previewContainerId, uploadBoxId) {
			        const previewImg = document.getElementById(previewImgId);
			        const previewContainer = document.getElementById(previewContainerId);
			        const uploadBox = document.getElementById(uploadBoxId);
				
			        if (input.files && input.files[0]) {
			            const reader = new FileReader();
			            reader.onload = e => {
			                previewImg.src = e.target.result;
			                previewContainer.classList.remove('hidden');
			                uploadBox.classList.add('hidden');
			            };
			            reader.readAsDataURL(input.files[0]);
			        } else {
			            previewImg.src = '';
			            previewContainer.classList.add('hidden');
			            uploadBox.classList.remove('hidden');
			        }
			    }
			</script>


        </main>

    </div>

    <script>
        const openBtn = document.getElementById('openIotModalBtn');
        const closeBtn = document.getElementById('closeIotModalBtn');
        const modal = document.getElementById('iotModal');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });

        // Image preview function
        function previewImage(input, previewId) {
		    const preview = document.getElementById(previewId);
		    const previewContainer = preview.parentElement;
		    if (input.files && input.files[0]) {
		        const reader = new FileReader();
		        reader.onload = e => {
		            preview.src = e.target.result;
		            preview.classList.remove('hidden');
		            previewContainer.classList.remove('hidden');
		        }
		        reader.readAsDataURL(input.files[0]);
		    } else {
		        preview.src = '';
		        preview.classList.add('hidden');
		        previewContainer.classList.add('hidden');
		    }
		}
    </script>
</body>
</html>