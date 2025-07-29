<header id="main-header" class="absolute top-0 left-0 w-full bg-[#ffffff] dark:bg-[#080808] text-black dark:text-white z-50">
    <div class="max-w-7xl mx-auto p-6 flex justify-between items-center">
        <a href="https://r4xn.com" class="text-3xl font-bold">R4XN</a>
        <nav class="hidden md:flex space-x-10 items-center text-lg">
            <a href="project.php" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-300">Projects</a>
            <a href="discover.php" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-300">Discover</a>
            <div class="relative">
                <button id="desktop-resources-toggle" class="flex items-center hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-300 focus:outline-none">
                    Resources
                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="desktop-resources-menu" class="absolute top-full right-0 mt-2 bg-gray-200 dark:bg-[#111] rounded-md hidden z-50 p-6 space-x-2 w-max flex transition-all duration-300">
                    <a href="frontend.php" class="w-56 hover:bg-gray-300 dark:hover:bg-[#1b1b1b] p-3 rounded-md block transition-colors duration-300">
                        <div class="text-black dark:text-white font-semibold mb-1">Front End Project</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">UI/UX focused web apps using modern frameworks.</div>
                    </a>
                    <a href="system.php" class="w-56 hover:bg-gray-300 dark:hover:bg-[#1b1b1b] p-3 rounded-md block transition-colors duration-300">
                        <div class="text-black dark:text-white font-semibold mb-1">System Design</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Architecture, scalability, and real-world systems.</div>
                    </a>
                    <a href="iot.php" class="w-56 hover:bg-gray-300 dark:hover:bg-[#1b1b1b] p-3 rounded-md block transition-colors duration-300">
                        <div class="text-black dark:text-white font-semibold mb-1">IoT Solution</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Connected devices and smart system integrations.</div>
                    </a>
                </div>
            </div>
            <a href="about.php" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors duration-300">About</a>
            
            <!-- Theme Toggle Button -->
            <button id="theme-toggle" class="p-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-700 transition-all duration-300 focus:outline-none" aria-label="Toggle theme">
                <!-- Dark Mode Icon (default) -->
                <svg id="dark-icon" class="w-5 h-5 transition-all duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
                <!-- Light Mode Icon -->
                <svg id="light-icon" class="w-5 h-5 hidden transition-all duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
            </button>
        </nav>
        <button id="mobile-toggle" aria-label="Toggle navigation menu" class="md:hidden text-black dark:text-white focus:outline-none transition-colors duration-300">
            <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-6 h-6 block transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 12h16"/><path d="M4 18h16"/><path d="M4 6h16"/>
            </svg>
            <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="w-6 h-6 hidden transition-all duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
            </svg>
        </button>
    </div>

    <div id="mobile-menu" class="md:hidden hidden px-6 pb-4 space-y-2 bg-gray-100 dark:bg-[#111] text-black dark:text-white transition-all duration-500">
        <a href="project.php" class="block p-3 transition-colors duration-300">Projects</a>
        <a href="discover.php" class="block p-3 transition-colors duration-300">Discover</a>
        <div>
            <button id="submenu-toggle" class="w-full text-left flex justify-between items-center p-3 rounded-md hover:bg-gray-200 dark:hover:bg-[#1c1c1c] focus:outline-none transition-colors duration-300">
                Resources
                <svg class="w-4 h-4 transform transition-transform duration-300" id="submenu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div id="submenu" class="ml-4 mt-3 hidden space-y-2 transition-all duration-300">
                <a href="frontend.php" class="block hover:bg-gray-200 dark:hover:bg-[#1b1b1b] p-3 rounded-md transition-colors duration-300">
                    <div class="text-black dark:text-white font-semibold">Front End Project</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">UI/UX focused web apps using modern frameworks.</div>
                </a>
                <a href="system.php" class="block hover:bg-gray-200 dark:hover:bg-[#1b1b1b] p-3 rounded-md transition-colors duration-300">
                    <div class="text-black dark:text-white font-semibold">System Design</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Architecture, scalability, and real-world systems.</div>
                </a>
                <a href="iot.php" class="block hover:bg-gray-200 dark:hover:bg-[#1b1b1b] p-3 rounded-md transition-colors duration-300">
                    <div class="text-black dark:text-white font-semibold">IoT Solution</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Connected devices and smart system integrations.</div>
                </a>
            </div>
        </div>
        <a href="about.php" class="block p-3 transition-colors duration-300">About</a>
        
        <!-- Mobile Theme Toggle -->
        <button id="mobile-theme-toggle" class="w-full text-left flex justify-between items-center p-3 rounded-md hover:bg-gray-200 dark:hover:bg-[#1c1c1c] focus:outline-none transition-colors duration-300">
            <span>Theme</span>
            <svg id="mobile-dark-icon" class="w-5 h-5 transition-all duration-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
        </button>
    </div>

    <script>
        const toggleBtn = document.getElementById("mobile-toggle");
        const menu = document.getElementById("mobile-menu");
        const submenuToggle = document.getElementById("submenu-toggle");
        const submenu = document.getElementById("submenu");
        const submenuIcon = document.getElementById("submenu-icon");

        const desktopResourcesToggle = document.getElementById("desktop-resources-toggle");
        const desktopResourcesMenu = document.getElementById("desktop-resources-menu");

        // Theme toggle elements
        const themeToggle = document.getElementById("theme-toggle");
        const darkIcon = document.getElementById("dark-icon");
        const lightIcon = document.getElementById("light-icon");
        const mobileThemeToggle = document.getElementById("mobile-theme-toggle");
        const mobileDarkIcon = document.getElementById("mobile-dark-icon");

        let desktopMenuOpen = false;
        let isDarkMode = true; // Default to dark mode

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user has a saved theme preference
            const savedTheme = localStorage.getItem('theme');
            
            if (savedTheme === 'light') {
                // User previously chose light mode
                document.documentElement.classList.remove('dark');
                isDarkMode = false;
                
                // Update icons to show light mode is active
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
                mobileDarkIcon.style.display = 'none';
            } else {
                // Default to dark mode (if no preference or preference is dark)
                document.documentElement.classList.add('dark');
                isDarkMode = true;
                
                // Update icons to show dark mode is active
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
                mobileDarkIcon.style.display = 'block';
            }
        });

        // Theme toggle function
        function toggleTheme() {
            isDarkMode = !isDarkMode;
            
            if (isDarkMode) {
                // Switch to dark mode
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                
                // Update icons with smooth transition
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
                mobileDarkIcon.style.display = 'block';
                
            } else {
                // Switch to light mode
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                
                // Update icons with smooth transition
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
                mobileDarkIcon.style.display = 'none';
            }
        }

        // Event listeners for theme toggle
        themeToggle.addEventListener('click', toggleTheme);
        mobileThemeToggle.addEventListener('click', toggleTheme);

        toggleBtn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
                
            // Toggle icon visibility with smooth transition
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

        let lastScroll = 0;
        const header = document.getElementById('main-header');
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
        
            if (currentScroll > lastScroll && currentScroll > 50) {
                // Scrolling down
                header.classList.add('opacity-0');
                header.classList.remove('opacity-100');
            } else {
                // Scrolling up
                header.classList.add('opacity-100');
                header.classList.remove('opacity-0');
            }
        
            lastScroll = currentScroll;
        });
    </script>

</header>