<?php
    // Set session configuration for cross-subdomain access
    ini_set('session.cookie_domain', '.r4xn.com');
    ini_set('session.cookie_path', '/');
    ini_set('session.cookie_secure', false);
    ini_set('session.cookie_httponly', false);
    ini_set('session.cookie_samesite', 'None');

    // Use custom session name to avoid conflicts
    session_name('erp_session');
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
    
    include 'config.php';
    $developers = $conn->query("SELECT * FROM developers ORDER BY developers.created_at DESC");
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
                    <h1 class="text-2xl font-bold mb-1">Manage Developers</h1>
                    <p class="text-xs text-gray-400">Add, edit, or remove developer profiles — keep your team’s portfolio details up to date here</p>
                </div>
                <button id="openDevModalBtn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Add developers
                </button>
            </div>

            <div id="devModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Add Developer
                        </h2>
                        <button id="closeDevModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form action="upload-dev.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <div>
                            <label class="block mb-1 font-medium">Developer Name</label>
                            <input type="text" name="name" required placeholder="Vertical Name (e.g., DEVXAIR)" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Portfolio Link</label>
                            <input type="url" name="link" required placeholder="Portfolio Link" class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>
                        <div>
                            <label for="devImageInput" class="block mb-1 font-medium">Upload New Image:</label>
                            <label for="devImageInput" id="devUploadBox" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
                                    <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                    <path d="m14 19.5 3-3 3 3"/>
                                    <path d="M17 22v-5.5"/>
                                    <circle cx="9" cy="9" r="2"/>
                                </svg>
                                <span>Upload Image Here</span>
                            </label>
                            <input type="file" name="image" id="devImageInput" accept="image/*" class="hidden" required>
                            <div id="devPreviewContainer" class="mt-4 hidden">
                                <img id="devImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                const devModal = document.getElementById('devModal');
                const openDevBtn = document.getElementById('openDevModalBtn');
                const closeDevBtn = document.getElementById('closeDevModalBtn');

                openDevBtn.addEventListener('click', () => devModal.classList.remove('hidden'));
                closeDevBtn.addEventListener('click', () => devModal.classList.add('hidden'));

                devModal.addEventListener('click', (e) => {
                    if (e.target === devModal) {
                        devModal.classList.add('hidden');
                    }
                });

                const devInput = document.getElementById('devImageInput');
                const devPreview = document.getElementById('devImagePreview');
                const devPreviewContainer = document.getElementById('devPreviewContainer');
                const devUploadBox = document.getElementById('devUploadBox');

                devInput.addEventListener('change', () => {
                    const file = devInput.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            devPreview.src = e.target.result;
                            devPreviewContainer.classList.remove('hidden');
                            devUploadBox.classList.add('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        devPreview.src = '';
                        devPreviewContainer.classList.add('hidden');
                        devUploadBox.classList.remove('hidden');
                    }
                });
            </script>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php while ($dev = $developers->fetch_assoc()): ?>
                    <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col">

                        <!-- Square Image -->
                        <div class="aspect-square overflow-hidden">
                            <img src="<?= htmlspecialchars($dev['image_path']) ?>" alt="Developer Image" class="w-full h-full object-cover rounded-md">
                        </div>

                        <!-- Info -->
                        <div class="flex-grow py-4">
                            <p class="text-lg font-semibold"><?= htmlspecialchars($dev['name']) ?></p>
                            <a href="<?= htmlspecialchars($dev['link']) ?>" target="_blank" class="text-sm text-blue-400 underline break-all">
                                <?= htmlspecialchars($dev['link']) ?>
                            </a>
                        </div>

                        <!-- Buttons -->
                        <div class="grid grid-cols-2 gap-2">
                            <!-- Edit Button -->
                            <button type="button" onclick='openEditModal(<?= json_encode($dev) ?>)' class="bg-black text-white py-3 rounded-md">
                                Edit
                            </button>

                            <!-- Delete Form -->
                            <form action="delete-dev.php" method="POST" onsubmit="return confirm('Delete this developer?')" class="flex-1">
                                <input type="hidden" name="id" value="<?= $dev['id'] ?>">
                                <button type="submit" class="w-full border border-gray-400 text-black py-3 rounded-md text-center">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Edit Modal -->
            <div id="editDevModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Edit Developer
                        </h2>
                        <button id="closeEditModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form action="update-dev.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="id" id="editDevId">

                        <div>
                            <label class="block mb-1 font-medium">Name (vertical letters)</label>
                            <input type="text" name="name" id="editDevName" required class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>

                        <div>
                            <label class="block mb-1 font-medium">Portfolio Link</label>
                            <input type="url" name="link" id="editDevLink" required class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>

                        <div>
                        	<label class="block mb-1 font-medium">Upload New Image (optional)</label>
                        	<label for="editDevImageInput" id="editDevUploadBox"
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
                        	<input type="file" name="image" id="editDevImageInput" accept="image/*" class="hidden">
                        	<div id="editDevPreviewContainer" class="mt-4 hidden relative mx-auto group cursor-pointer">
                        		<img id="editDevPreview" src="" alt="Preview"
                        			class="w-full rounded-md object-cover">
                        		<div class="absolute inset-0 bg-black bg-opacity-50 text-white flex items-center justify-center text-sm font-medium rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        			Click to upload new image
                        		</div>
                        	</div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
            	const modal = document.getElementById('editDevModal');
            	const closeBtn = document.getElementById('closeEditModalBtn');

            	const editDevImageInput = document.getElementById('editDevImageInput');
            	const editDevPreview = document.getElementById('editDevPreview');
            	const editDevPreviewContainer = document.getElementById('editDevPreviewContainer');
            	const editDevUploadBox = document.getElementById('editDevUploadBox');

            	// Show modal and fill fields
            	function openEditModal(dev) {
            		modal.classList.remove('hidden');
                
            		document.getElementById('editDevId').value = dev.id;
            		document.getElementById('editDevName').value = dev.name;
            		document.getElementById('editDevLink').value = dev.link;
                
            		if (dev.image_path) {
            			editDevPreview.src = dev.image_path;
            			editDevPreviewContainer.classList.remove('hidden');
            			editDevUploadBox.classList.add('hidden');
            		} else {
            			editDevPreview.src = '';
            			editDevPreviewContainer.classList.add('hidden');
            			editDevUploadBox.classList.remove('hidden');
            		}
            	}
            
            	// Close modal
            	closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
            
            	// Handle new image upload and preview
            	editDevImageInput.addEventListener('change', () => {
            		const file = editDevImageInput.files[0];
                
            		if (file && file.type.startsWith('image/')) {
            			const reader = new FileReader();
            			reader.onload = (e) => {
            				editDevPreview.src = e.target.result;
            				editDevPreviewContainer.classList.remove('hidden');
            				editDevUploadBox.classList.add('hidden');
            			};
            			reader.readAsDataURL(file);
            		}
            	});
            
            	// Allow clicking the image to trigger new upload
            	editDevPreviewContainer.addEventListener('click', () => {
            		editDevImageInput.click();
            	});
            </script>

        </main>

    </div>

</body>
</html>