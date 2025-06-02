document.addEventListener("DOMContentLoaded", () => {
    const headerHTML = `
        <div class="flex justify-center text-white">
            <header id="header" class="mt-10 bg-[#1B1B1B] w-[90%] lg:w-[85%] flex flex-col items-center justify-between px-8 py-4 
                    lg:rounded-full rounded-[30px] fixed z-[999] transition-opacity duration-500
                    bg-opacity-100 lg:bg-opacity-10 lg:backdrop-blur-md">
                <div class="flex items-center w-full justify-between">
                    <div class="flex items-center">
                        <div class="ml-3">
                            <a href="index.html" class="text-lg font-semibold">
                                R4XN
                            </a>
                        </div>
                    </div>
                    <nav class="hidden lg:flex items-center space-x-6">
                        <a href="projects.html" class="text-md">Projects</a>
                        <a href="discover.html" class="text-md">Discover</a>
                        <div class="relative">
                            <button id="desktop-resources-btn" class="text-md flex items-center space-x-2 focus:outline-none">
                                <span>Resources</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div id="desktop-resources-menu"
                                class="absolute right-0 mt-8 w-[750px] bg-[#0A0A0A] text-white rounded-[20px] shadow-lg opacity-0 scale-95 translate-y-2 transition-all duration-300 ease-in-out pointer-events-none p-4 backdrop-blur-md backdrop-filter hidden">
                                <div class="grid grid-cols-3 w-full">
                                    <a href="projects.html" class="block p-3 hover:bg-neutral-900 rounded-md transition-all duration-300">
                                        <h3 class="text-md font-semibold">Front-End Projects</h3>
                                        <p class="text-xs text-gray-400">Interactive web applications with modern UI/UX design.</p>
                                    </a>
                                    <a href="#" class="block p-3 hover:bg-neutral-900 rounded-md transition-all duration-300">
                                        <h3 class="text-md font-semibold">IoT Solutions</h3>
                                        <p class="text-xs text-gray-400">Smart devices integration for real-time monitoring.</p>
                                    </a>
                                    <a href="system.html" class="block p-3 hover:bg-neutral-900 rounded-md transition-all duration-300">
                                        <h3 class="text-md font-semibold">System Design</h3>
                                        <p class="text-xs text-gray-400">Scalable architectures for efficient software and hardware systems.</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a href="#about" class="text-md">About</a>
                    </nav>
                    <button class="lg:hidden text-white" id="menu-btn">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>

                <div class="w-full mt-4 backdrop-blur-lg bg-opacity-10 text-white hidden" id="mobile-menu">
                    <nav class="flex flex-col space-y-4 py-4 lg:hidden">
                        <a href="projects.html" class="text-md py-2">Projects</a>
                        <a href="#discover" class="text-md py-2">Discover</a>
                        <div class="relative">
                            <button id="mobile-resources-btn" class="text-md flex items-center space-x-1 py-2">
                                <span>Resources</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            <div id="mobile-resources-menu" class="hidden flex flex-col space-y-2 bg-[#0A0A0A] text-white p-2 rounded-md">
                                <a href="projects.html" class="text-sm py-2 block hover:bg-neutral-900 p-4">Front-End Projects</a>
                                <a href="#" class="text-sm py-2 block hover:bg-neutral-900 p-4">IoT Solutions</a>
                                <a href="system.html" class="text-sm py-2 block hover:bg-neutral-900 p-4">System Design</a>
                            </div>
                        </div>
                        <a href="#about" class="text-md py-2">About</a>
                    </nav>
                </div>
            </header>
        </div>
    `;

    document.body.insertAdjacentHTML("afterbegin", headerHTML);
    document.getElementById("menu-btn").addEventListener("click", function () {
        document.getElementById("mobile-menu").classList.toggle("hidden");
    });

    document.getElementById("mobile-resources-btn").addEventListener("click", function () {
        document.getElementById("mobile-resources-menu").classList.toggle("hidden");
    });

    const resourcesBtn = document.getElementById("desktop-resources-btn");
    const resourcesMenu = document.getElementById("desktop-resources-menu");

    resourcesBtn.addEventListener("click", function (event) {
        event.stopPropagation();
        const isHidden = resourcesMenu.classList.contains("hidden");
        
        if (isHidden) {
            resourcesMenu.classList.remove("hidden", "opacity-0", "scale-95", "translate-y-2", "pointer-events-none");
            resourcesMenu.classList.add("opacity-100", "scale-100", "translate-y-0");
        } else {
            resourcesMenu.classList.add("hidden", "opacity-0", "scale-95", "translate-y-2", "pointer-events-none");
            resourcesMenu.classList.remove("opacity-100", "scale-100", "translate-y-0");
        }
    });

    document.addEventListener("click", function (event) {
        if (!resourcesBtn.contains(event.target) && !resourcesMenu.contains(event.target)) {
            resourcesMenu.classList.add("hidden", "opacity-0", "scale-95", "translate-y-2", "pointer-events-none");
            resourcesMenu.classList.remove("opacity-100", "scale-100", "translate-y-0");
        }
    });

    const header = document.getElementById("header");
    let lastScrollY = window.scrollY;

    window.addEventListener("scroll", () => {
        if (window.scrollY > lastScrollY) {
            header.style.opacity = "0";
        } else {
            header.style.opacity = "1";
        }
        lastScrollY = window.scrollY;
    });
});