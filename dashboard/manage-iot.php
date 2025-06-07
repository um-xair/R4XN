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
                    <h1 class="text-2xl font-bold mb-1">Manage Frontend Projects</h1>
                    <p class="text-xs text-gray-400">Upload new projects — all your frontend portfolio management in one place</p>
                </div>
                <button id="open-frontendmodal-btn" class="bg-black text-white px-6 py-3 rounded-md max-w-full shrink-0" type="button">
                    Upload Project
                </button>
            </div>

            <h1 class="text-2xl font-bold mb-6">Manage IoT Projects</h1>

  <!-- Upload Form -->
  <form action="upload-iot.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow mb-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="text" name="title" required placeholder="Project Name" class="border p-2 rounded w-full">
      <input type="url" name="link_url" required placeholder="Project Link" class="border p-2 rounded w-full">
      <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'iotPreview')" required class="border p-2 rounded w-full">
    </div>
    <div class="mt-4">
      <img id="iotPreview" src="" alt="" class="w-48 hidden" />
    </div>
    <button type="submit" class="mt-4 bg-black text-white px-4 py-2 rounded">Upload Project</button>
  </form>

  <!-- Projects List -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php while ($p = $projects->fetch_assoc()): ?>
      <div class="bg-white p-4 rounded shadow flex flex-col">
        <img src="iot/<?= htmlspecialchars(basename($p['image_path'])) ?>" class="w-full aspect-square object-cover rounded mb-4">
        <h2 class="font-semibold text-lg mb-2"><?= htmlspecialchars($p['title']) ?></h2>
        <a href="<?= htmlspecialchars($p['link_url']) ?>" class="text-blue-500 text-sm break-all mb-4" target="_blank">Visit Link</a>
        <div class="flex gap-2">
          <form action="delete-iot.php" method="POST" onsubmit="return confirm('Delete this project?')">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button class="bg-red-500 text-white px-4 py-2 rounded w-full">Delete</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
                    

        </main>

        
    <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2 bg-green-500 pointer-events-none opacity-0"></div>



</body>
</html>
