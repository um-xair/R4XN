<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>R4XN | Project</title>
<link rel="icon" href="assets/r4xn-black.png" type="image/png" sizes="32x32">
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">    <script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
<style>
    body {
    font-family: 'Outfit', sans-serif;
    background-color: #080808;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    overflow: auto;
    }
    body::before {
    --size: 45px;
    --line: rgba(255, 255, 255, 0.115);
    content: '';
    position: fixed;
    inset: 0;
    background:
        linear-gradient(90deg, var(--line) 1px, transparent 1px var(--size)) 50% 50% / var(--size) var(--size),
        linear-gradient(var(--line) 1px, transparent 1px var(--size)) 50% 50% / var(--size) var(--size);
    mask: linear-gradient(-15deg, transparent 50%, white);
    pointer-events: none;
    z-index: -1;
    }
    ::-webkit-scrollbar {
        display: none;
    }
    html {
        scroll-behavior: smooth;
    }
</style>
</head>
<body class="font-[Outfit] bg-[#080808] m-0 flex flex-col items-center justify-start text-white overflow-auto">

    <?php include 'header.php';?>

    <!-- Link-wrapped cards with animation class -->
    <section class="min-h-screen px-6 py-32 flex flex-col items-center justify-center text-white">
        <h2 class="text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text leading-tight font-bold text-8xl mb-16 text-center">
            <div>Our Digital Showcase</div>
        </h2>
        <div id="projectCards" class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl w-full">
            <a href="frontend.php" class="group card-link">
                <div class="flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300 h-full">
                    <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 01</span>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="17" cy="4" r="2"/>
                            <path d="M15.59 5.41 5.41 15.59"/>
                            <circle cx="4" cy="17" r="2"/>
                            <path d="M12 22s-4-9-1.5-11.5S22 12 22 12"/>
                        </svg>
                    </div>
                    <h3 class="text-4xl font-bold group-hover:text-black">Frontend Projects</h3>
                    <p class="text-gray-400 text-sm group-hover:text-black">Bringing ideas to life through engaging, user-first frontend experiences.</p>
                </div>
            </a>
            <a href="system.php" class="group card-link">
                <div class="flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300 h-full">
                    <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 02</span>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="12" x="3" y="4" rx="2" ry="2"/>
                            <line x1="2" x2="22" y1="20" y2="20"/>
                        </svg>
                    </div>
                    <h3 class="text-4xl font-bold group-hover:text-black">UI/UX System Design</h3>
                    <p class="text-gray-400 text-sm group-hover:text-black">Merging aesthetics with logic to deliver high-performing systems.</p>
                </div>
            </a>
            <a href="iot.php" class="group card-link">
                <div class="flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300 h-full">
                    <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 03</span>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20v2"/><path d="M12 2v2"/><path d="M17 20v2"/><path d="M17 2v2"/><path d="M2 12h2"/><path d="M2 17h2"/><path d="M2 7h2"/><path d="M20 12h2"/><path d="M20 17h2"/><path d="M20 7h2"/><path d="M7 20v2"/><path d="M7 2v2"/>
                            <rect x="4" y="4" width="16" height="16" rx="2"/>
                            <rect x="8" y="8" width="8" height="8" rx="1"/>
                        </svg>
                    </div>
                    <h3 class="text-4xl font-bold group-hover:text-black">IoT Smart Solutions</h3>
                    <p class="text-gray-400 text-sm group-hover:text-black">Connecting the physical and digital worlds through intelligent automation.</p>
                </div>
            </a>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        gsap.from("h2", {
            opacity: 0,
            y: -40,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".card-link", {
            opacity: 0,
            y: 50,
            duration: 1,
            delay: 0.3,
            stagger: 0.2,
            ease: "power2.out"
        });

        gsap.from(".card-link h3, .card-link p", {
            opacity: 0,
            y: 30,
            duration: 1,
            delay: 0.6,
            stagger: 0.15,
            ease: "power2.out"
        });
    </script>

    <?php include 'footer.php';?>

</body>
</html>