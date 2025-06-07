<header class="absolute md:top-10 left-0 w-full bg-[#1B1B1B] z-50 text-white bg-opacity-100 lg:bg-opacity-10 lg:backdrop-blur-md rounded-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="main.php" class="text-3xl font-bold">R4XN</a>
        <nav class="hidden md:flex space-x-10 items-center">
            <a href="project.php" class="hover:text-gray-300 transition">Projects</a>
            <a href="discover.php" class="hover:text-gray-300 transition">Discover</a>
            <div class="relative">
                <button id="desktop-resources-toggle" class="flex items-center hover:text-gray-300 transition focus:outline-none">
                    Resources
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="desktop-resources-menu" class="absolute top-full right-0 mt-2 bg-[#111] rounded-md hidden z-50 p-6 space-x-2 w-max flex">
                    <a href="frontend.php" class="w-56 hover:bg-[#1b1b1b] p-3 rounded-md block">
                        <div class="text-white font-semibold mb-1">Front End Project</div>
                        <div class="text-sm text-gray-400">UI/UX focused web apps using modern frameworks.</div>
                    </a>
                    <a href="system.php" class="w-56 hover:bg-[#1b1b1b] p-3 rounded-md block">
                        <div class="text-white font-semibold mb-1">System Design</div>
                        <div class="text-sm text-gray-400">Architecture, scalability, and real-world systems.</div>
                    </a>
                    <a href="iot.php" class="w-56 hover:bg-[#1b1b1b] p-3 rounded-md block">
                        <div class="text-white font-semibold mb-1">IoT Solution</div>
                        <div class="text-sm text-gray-400">Connected devices and smart system integrations.</div>
                    </a>
                </div>
            </div>
            <a href="about.php" class="hover:text-gray-300 transition">About</a>
        </nav>
        <button id="mobile-toggle" class="md:hidden text-white focus:outline-none">
            <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-6 h-6 block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 12h16"/><path d="M4 18h16"/><path d="M4 6h16"/>
            </svg>
            <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-6 h-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="md:hidden hidden px-6 pb-4 space-y-2 bg-[#111] text-white">
        <a href="project.php" class="block p-3">Projects</a>
        <a href="discover.php" class="block p-3">Discover</a>
        <div>
            <button id="submenu-toggle" class="w-full text-left flex justify-between items-center p-3 rounded-md hover:bg-[#1c1c1c] focus:outline-none">
                Resources
                <svg class="w-4 h-4 transform transition-transform" id="submenu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="submenu" class="ml-4 mt-3 hidden space-y-2">
                <a href="frontend.php" class="block hover:bg-[#1b1b1b] p-3 rounded-md">
                    <div class="text-white font-semibold">Front End Project</div>
                    <div class="text-sm text-gray-400">UI/UX focused web apps using modern frameworks.</div>
                </a>
                <a href="system.php" class="block hover:bg-[#1b1b1b] p-3 rounded-md">
                    <div class="text-white font-semibold">System Design</div>
                    <div class="text-sm text-gray-400">Architecture, scalability, and real-world systems.</div>
                </a>
                <a href="iot.php" class="block hover:bg-[#1b1b1b] p-3 rounded-md">
                    <div class="text-white font-semibold">IoT Solution</div>
                    <div class="text-sm text-gray-400">Connected devices and smart system integrations.</div>
                </a>
            </div>
        </div>
        <a href="about.php" class="block p-3">About</a>
    </div>

    <script>
        const toggleBtn = document.getElementById("mobile-toggle");
        const menu = document.getElementById("mobile-menu");
        const submenuToggle = document.getElementById("submenu-toggle");
        const submenu = document.getElementById("submenu");
        const submenuIcon = document.getElementById("submenu-icon");

        const desktopResourcesToggle = document.getElementById("desktop-resources-toggle");
        const desktopResourcesMenu = document.getElementById("desktop-resources-menu");

        let desktopMenuOpen = false;

        toggleBtn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
                
            // Toggle icon visibility
            document.getElementById("icon-menu").classList.toggle("hidden");
            document.getElementById("icon-close").classList.toggle("hidden");
        });
        

        submenuToggle.addEventListener("click", () => {
            submenu.classList.toggle("hidden");
            submenuIcon.classList.toggle("rotate-180");
        });

        // Toggle desktop submenu on click
        desktopResourcesToggle.addEventListener("click", (e) => {
            e.stopPropagation(); // Prevent bubbling
            desktopResourcesMenu.classList.toggle("hidden");
            desktopMenuOpen = !desktopMenuOpen;
        });

        // Close desktop submenu when clicking outside
        document.addEventListener("click", (e) => {
            if (desktopMenuOpen && !desktopResourcesMenu.contains(e.target) && !desktopResourcesToggle.contains(e.target)) {
                desktopResourcesMenu.classList.add("hidden");
                desktopMenuOpen = false;
            }
        });
    </script>

</header>