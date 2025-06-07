<?php
include 'config.php'; // adjust path if needed

// Handle upload or update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $title = $_POST['title'] ?? '';
    $link_url = $_POST['link_url'] ?? '';

    if ($action === 'upload' && $title && $link_url && !empty($_FILES['image']['tmp_name'])) {
        // Upload new system project
        $uploadDir = 'system/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $stmt = $conn->prepare("INSERT INTO system_projects (title, link_url, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $link_url, $targetPath);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    elseif ($action === 'update' && !empty($_POST['id']) && $title && $link_url) {
        // Update existing project
        $id = intval($_POST['id']);
        $old = $conn->query("SELECT image_path FROM system_projects WHERE id = $id")->fetch_assoc();
        $imagePath = $old['image_path'];
        if (!empty($_FILES['image']['tmp_name'])) {
            @unlink($imagePath);
            $uploadDir = 'system/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $filename = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $filename;
            move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
            $imagePath = $targetPath;
        }
        $stmt = $conn->prepare("UPDATE system_projects SET title=?, link_url=?, image_path=? WHERE id=?");
        $stmt->bind_param("sssi", $title, $link_url, $imagePath, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    elseif ($action === 'delete' && !empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $old = $conn->query("SELECT image_path FROM system_projects WHERE id = $id")->fetch_assoc();
        @unlink($old['image_path']);
        $conn->query("DELETE FROM system_projects WHERE id = $id");
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch existing system projects
$results = $conn->query("SELECT * FROM system_projects ORDER BY id DESC");
$projects = $results ? $results->fetch_all(MYSQLI_ASSOC) : [];
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

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-2xl font-bold mb-1">Manage System Projects</h1>
                    <p class="text-xs text-gray-400">Upload, edit, or delete system project entries</p>
                </div>
                <button id="openSystemModalBtn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Upload Project
                </button>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <?php foreach ($projects as $pr): ?>
                    <div class="border border-gray-200 rounded-md p-4 overflow-hidden flex flex-col">
                        <a href="<?= htmlspecialchars($pr['link_url']) ?>" target="_blank">
                            <img src="<?= htmlspecialchars($pr['image_path']) ?>" alt="" class="w-full h-full object-cover rounded-md">
                        </a>
                        <div class="flex-grow py-4">
                            <h2 class="text-lg font-semibold"><?= htmlspecialchars($pr['title']) ?></h2>
                            <a href="<?= htmlspecialchars($pr['link_url']) ?>" target="_blank" class="text-sm text-blue-400 underline break-all">
                                <?= htmlspecialchars($pr['link_url']) ?>
                            </a>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick='openSystemEditModal(<?= json_encode($pr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="bg-black text-white py-3 rounded-md">Edit</button>
                            <form method="POST">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $pr['id'] ?>">
                                <button type="submit" onclick="return confirm('Delete this project?')"class="w-full border border-gray-400 text-black py-3 rounded-md text-center">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Upload Modal -->
            <div id="systemModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Upload System Project
                        </h2>
                        <button id="closeSystemModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="upload">
                        <div>
                            <label class="block mb-1 font-medium">Title</label>
                            <input name="title" type="text" required class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Link URL</label>
                            <input name="link_url" type="url" required class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>
                        <div id="systemImageBox">
                            <label for="systemImage" class="block mb-1 font-medium">Upload Image</label>
                            <label for="systemImage" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
                                    <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                    <path d="m14 19.5 3-3 3 3"/>
                                    <path d="M17 22v-5.5"/>
                                    <circle cx="9" cy="9" r="2"/>
                                </svg>
                                <span>Upload Image</span>
                            </label>
                        </div>
                        <input id="systemImage" type="file" name="image" accept="image/*" class="hidden" required>
                        <div id="systemPreview" class="mt-4 hidden">
                            <img id="systemImagePreview" src="" alt="Preview" class="w-full rounded">
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Edit Modal -->
            <div id="systemEditModal" class="fixed px-6 inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
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
                            Edit System Project
                        </h2>
                        <button id="closeSystemEditModalBtn" class="text-gray-400 hover:text-gray-700 transition duration-150 text-xl">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <form method="POST" enctype="multipart/form-data" class="space-y-4">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="systemEditId">
                        <div>
                            <label class="block mb-1 font-medium">Title</label>
                            <input type="text" name="title" id="systemEditTitle" required class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Link URL</label>
                            <input type="url" name="link_url" id="systemEditLink" required class="border border-gray-300 rounded-md px-4 py-3 w-full">
                        </div>
                        <div>
                            <label for="systemEditImageInput" class="block mb-1 font-medium">Upload New Image</label>
                            <label for="systemEditImageInput" class="cursor-pointer flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-md h-48 text-gray-400 transition hover:border-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="1.5" class="w-8 h-8 mb-2">
                                    <path d="M10.3 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10l-3.1-3.1a2 2 0 0 0-2.814.014L6 21"/>
                                    <path d="m14 19.5 3-3 3 3"/>
                                    <path d="M17 22v-5.5"/>
                                    <circle cx="9" cy="9" r="2"/>
                                </svg>
                                <span>Change Image (optional)</span>
                            </label>
                            <input type="file" name="image" id="systemEditImageInput" class="hidden">
                            <div id="systemEditPreview" class="mt-4 hidden">
                                <img id="systemEditImagePreview" src="" alt="Preview" class="w-full rounded">
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="bg-black text-white px-6 py-3 rounded-md">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>


    </div>

    <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 bg-green-500 pointer-events-none opacity-0"></div>

    <script>
        const sysModal = document.getElementById('systemModal');
        const sysEditModal = document.getElementById('systemEditModal');

        document.getElementById('openSystemModalBtn')?.addEventListener('click', () => {
            sysModal.classList.remove('hidden');
        });
        document.getElementById('closeSystemModalBtn')?.addEventListener('click', () => {
            sysModal.classList.add('hidden');
        });
      
        document.getElementById('closeSystemEditModalBtn')?.addEventListener('click', () => {
            sysEditModal.classList.add('hidden');
        });
      
        function openSystemEditModal(pr) {
            if (!pr) return;
            document.getElementById('systemEditId').value = pr.id;
            document.getElementById('systemEditTitle').value = pr.title;
            document.getElementById('systemEditLink').value = pr.link_url;
            document.getElementById('systemEditImagePreview').src = pr.image_path;
            document.getElementById('systemEditPreview').classList.remove('hidden');
            sysEditModal.classList.remove('hidden');
        }
      
        function setupImgPreview(inputId, previewImgId, previewContainerId, uploadBoxId = null) {
            const input = document.getElementById(inputId);
            const previewImg = document.getElementById(previewImgId);
            const previewContainer = document.getElementById(previewContainerId);
            const uploadBox = uploadBoxId ? document.getElementById(uploadBoxId) : null;
            if (!input) return;
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewImg.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        if (uploadBox) uploadBox.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewContainer.classList.add('hidden');
                    previewImg.src = '';
                    if (uploadBox) uploadBox.classList.remove('hidden');
                }
            });
        }
      
        setupImgPreview('systemImage', 'systemImagePreview', 'systemPreview', 'systemImageBox'); // upload modal
        setupImgPreview('systemEditImageInput', 'systemEditImagePreview', 'systemEditPreview');  // edit modal
    </script>


</body>
</html>