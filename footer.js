function loadFooter() {
    const footerHTML = `
        <footer class="bg-black text-white py-20">
            <div class="mx-auto px-4 w-[80%]">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-[20%_20%_20%_40%] gap-8">
                    <div>
                        <h3 class="text-lg lg:text-2xl font-semibold mb-4">Discover More</h3>
                        <ul class="space-y-6">
                            <li><a href="https://r4xn.netlify.app/" class="text-[#737373] text-sm hover:text-white transition-all duration-500 hover-underline">R4XN Portfolio</a></li>
                            <li><a href="projects.html" class="text-[#737373] text-sm hover:text-white transition-all duration-500 hover-underline">Front-End Projects</a></li>
                            <li><a href="#iot-solutions" class="text-[#737373] text-sm hover:text-white transition-all duration-500 hover-underline">IoT Solutions</a></li>
                            <li><a href="system.html" class="text-[#737373] text-sm hover:text-white transition-all duration-500 hover-underline">System Design</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg lg:text-2xl font-semibold mb-4">Contact Us</h3>
                        <ul class="space-y-6">
                            <li>
                                <div class="flex items-center space-x-2 text-[#737373] hover:text-white transition-all duration-500">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                    <a href="https://wa.me/601119415142" target="_blank" class="text-sm hover-underline">+60 11-1941 5142</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center space-x-2 text-[#737373] hover:text-white transition-all duration-500">
                                    <i class="fa-brands fa-whatsapp text-lg"></i>
                                    <a href="https://wa.me/601162260866" target="_blank" class="text-sm hover-underline">+60 11-6226 0866</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center space-x-2 text-[#737373] hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-envelope text-lg"></i>
                                    <a href="mailto:walkers287@gmail.com" class="text-sm hover-underline">walkers287@gmail.com</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center space-x-2 text-[#737373] hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-envelope text-lg"></i>
                                    <a href="mailto:natasyahebat@gmail.com" class="text-sm hover-underline">natasyahebat@gmail.com</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg lg:text-2xl font-semibold mb-4">Portfolio</h3>
                        <ul class="space-y-6">
                            <li>
                                <div class="flex items-center space-x-2 text-[#737373] hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-globe text-lg"></i>
                                    <a href="https://rawzeens.rf.gd/" target="_blank" class="text-sm hover-underline">Rawzeens</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center space-x-2 text-[#737373] hover:text-white transition-all duration-500">
                                    <i class="fa-solid fa-globe text-lg"></i>
                                    <a href="https://devxair.netlify.app/" target="_blank" class="text-sm hover-underline">Devxair</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="lg:col-span-2 lg:col-start-4">
                        <h3 class="text-lg lg:text-4xl font-semibold mb-4">R4XN</h3>
                        <p class="text-sm text-[#737373]">
                            R4XN is a tech collaboration between 
                            <span class="italic font-semibold">RAWZEENS TECH</span>, a full-stack developer, and 
                            <span class="italic font-semibold">DEVXAIR</span>, a front-end developer. Together, we deliver innovative solutions in web development, IoT, and system design.
                        </p>
                        <div class="flex space-x-2 mt-6">
                            <a href="https://github.com/um-xair" target="_blank" class="bg-[#171717] w-14 h-14 flex items-center justify-center rounded-[10px] text-white hover:bg-white hover:text-black transition-colors duration-300" aria-label="Follow us on GitHub">
                                <i class="fa-brands fa-github text-3xl"></i>
                            </a>
                            <a href="https://github.com/Rawzeens" target="_blank" class="bg-[#171717] w-14 h-14 flex items-center justify-center rounded-[10px] text-white hover:bg-white hover:text-black transition-colors duration-300" aria-label="Follow us on GitHub">
                                <i class="fa-brands fa-github text-3xl"></i>
                            </a>
                            <a href="https://tiktok.com/@rawxair" target="_blank" class="bg-[#171717] w-14 h-14 flex items-center justify-center rounded-[10px] text-white hover:bg-white hover:text-black transition-colors duration-300" aria-label="Follow us on TikTok">
                                <i class="fa-brands fa-tiktok text-3xl"></i>
                            </a>
                        </div>                    
                    </div>
                </div>
                <div class="mt-8 border-t border-[#171717] pt-4 text-left text-[#737373]">
                    <p class="text-lg">© <span id="current-year"></span> R4XN. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <script>
            document.getElementById("current-year").textContent = new Date().getFullYear();
        </script> 
    `;
    document.body.insertAdjacentHTML('beforeend', footerHTML);
}

loadFooter();