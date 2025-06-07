<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Handle form submit (update profile)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($name) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid name and email.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } elseif ($new_password && strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $profile_image_path = null;

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_image']['tmp_name'];
            $fileName = basename($_FILES['profile_image']['name']);
            $fileSize = $_FILES['profile_image']['size'];
            $fileType = mime_content_type($fileTmpPath);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($fileType, $allowedTypes)) {
                $error = "Only JPG, PNG, and GIF files are allowed.";
            } elseif ($fileSize > 2 * 1024 * 1024) {
                $error = "Image size should be less than 2MB.";
            } else {
                $uploadDir = __DIR__ . '/uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $newFileName = 'profile_' . $user_id . '_' . time() . '.' . $ext;
                $destPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $profile_image_path = 'uploads/' . $newFileName;
                } else {
                    $error = "Error uploading the file.";
                }
            }
        }

        if (!$error) {
            if ($new_password) {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                if ($profile_image_path) {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ?, profile_image = ? WHERE id = ?");
                    $stmt->bind_param("ssssi", $name, $email, $password_hash, $profile_image_path, $user_id);
                } else {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $name, $email, $password_hash, $user_id);
                }
            } else {
                if ($profile_image_path) {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, profile_image = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $name, $email, $profile_image_path, $user_id);
                } else {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                    $stmt->bind_param("ssi", $name, $email, $user_id);
                }
            }

            if ($stmt->execute()) {
                $success = "Profile updated successfully!";
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                if ($profile_image_path) {
                    $_SESSION['user_image'] = $profile_image_path;
                }
            } else {
                $error = "Failed to update profile.";
            }
            $stmt->close();
        }
    }
}

// Fetch fresh user data for display
$stmt = $conn->prepare("SELECT name, email, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$profile_image = $user['profile_image'] ?: 'https://i.pinimg.com/736x/a2/61/ad/a261ad5056339af1980926d291cf4183.jpg';

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

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3 sm:gap-0 px-2 sm:px-0">
                <div class="max-w-full sm:max-w-[60%]">
                    <h1 class="text-2xl font-bold mb-1">Personal Information</h1>
                    <p class="text-xs text-gray-400">Modify your personal and account settings in one place</p>
                </div>
            </div>

            <form method="POST" enctype="multipart/form-data" class="p-8 rounded-md w-full border border-gray-200 bg-[#F5F5FD] flex flex-col md:flex-row items-start gap-10 mx-auto" id="profileForm">

                <div class="relative group w-40 h-40 shrink-0">
                    <img id="profileImagePreview" src="<?= htmlspecialchars($profile_image) ?>" alt="Profile Image" class="w-full h-full object-cover rounded-full border-4 border-gray-300 shadow-lg" />
                    <input type="file" name="profile_image" accept="image/*" id="profileImageInput" class="hidden" />
                    <label for="profileImageInput" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition">
                        <i class="fas fa-camera text-white text-2xl"></i>
                    </label>
                </div>

                <div class="flex-1 flex flex-col justify-between w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6">
                        <div class="flex flex-col">
                            <label for="name" class="block mb-1 font-medium">Name</label>
                            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required class="border border-gray-300 rounded-md px-4 py-3 w-full bg-[#F5F5FD]" />
                        </div>
                        <div class="flex flex-col">
                            <label for="email" class="block mb-1 font-medium">Email</label>
                            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required class="border border-gray-300 rounded-md px-4 py-3 w-full bg-[#F5F5FD]" />
                        </div>
                        <div class="flex flex-col">
                            <label for="new_password" class="block mb-1 font-medium">New Password <span class="text-sm font-normal">(optional)</span></label>
                            <input type="password" name="new_password" id="new_password" placeholder="Enter new password" class="border border-gray-300 rounded-md px-4 py-3 w-full bg-[#F5F5FD]" />
                        </div>
                        <div class="flex flex-col">
                            <label for="confirm_password" class="block mb-1 font-medium">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" class="border border-gray-300 rounded-md px-4 py-3 w-full bg-[#F5F5FD]" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button id="topSaveBtn" type="submit" class="bg-black text-white px-6 py-3 rounded-md">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>

        </main>
        <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 bg-green-500 pointer-events-none opacity-0"></div>

    </div>

    <script>
        document.getElementById('topSaveBtn').addEventListener('click', () => {
            document.getElementById('profileForm').submit();
        });

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;    

            if (type === 'error') {
                toast.classList.remove('bg-green-600');
                toast.classList.add('bg-red-600');
            } else {
                toast.classList.remove('bg-red-600');
                toast.classList.add('bg-green-600');
            }   
            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-full');
            toast.classList.add('opacity-100', 'pointer-events-auto');
            toast.style.transform = 'none'; 
            setTimeout(() => {
                toast.classList.remove('opacity-100', 'pointer-events-auto');
                toast.classList.add('opacity-0', 'pointer-events-none', 'translate-x-full');
            }, 3000);
        }   

        <?php if (!empty($success)): ?>
            showToast("<?= htmlspecialchars($success, ENT_QUOTES) ?>", "success");
        <?php elseif (!empty($error)): ?>
            showToast("<?= htmlspecialchars($error, ENT_QUOTES) ?>", "error");
        <?php endif; ?>

        document.getElementById('profileImageInput').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('profileImagePreview');
        
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

</body>
</html>
