<?php
session_start();
require 'config.php';

// Fetch current project data
$stmt = $conn->prepare("SELECT id, image_path, link_url FROM current_project LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc();
$stmt->close();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $link = $_POST['link_url'] ?? '';
    $link = filter_var($link, FILTER_SANITIZE_URL);

    // Handle image upload if provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $uploadDir = 'index-assets/';
        $fileName = uniqid() . "-" . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Delete old image file
            if ($current && file_exists($current['image_path'])) {
                unlink($current['image_path']);
            }

            if ($current) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE current_project SET image_path = ?, link_url = ?, updated_at = NOW() WHERE id = ?");
                $stmt->bind_param("ssi", $targetPath, $link, $current['id']);
                $stmt->execute();
                $stmt->close();
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO current_project (image_path, link_url) VALUES (?, ?)");
                $stmt->bind_param("ss", $targetPath, $link);
                $stmt->execute();
                $stmt->close();
            }
            $message = "Project updated successfully.";
        } else {
            $message = "Failed to upload image.";
        }
    } else {
        // No image uploaded, update only link if record exists
        if ($current) {
            $stmt = $conn->prepare("UPDATE current_project SET link_url = ?, updated_at = NOW() WHERE id = ?");
            $stmt->bind_param("si", $link, $current['id']);
            $stmt->execute();
            $stmt->close();
            $message = "Link updated successfully.";
        } else {
            $message = "Please upload an image.";
        }
    }

    // Refresh current data after update
    $stmt = $conn->prepare("SELECT id, image_path, link_url FROM current_project LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();
    $current = $result->fetch_assoc();
    $stmt->close();
}
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
                    <h1 class="text-2xl font-bold mb-1">Manage Current Project</h1>
                    <p class="text-xs text-gray-400">Upload new images, replace existing ones, and keep your current project visuals up to date with ease</p>
                </div>
                <button id="openModalBtn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Update Project
                </button>
            </div>
            
            <?php if ($current): ?>
                <div class="p-8 border border-gray-200 rounded-md">
                    <h2 class="text-lg font-semibold mb-2">Current Project Image & Link:</h2>
                    <a href="<?= htmlspecialchars($current['link_url']) ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?= htmlspecialchars($current['image_path']) ?>" alt="Current Project" class="rounded-md mb-2">
                    </a>
                    <p>Link URL: <a class="underline text-blue-400" href="<?= htmlspecialchars($current['link_url']) ?>" target="_blank"><?= htmlspecialchars($current['link_url']) ?></a></p>
                </div>
            <?php endif; ?>
            
            <!-- Modal Overlay -->
            <div id="currentProjectModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Update Current Project
                        </h2>
                        <button id="closeModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div>
                            <label class="block mb-1 font-medium">Link URL:</label>
                            <input type="url" name="link_url" value="<?= $current['link_url'] ?? '' ?>" placeholder="https://example.com" required class="border rounded-md px-4 py-3 w-full" />
                        </div>
                        <div>
                            <label for="imageUpload" class="block mb-1 font-medium">Upload New Image:</label>
                            <label id="uploadLabel" for="imageUpload"
                                class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-md h-48 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-up-icon lucide-image-up w-6 h-6">
                                    <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                    <path d="m14 19.5 3-3 3 3"/>
                                    <path d="M17 22v-5.5"/>
                                    <circle cx="9" cy="9" r="2"/>
                                </svg>
                                <span>Click to upload image</span>
                                <input id="imageUpload" type="file" name="image" accept="image/*" class="hidden" />
                            </label>

                            <img id="imagePreview" class="mt-4 rounded-md hidden" alt="Preview" />
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </main>

        <script>
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const currentProjectModal = document.getElementById('currentProjectModal');

            openModalBtn.addEventListener('click', () => {
                currentProjectModal.classList.remove('hidden');
            });
        
            closeModalBtn.addEventListener('click', () => {
                currentProjectModal.classList.add('hidden');
            });
        
            currentProjectModal.addEventListener('click', (e) => {
                if (e.target === currentProjectModal) {
                    currentProjectModal.classList.add('hidden');
                }
            });

            const imageInput = document.getElementById('imageUpload');
            const preview = document.getElementById('imagePreview');
            const uploadLabel = document.getElementById('uploadLabel');

            imageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        uploadLabel.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('hidden');
                    uploadLabel.classList.remove('hidden');
                }
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

        <?php if ($message): ?>
            <div class="mb-4 p-3 bg-green-700 rounded"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </script>

</body>
</html>
