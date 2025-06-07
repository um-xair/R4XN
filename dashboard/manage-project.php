<?php
session_start();
require 'config.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    
    // Get image path from DB
    $stmt = $conn->prepare("SELECT image_path FROM project_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($path);
    $stmt->fetch();
    $stmt->close();

    // Delete file from filesystem
    if ($path && file_exists($path)) {
        unlink($path);
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM project_images WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage-project.php?deleted=1");
    exit;
}

// Get images
$result = $conn->query("SELECT * FROM project_images ORDER BY created_at DESC");
$images = $result->fetch_all(MYSQLI_ASSOC);
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

            <!-- Header with title left, upload button right -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-2xl font-bold mb-1">Manage Project Showcase</h1>
                    <p class="text-xs text-gray-400">Upload, replace, and delete project images easily</p>
                </div>
                <button id="openUploadModalBtn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Upload Image
                </button>
            </div>
            
            <!-- Modal backdrop -->
            <div id="uploadModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Upload New Image
                        </h2>
                        <button id="closeModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form method="POST" action="upload-image.php" enctype="multipart/form-data" id="uploadForm" class="mx-auto">
                        <label for="imageInput" id="uploadBox"class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md h-48 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-up-icon lucide-image-up w-6 h-6">
                                <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                <path d="m14 19.5 3-3 3 3"/>
                                <path d="M17 22v-5.5"/>
                                <circle cx="9" cy="9" r="2"/>
                            </svg>
                            <span>Upload Image Here</span>
                        </label>
                        <input type="file" name="image" id="imageInput" accept="image/*" required class="hidden" />
                        <div id="previewContainer" class="mb-4 hidden">
                            <img id="imagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">
                                Upload Image
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($images as $img): ?>
                    <div class="border border-gray-200 p-4 rounded-md relative">
                        <img src="<?= htmlspecialchars($img['image_path']) ?>" alt="Project" class="w-full rounded-md mb-4">
                        <div class="flex gap-2">
                            <button class="replaceBtn bg-black text-white py-3 rounded-md w-1/2" data-id="<?= $img['id'] ?>" data-imgpath="../<?= htmlspecialchars($img['image_path']) ?>">
                                Replace
                            </button>
                            <a href="?delete=<?= $img['id'] ?>" onclick="return confirm('Delete this image?')" class="border border-gray-400 text-black py-3 rounded-md text-center w-1/2">
                                Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
                
            <div id="replaceModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Replace Image
                        </h2>
                        <button id="modalClose" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form id="replaceForm" method="POST" enctype="multipart/form-data" action="edit-image.php" class="mx-auto">
                        <input type="hidden" name="id" id="replaceImageId">
                        <label for="modalFileInput" id="replaceUploadBox"
                            class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md h-48 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-up-icon lucide-image-up w-6 h-6">
                                <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                <path d="m14 19.5 3-3 3 3"/>
                                <path d="M17 22v-5.5"/>
                                <circle cx="9" cy="9" r="2"/>
                            </svg>
                            <span>Upload New Image</span>
                        </label>
                        <input type="file" name="new_image" id="modalFileInput" accept="image/*" required class="hidden" />
                        <div id="modalPreviewContainer" class="mb-4 hidden">
                            <img id="modalImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">
                                Replace Image
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </main>
            
        <script>
            // Modal open/close logic
            const modal = document.getElementById('uploadModal');
            const openBtn = document.getElementById('openUploadModalBtn');
            const closeBtn = document.getElementById('closeModalBtn');
            const imageInput = document.getElementById('imageInput');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const uploadBox = document.getElementById('uploadBox');
                
            openBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                imagePreview.src = '';
                imageInput.value = '';
            });
          
            closeBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
          
            imageInput.addEventListener('change', () => {
                const file = imageInput.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        imagePreview.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadBox.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                    uploadBox.classList.remove('hidden');
                    imagePreview.src = '';
                }
              });
          
            // Optional: Close modal when clicking outside modal content
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('replaceModal');
                const modalCloseBtn = document.getElementById('modalClose');
                const modalFileInput = document.getElementById('modalFileInput');
                const modalPreviewContainer = document.getElementById('modalPreviewContainer');
                const modalImagePreview = document.getElementById('modalImagePreview');
                const replaceUploadBox = document.getElementById('replaceUploadBox');  // updated here
                const replaceButtons = document.querySelectorAll('.replaceBtn');

                replaceButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        const imageId = button.getAttribute('data-id');
                        document.getElementById('replaceImageId').value = imageId; // Set the ID
                    
                        modalFileInput.value = '';
                        modalImagePreview.src = '';
                        modalPreviewContainer.classList.add('hidden');
                        replaceUploadBox.classList.remove('hidden');
                        modal.classList.remove('hidden');
                    });
                });
              
                modalCloseBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                });
              
                modalFileInput.addEventListener('change', () => {
                    const file = modalFileInput.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            modalImagePreview.src = e.target.result;
                            modalPreviewContainer.classList.remove('hidden'); // show preview
                            replaceUploadBox.classList.add('hidden'); // hide upload label
                        };
                        reader.readAsDataURL(file);
                    } else {
                        modalPreviewContainer.classList.add('hidden'); // hide preview
                        modalImagePreview.src = '';
                        replaceUploadBox.classList.remove('hidden'); // show upload label
                    }
                });
                
                modal.addEventListener('click', e => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
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

        <?php if (!empty($success)): ?>
            showToast("<?= htmlspecialchars($success, ENT_QUOTES) ?>", "success");
        <?php elseif (!empty($error)): ?>
            showToast("<?= htmlspecialchars($error, ENT_QUOTES) ?>", "error");
        <?php elseif (isset($_GET['deleted'])): ?>
            showToast("Image deleted successfully.", "success");
        <?php endif; ?>
    </script>

</body>
</html>
