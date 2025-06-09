<?php
    include 'config.php'; // adjust path if needed

    // Handle upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'] ?? '';
        $link_url = $_POST['link_url'] ?? '';
        $image = $_FILES['image'];

        if ($title && $link_url && $image['tmp_name']) {
            $uploadDir = 'frontend/';
            $filename = time() . '_' . basename($image['name']);
            $targetPath = $uploadDir . $filename;

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                $stmt = $conn->prepare("INSERT INTO frontend_projects (title, link_url, image_path) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $title, $link_url, $targetPath);
                $stmt->execute();
                $stmt->close();

                // Redirect to avoid resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
        }
    }

    // Fetch existing entries
    $projects = [];
    $result = $conn->query("SELECT * FROM frontend_projects ORDER BY created_at DESC");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
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

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-2xl font-bold mb-1">Manage Frontend Projects</h1>
                    <p class="text-xs text-gray-400">Upload new projects — all your frontend portfolio management in one place</p>
                </div>
                <button id="open-frontendmodal-btn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Upload Project
                </button>
            </div>

            <div class="mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <?php foreach ($projects as $project): ?>
                        <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col">
                            <!-- Image -->
                            <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank">
                                <img src="<?= htmlspecialchars($project['image_path']) ?>" class="w-full h-full object-cover rounded-md" alt="">
                            </a>
                            <!-- Text Content -->
                            <div class="flex-grow py-4">
                                <h3 class="text-lg font-semibold"><?= htmlspecialchars($project['title']) ?></h3>
                                <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank" class="text-sm text-blue-400 underline break-all">
                                    <?= htmlspecialchars($project['link_url']) ?>
                                </a>
                            </div>
                            <!-- Buttons -->
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick='openEditModal(<?= json_encode($project) ?>)' class="bg-black text-white py-3 rounded-md">
                                    Edit
                                </button>
                                <form method="POST" action="delete-frontend.php" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    <input type="hidden" name="id" value="<?= $project['id'] ?>">
                                    <button type="submit" class="w-full border border-gray-400 text-black py-3 rounded-md text-center">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </main>

        <!-- Edit Modal -->
        <div id="editFrontendModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-md p-10 max-w-4xl w-full relative overflow-y-auto max-h-[90vh]">
                <div class="flex justify-between items-center border-b pb-4 mb-6">
                    <h2 class="text-xl font-semibold">Edit Frontend Project</h2>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-700 text-xl">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data" action="update-frontend.php" class="space-y-4">
                    <input type="hidden" name="id" id="editProjectId">
                    <div>
                        <label class="block mb-1 font-medium">Title</label>
                        <input type="text" name="title" id="editTitle" class="border border-gray-300 rounded-md px-4 py-3 w-full" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Link URL</label>
                        <input type="url" name="link_url" id="editLink" class="border border-gray-300 rounded-md px-4 py-3 w-full" required>
                    </div>
                    <div>
                        <label for="editImageInput" class="block mb-1 font-medium">Change Image (optional)</label>
                        <!-- Upload box -->
                        <label for="editImageInput" id="editUploadBox"
                            class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
                                <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                <path d="m14 19.5 3-3 3 3"/>
                                <path d="M17 22v-5.5"/>
                                <circle cx="9" cy="9" r="2"/>
                            </svg>
                            <span class="text-sm font-medium">Upload Image Here (optional)</span>
                        </label>
                        <!-- Hidden file input -->
                        <input type="file" name="image" id="editImageInput" accept="image/*" class="hidden">
                        <!-- Preview -->
                        <div id="editPreviewContainer" class="mt-4 hidden">
                            <img id="editImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Update Project</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openEditModal(project) {
                document.getElementById('editProjectId').value = project.id;
                document.getElementById('editTitle').value = project.title;
                document.getElementById('editLink').value = project.link_url;

                // Show current image preview
                if (project.image_path) {
                    document.getElementById('editImagePreview').src = project.image_path;
                    document.getElementById('editPreviewContainer').classList.remove('hidden');
                } else {
                    document.getElementById('editImagePreview').src = '';
                    document.getElementById('editPreviewContainer').classList.add('hidden');
                }

                document.getElementById('editFrontendModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editFrontendModal').classList.add('hidden');
            }
    
            const editFileInput = document.getElementById('editImageInput');
            const editImagePreview = document.getElementById('editImagePreview');
            const editPreviewContainer = document.getElementById('editPreviewContainer');
            const editUploadBox = document.getElementById('editUploadBox');
        
            editFileInput.addEventListener('change', () => {
                const file = editFileInput.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        editImagePreview.src = e.target.result;
                        editPreviewContainer.classList.remove('hidden');
                        // Remove this line so upload box stays visible
                        // editUploadBox.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    editPreviewContainer.classList.add('hidden');
                    editImagePreview.src = '';
                    // Remove this line so upload box stays visible
                    // editUploadBox.classList.remove('hidden');
                }
            });
        </script>

        <!-- Modal -->
        <div id="frontendmodal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                        Upload Frontend Project
                    </h2>
                    <button id="close-frontendmodal-btn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div>
                    <form method="POST" enctype="multipart/form-data" class="space-y-4" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                        <div>
                            <label class="block mb-1 font-medium">Title</label>
                            <input type="text" name="title" class="border border-gray-300 rounded-md px-4 py-3 w-full" required>
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Link URL</label>
                            <input type="url" name="link_url" class="border border-gray-300 rounded-md px-4 py-3 w-full" required>
                        </div>
                        <div>
                            <label for="frontendImageInput" class="block mb-1 font-medium">Upload New Image:</label>
                            <label for="frontendImageInput" id="frontendUploadBox"
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
                            <input type="file" name="image" id="frontendImageInput" accept="image/*" class="hidden" required>
                            <div id="frontendPreviewContainer" class="mt-4 hidden">
                                <img id="frontendImagePreview" src="" alt="Preview" class="w-auto mx-auto rounded-md" />
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Upload Project</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Modal open/close
        const openBtn = document.getElementById('open-frontendmodal-btn');
        const modal = document.getElementById('frontendmodal');
        const closeBtn = document.getElementById('close-frontendmodal-btn');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            backdrop.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            backdrop.classList.add('hidden');
        });

        backdrop.addEventListener('click', () => {
            modal.classList.add('hidden');
            backdrop.classList.add('hidden');
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalFileInput = document.getElementById('frontendImageInput');
            const modalImagePreview = document.getElementById('frontendImagePreview');
            const modalPreviewContainer = document.getElementById('frontendPreviewContainer');
            const replaceUploadBox = document.getElementById('frontendUploadBox');
        
            if (modalFileInput) {
                modalFileInput.addEventListener('change', () => {
                    const file = modalFileInput.files[0];
                
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            modalImagePreview.src = e.target.result;
                            modalPreviewContainer.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        modalImagePreview.src = '';
                        modalPreviewContainer.classList.add('hidden');
                    }
                });
            } else {
                console.error('Could not find modal file input for frontend modal.');
            }
        });
    </script>

</body>
</html>