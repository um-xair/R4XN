<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div id="sidebarWrapper" class="fixed z-20 top-0 left-0 h-full transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:relative">
    <aside id="sidebar" class="w-64 bg-[#080808] p-4 space-y-6 h-full transition-all duration-300 flex flex-col overflow-y-hidden hover:overflow-y-auto">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4 py-3">
                <img src="../index-assets/r4xn-black.png" class="w-8 h-8" alt="Logo" />
                <h1 id="sidebarTitle" class="text-xl font-bold text-white">R4XN</h1>
            </div>
            <button id="mobileCloseBtn" class="lg:hidden p-1 ml-2 hover:bg-gray-700 rounded text-white">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        <nav class="space-y-2 mt-6 flex flex-col">
            <button onclick="window.location.href='dashboard.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span class="link-label">Home</span>
            </button>
            <button onclick="window.location.href='profile.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round-icon lucide-circle-user-round">
                    <path d="M18 20a6 6 0 0 0-12 0"/>
                    <circle cx="12" cy="10" r="4"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
                <span class="link-label">Profile</span>
            </button>
            <button onclick="window.location.href='manage-dev.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-github-icon lucide-github">
                    <path d="M15 22v-4a4.8 4.8 0 0 0-1-3.5c3 0 6-2 6-5.5.08-1.25-.27-2.48-1-3.5.28-1.15.28-2.35 0-3.5 0 0-1 0-3 1.5-2.64-.5-5.36-.5-8 0C6 2 5 2 5 2c-.3 1.15-.3 2.35 0 3.5A5.403 5.403 0 0 0 4 9c0 3.5 3 5.5 6 5.5-.39.49-.68 1.05-.85 1.65-.17.6-.22 1.23-.15 1.85v4"/>
                    <path d="M9 18c-4.51 2-5-2-7-2"/>
                </svg>
                <span class="link-label">Manage Dev</span>
            </button>
            <button onclick="window.location.href='manage-project.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tv-minimal-icon lucide-tv-minimal">
                    <path d="M7 21h10"/>
                    <rect width="20" height="14" x="2" y="3" rx="2"/>
                </svg>
                <span class="link-label">Manage Project</span>
            </button>
            <button onclick="window.location.href='current-project.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-code-icon lucide-message-square-code">
                    <path d="M10 7.5 8 10l2 2.5"/>
                    <path d="m14 7.5 2 2.5-2 2.5"/>
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span class="link-label">Current Project</span>
            </button>
            <button onclick="window.location.href='manage-frontend.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-spline-pointer-icon lucide-spline-pointer">
                    <path d="M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z"/>
                    <path d="M5 17A12 12 0 0 1 17 5"/>
                    <circle cx="19" cy="5" r="2"/>
                    <circle cx="5" cy="19" r="2"/>
                </svg>
                <span class="link-label">Front-end Project</span>
            </button>
            <button onclick="window.location.href='manage-system.php'" class="flex items-center gap-3 py-3 px-4 rounded-md hover:bg-[#2b2b2b] hover:text-white transition w-full text-white">
                <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard-icon lucide-layout-dashboard">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
                <span class="link-label">System Design</span>
            </button>
        </nav>
    </aside>
</div>

<!-- Header -->
<div id="mainContent" class="flex-1 flex flex-col min-w-0 transition-all duration-300">
    <header class="flex items-center justify-between border-b border-gray-300 p-5 flex-wrap bg-[##F5F5FD]">
        <div class="flex items-center gap-4">
            <button id="toggleSidebar" class="lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <button id="collapseSidebarBtn" class="hidden lg:block transition">
                <svg id="collapseSidebarIcon" class="w-6 h-6 transition-transform duration-300 text-black" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M3 19V5" />
                    <path d="m13 6-6 6 6 6" />
                    <path d="M7 12h14" />
                </svg>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <div onclick="window.location.href='profile.php'" class="flex items-center gap-3 cursor-pointer">
                <img src="<?= htmlspecialchars($_SESSION['user_image'] ?: 'https://i.pinimg.com/736x/a2/61/ad/a261ad5056339af1980926d291cf4183.jpg') ?>"
                     class="w-12 h-12 rounded-full border-2 border-gray-400 object-cover" />
                <div class="flex flex-col text-sm leading-tight">
                    <span class="font-semibold text-black"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <span class="text-gray-400 text-xs"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                </div>
            </div>
            <form method="POST" action="logout.php">
                <button type="submit" class="px-6 py-3 bg-black text-white rounded-md">
                    Logout
                </button>
            </form>
        </div>
    </header>




    <script>
        const sidebarWrapper = document.getElementById("sidebarWrapper");
        const sidebar = document.getElementById("sidebar");
        const toggleSidebar = document.getElementById("toggleSidebar");
        const mobileCloseBtn = document.getElementById("mobileCloseBtn");
        const collapseSidebarBtn = document.getElementById("collapseSidebarBtn");
        const collapseIcon = document.getElementById("collapseSidebarIcon");
        const sidebarTitle = document.getElementById("sidebarTitle");

        toggleSidebar?.addEventListener("click", () => {
            sidebarWrapper.classList.remove("-translate-x-full");
        });

        mobileCloseBtn?.addEventListener("click", () => {
            sidebarWrapper.classList.add("-translate-x-full");
        });

        let collapsed = false;

        collapseSidebarBtn?.addEventListener("click", () => {
            collapsed = !collapsed;

            if (collapsed) {
                sidebar.classList.replace("w-64", "w-20");
                sidebarTitle?.classList.add("hidden");
                document.querySelectorAll(".link-label").forEach(label => label.classList.add("hidden"));
                collapseIcon.classList.add("rotate-180");
            } else {
                sidebar.classList.replace("w-20", "w-64");
                sidebarTitle?.classList.remove("hidden");
                document.querySelectorAll(".link-label").forEach(label => label.classList.remove("hidden"));
                collapseIcon.classList.remove("rotate-180");
            }
        });
    </script>

    <script>
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('bg-green-500', 'bg-red-700', 'opacity-0', 'pointer-events-none', 'translate-x-full');
            toast.classList.add(type === 'success' ? 'bg-green-500' : 'bg-red-700', 'translate-x-0');

            setTimeout(() => {
                toast.classList.remove('translate-x-0');
                toast.classList.add('translate-x-full');
                toast.addEventListener('transitionend', () => {
                    toast.classList.add('opacity-0', 'pointer-events-none');
                }, { once: true });
            }, 3000);
        }
    </script>
