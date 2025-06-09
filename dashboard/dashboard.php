<?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    require_once 'config.php'; 

    $frontendCount = $systemCount = $iotCount = 0;

    $frontendResult = $conn->query("SELECT COUNT(*) AS total FROM frontend_projects");
    $systemResult   = $conn->query("SELECT COUNT(*) AS total FROM system_projects");
    $iotResult      = $conn->query("SELECT COUNT(*) AS total FROM iot_projects");

    if ($frontendResult && $row = $frontendResult->fetch_assoc()) {
        $frontendCount = $row['total'];
    }
    if ($systemResult && $row = $systemResult->fetch_assoc()) {
        $systemCount = $row['total'];
    }
    if ($iotResult && $row = $iotResult->fetch_assoc()) {
        $iotCount = $row['total'];
    }

    $totalProjects = $frontendCount + $systemCount + $iotCount;

    $recentProjects = [];

    $sql = "
        (SELECT 'Frontend' AS category, title, link_url AS link, image_path, created_at FROM frontend_projects)
        UNION
        (SELECT 'System' AS category, title, link_url AS link, image_path, created_at FROM system_projects)
        UNION
        (SELECT 'IoT' AS category, title, link_url AS link, image_path, created_at FROM iot_projects)
        ORDER BY created_at DESC
        LIMIT 4
    ";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recentProjects[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no" />
<title>Dahsboard</title>
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

        <?php include 'sidebar.php'; ?>

        <main class="flex-1 p-6 w-full overflow-y-auto min-w-0 relative">
            <div>
                <!-- Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-3 sm:gap-0 px-2 sm:px-0">
                    <div class="max-w-full sm:max-w-[60%]">
                        <h1 class="text-3xl font-extrabold mb-1">
                            Welcome Back, <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                        </h1>
                        <p class="text-sm text-gray-400">Manage your projects and keep your portfolio up to date.</p>
                    </div>
                </div>

                <!-- Analytics Section -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-2 mb-4">
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-cog-icon lucide-monitor-cog">
                                <path d="M12 17v4"/>
                                <path d="m14.305 7.53.923-.382"/>
                                <path d="m15.228 4.852-.923-.383"/>
                                <path d="m16.852 3.228-.383-.924"/>
                                <path d="m16.852 8.772-.383.923"/>
                                <path d="m19.148 3.228.383-.924"/>
                                <path d="m19.53 9.696-.382-.924"/>
                                <path d="m20.772 4.852.924-.383"/>
                                <path d="m20.772 7.148.924.383"/>
                                <path d="M22 13v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"/>
                                <path d="M8 21h8"/>
                                <circle cx="18" cy="6" r="3"/>
                            </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">Total Projects</p>
                            <h2 class="text-xl font-semibold"><?= $totalProjects ?></h2>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pen-tool-icon lucide-pen-tool">
                                <path d="M15.707 21.293a1 1 0 0 1-1.414 0l-1.586-1.586a1 1 0 0 1 0-1.414l5.586-5.586a1 1 0 0 1 1.414 0l1.586 1.586a1 1 0 0 1 0 1.414z"/>
                                <path d="m18 13-1.375-6.874a1 1 0 0 0-.746-.776L3.235 2.028a1 1 0 0 0-1.207 1.207L5.35 15.879a1 1 0 0 0 .776.746L13 18"/>
                                <path d="m2.3 2.3 7.286 7.286"/>
                                <circle cx="11" cy="11" r="2"/>
                            </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">Frontend Project</p>
                            <h2 class="text-xl font-semibold"><?= $frontendCount ?></h2>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-monitor-smartphone-icon lucide-monitor-smartphone">
                                <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/>
                                <path d="M10 19v-3.96 3.15"/>
                                <path d="M7 19h5"/>
                                <rect width="6" height="10" x="16" y="12" rx="2"/>
                            </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">System Design</p>
                            <h2 class="text-xl font-semibold"><?= $systemCount ?></h2>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-md border flex items-stretch space-x-4">
                        <div class="bg-black text-white p-2 rounded-md flex items-center justify-center h-full aspect-square">
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cpu-icon lucide-cpu">
                                <path d="M12 20v2"/>
                                <path d="M12 2v2"/>
                                <path d="M17 20v2"/>
                                <path d="M17 2v2"/>
                                <path d="M2 12h2"/>
                                <path d="M2 17h2"/>
                                <path d="M2 7h2"/>
                                <path d="M20 12h2"/>
                                <path d="M20 17h2"/>
                                <path d="M20 7h2"/>
                                <path d="M7 20v2"/>
                                <path d="M7 2v2"/>
                                <rect x="4" y="4" width="16" height="16" rx="2"/>
                                <rect x="8" y="8" width="8" height="8" rx="1"/>
                            </svg>
                        </div>
                        <div class="flex flex-col justify-center">
                            <p class="text-xs text-gray-400 mb-1">IoT Solution</p>
                            <h2 class="text-xl font-semibold"><?= $iotCount ?></h2>
                        </div>
                    </div>
                </div>

                <!-- Projects List Preview -->
                <section class="bg-white p-6 border border-gray-200 rounded-md max-h-[60vh] overflow-y-auto">
                    <h3 class="text-xl font-semibold mb-4">Recent Projects</h3>
                    <ul class="divide-y divide-gray-200 max-h-[50vh] overflow-y-auto">
                        <?php if (empty($recentProjects)): ?>
                            <li class="py-4 text-gray-400 text-center">No recent projects uploaded.</li>
                        <?php else: ?>
                            <?php foreach ($recentProjects as $project): ?>
                                <li class="py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 w-full sm:w-auto">
                                        <img src="<?= htmlspecialchars($project['image_path']) ?>" alt="Project Image"
                                             class="w-20 h-20 sm:w-32 sm:h-32 rounded-md object-cover flex-shrink-0" />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-base sm:text-lg font-semibold truncate"><?= htmlspecialchars($project['title']) ?></p>
                                            <a href="<?= htmlspecialchars($project['link']) ?>"
                                               class="text-blue-500 text-xs sm:text-sm hover:underline block truncate"
                                               target="_blank" rel="noopener noreferrer">
                                                <?= htmlspecialchars($project['link']) ?>
                                            </a>
                                            <p class="text-xs text-gray-400 mt-1"><?= htmlspecialchars($project['category']) ?> Project</p>
                                        </div>
                                    </div>
                                    <div class="w-full sm:w-auto">
                                        <?php
                                            $category = strtolower($project['category']);
                                            $pageMap = [
                                                'frontend' => 'manage-frontend.php',
                                                'system'   => 'manage-system.php',
                                                'iot'      => 'manage-iot.php'
                                            ];
                                            $targetPage = $pageMap[$category] ?? '#';
                                        ?>
                                        <a href="<?= $targetPage ?>" class="inline-block px-5 py-2 bg-black text-white rounded-md text-center w-full sm:w-auto">
                                            Go
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </section>
            </div>
        </main>

    </div>
    </div>

</body>
</html>