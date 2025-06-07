<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no">
<title>R4XN</title>
<link rel="icon" href="assets/r4xn-black.png" type="image/png" sizes="32x32">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
<style>
    body {
        font-family: "Poppins", sans-serif;
        background-color: #080808;
    }
    html{
        overflow-x: hidden;
    }
    .customfont {
        font-family: "Roboto", sans-serif;
    }
    ::-webkit-scrollbar {
        display: none;
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
</style>
</head>

<body class="max-w-screen overflow-x-hidden">

    <?php include 'header.php';?>

    <section class="min-h-screen px-6 py-10 md:px-32 md:py-10 flex flex-col items-center justify-center text-center">
        <svg width="0" height="0">
            <defs>
                <linearGradient id="gradientText" x1="0%" y1="100%" x2="0%" y2="0%">
                    <stop offset="0%" stop-color="#9CA3AF" />
                    <stop offset="100%" stop-color="#FFFFFF" />
                </linearGradient>
            </defs>
        </svg>
        <div class="relative flex items-center gap-10 mb-3 text-3xl md:text-5xl font-bold uppercase">
            <div class="relative flex icon-group">
                <svg class="main-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="4em" width="4em" fill="none" stroke="url(#gradientText)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m18 16 4-4-4-4"/>
                    <path d="m6 8-4 4 4 4"/>
                    <path d="m14.5 4-5 16"/>
                </svg>
                <svg class="absolute animate-ping ping-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="4em" width="4em" fill="none" stroke="url(#gradientText)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m18 16 4-4-4-4"/>
                    <path d="m6 8-4 4 4 4"/>
                    <path d="m14.5 4-5 16"/>
                </svg>
            </div>
            <h1 class="heading max-w-4xl text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text leading-[1] font-bold text-6xl md:text-[8rem]">
                About R4XN
            </h1>
        </div>
        <p class="description max-w-5xl text-base md:text-lg mt-4 text-gray-400">
            R4XN is a technology company established in 2023, formed through a collaboration between 
            <span class="px-2 py-1 rounded bg-gradient-to-tr from-[#1E1130] via-[#5D3D89] to-[#1E1130] text-white font-semibold">@Rawzeens</span> and 
            <span class="px-2 py-1 rounded bg-gradient-to-tr from-[#1E1130] via-[#5D3D89] to-[#1E1130] text-white font-semibold">@Umxair</span>. 
            Operating under the registered entity Rawzeens Tech Enterprise (SSM-registered), the company specializes in IoT solutions, mobile and web app development, and API integration.
        </p>
    </section>

    <script>
        window.addEventListener("load", () => {
            gsap.from(".icon-group", {
                opacity: 0,
                y: -40,
                duration: 1,
                ease: "power2.out"
            });

            gsap.from(".heading", {
                opacity: 0,
                scale: 0.8,
                duration: 1.2,
                delay: 0.3,
                ease: "back.out(1.7)"
            });

            gsap.from(".description", {
                opacity: 0,
                y: 30,
                duration: 1,
                delay: 0.6,
                ease: "power2.out"
            });
        });
    </script>

    <section class="min-h-screen px-6 py-10 md:px-32 md:py-10 flex flex-col items-center justify-center text-center text-white">
        <h2 id="tech-heading" class="text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text leading-tight font-bold text-8xl mb-16 text-center">
            <div>Technologies</div>
            <div class="text-8xl mt-2">We Work With</div>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-10 w-full max-w-5xl">
            <div id="card1" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-orange-500 transition-colors duration-300 fill-current">
                        <path d="M1.5 0h21l-1.91 21.563L11.977 24l-8.564-2.438L1.5 0zm7.031 9.75l-.232-2.718 10.059.003.23-2.622L5.412 4.41l.698 8.01h9.126l-.326 3.426-2.91.804-2.955-.81-.188-2.11H6.248l.33 4.171L12 19.351l5.379-1.443.744-8.157H8.531z"></path>
                    </svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    HTML5
                </p>
            </div>
            <div id="card2" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-blue-500 transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>CSS3 icon</title><path d="M1.5 0h21l-1.91 21.563L11.977 24l-8.565-2.438L1.5 0zm17.09 4.413L5.41 4.41l.213 2.622 10.125.002-.255 2.716h-6.64l.24 2.573h6.182l-.366 3.523-2.91.804-2.956-.81-.188-2.11h-2.61l.29 3.855L12 19.288l5.373-1.53L18.59 4.414z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    CSS3
                </p>
            </div>
            <div id="card3" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-yellow-400 transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>JavaScript icon</title><path d="M0 0h24v24H0V0zm22.034 18.276c-.175-1.095-.888-2.015-3.003-2.873-.736-.345-1.554-.585-1.797-1.14-.091-.33-.105-.51-.046-.705.15-.646.915-.84 1.515-.66.39.12.75.42.976.9 1.034-.676 1.034-.676 1.755-1.125-.27-.42-.404-.601-.586-.78-.63-.705-1.469-1.065-2.834-1.034l-.705.089c-.676.165-1.32.525-1.71 1.005-1.14 1.291-.811 3.541.569 4.471 1.365 1.02 3.361 1.244 3.616 2.205.24 1.17-.87 1.545-1.966 1.41-.811-.18-1.26-.586-1.755-1.336l-1.83 1.051c.21.48.45.689.81 1.109 1.74 1.756 6.09 1.666 6.871-1.004.029-.09.24-.705.074-1.65l.046.067zm-8.983-7.245h-2.248c0 1.938-.009 3.864-.009 5.805 0 1.232.063 2.363-.138 2.711-.33.689-1.18.601-1.566.48-.396-.196-.597-.466-.83-.855-.063-.105-.11-.196-.127-.196l-1.825 1.125c.305.63.75 1.172 1.324 1.517.855.51 2.004.675 3.207.405.783-.226 1.458-.691 1.811-1.411.51-.93.402-2.07.397-3.346.012-2.054 0-4.109 0-6.179l.004-.056z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    JavaScript
                </p>
            </div>
            <div id="card4" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-purple-600 transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>PHP icon</title><path d="M7.01 10.207h-.944l-.515 2.648h.838c.556 0 .97-.105 1.242-.314.272-.21.455-.559.55-1.049.092-.47.05-.802-.124-.995-.175-.193-.523-.29-1.047-.29zM12 5.688C5.373 5.688 0 8.514 0 12s5.373 6.313 12 6.313S24 15.486 24 12c0-3.486-5.373-6.312-12-6.312zm-3.26 7.451c-.261.25-.575.438-.917.551-.336.108-.765.164-1.285.164H5.357l-.327 1.681H3.652l1.23-6.326h2.65c.797 0 1.378.209 1.744.628.366.418.476 1.002.33 1.752a2.836 2.836 0 0 1-.305.847c-.143.255-.33.49-.561.703zm4.024.715l.543-2.799c.063-.318.039-.536-.068-.651-.107-.116-.336-.174-.687-.174H11.46l-.704 3.625H9.388l1.23-6.327h1.367l-.327 1.682h1.218c.767 0 1.295.134 1.586.401s.378.7.263 1.299l-.572 2.944h-1.389zm7.597-2.265a2.782 2.782 0 0 1-.305.847c-.143.255-.33.49-.561.703a2.44 2.44 0 0 1-.917.551c-.336.108-.765.164-1.286.164h-1.18l-.327 1.682h-1.378l1.23-6.326h2.649c.797 0 1.378.209 1.744.628.366.417.477 1.001.331 1.751zM17.766 10.207h-.943l-.516 2.648h.838c.557 0 .971-.105 1.242-.314.272-.21.455-.559.551-1.049.092-.47.049-.802-.125-.995s-.524-.29-1.047-.29z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    PHP
                </p>
            </div>
            <div id="card5" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" class="w-20 h-20 mx-auto text-white group-hover:text-yellow-400 transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>Python icon</title><path d="M14.31.18l.9.2.73.26.59.3.45.32.34.34.25.34.16.33.1.3.04.26.02.2-.01.13V8.5l-.05.63-.13.55-.21.46-.26.38-.3.31-.33.25-.35.19-.35.14-.33.1-.3.07-.26.04-.21.02H8.83l-.69.05-.59.14-.5.22-.41.27-.33.32-.27.35-.2.36-.15.37-.1.35-.07.32-.04.27-.02.21v3.06H3.23l-.21-.03-.28-.07-.32-.12-.35-.18-.36-.26-.36-.36-.35-.46-.32-.59-.28-.73-.21-.88-.14-1.05L0 11.97l.06-1.22.16-1.04.24-.87.32-.71.36-.57.4-.44.42-.33.42-.24.4-.16.36-.1.32-.05.24-.01h.16l.06.01h8.16v-.83H6.24l-.01-2.75-.02-.37.05-.34.11-.31.17-.28.25-.26.31-.23.38-.2.44-.18.51-.15.58-.12.64-.1.71-.06.77-.04.84-.02 1.27.05 1.07.13zm-6.3 1.98l-.23.33-.08.41.08.41.23.34.33.22.41.09.41-.09.33-.22.23-.34.08-.41-.08-.41-.23-.33-.33-.22-.41-.09-.41.09-.33.22zM21.1 6.11l.28.06.32.12.35.18.36.27.36.35.35.47.32.59.28.73.21.88.14 1.04.05 1.23-.06 1.23-.16 1.04-.24.86-.32.71-.36.57-.4.45-.42.33-.42.24-.4.16-.36.09-.32.05-.24.02-.16-.01h-8.22v.82h5.84l.01 2.76.02.36-.05.34-.11.31-.17.29-.25.25-.31.24-.38.2-.44.17-.51.15-.58.13-.64.09-.71.07-.77.04-.84.01-1.27-.04-1.07-.14-.9-.2-.73-.25-.59-.3-.45-.33-.34-.34-.25-.34-.16-.33-.1-.3-.04-.25-.02-.2.01-.13v-5.34l.05-.64.13-.54.21-.46.26-.38.3-.32.33-.24.35-.2.35-.14.33-.1.3-.06.26-.04.21-.02.13-.01h5.84l.69-.05.59-.14.5-.21.41-.28.33-.32.27-.35.2-.36.15-.36.1-.35.07-.32.04-.28.02-.21V6.07h2.09l.14.01.21.03zm-6.47 14.25l-.23.33-.08.41.08.41.23.33.33.23.41.08.41-.08.33-.23.23-.33.08-.41-.08-.41-.23-.33-.33-.23-.41-.08-.41.08-.33.23z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Python
                </p>
            </div>
            <div id="card6" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-pink-500 transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>Kotlin icon</title><path d="M1.3 24l11.3-11.5L24 24zM0 0h12L0 12.5zM13.4 0L0 14v10l12-12L24 0z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Kotlin
                </p>
            </div>
            <div id="card7" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-[#7952B3] transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>Bootstrap icon</title><path d="M20 0H4C1.793.006.006 1.793 0 4v16c0 2.2 1.8 4 4 4h16c2.2 0 4-1.8 4-4V4c0-2.2-1.8-4-4-4zm-2.187 16.855c-.2.482-.517.907-.923 1.234-.42.34-.952.62-1.607.82-.654.203-1.432.305-2.333.305H6.518v-14h6.802c1.258 0 2.266.283 3.02.86.76.58 1.138 1.444 1.138 2.61 0 .705-.172 1.31-.518 1.81-.344.497-.84.886-1.48 1.156v.046c.854.18 1.515.585 1.95 1.215s.658 1.426.658 2.387c0 .538-.104 1.05-.3 1.528l.025.027zm-2.776-3.45c-.41-.375-.986-.558-1.73-.558H8.985v4.368h4.334c.74 0 1.32-.192 1.73-.58.41-.385.62-.934.62-1.64-.007-.69-.21-1.224-.62-1.59h-.017zm-.6-2.823c.396-.336.59-.817.59-1.444 0-.704-.175-1.204-.53-1.49-.352-.285-.86-.433-1.528-.433h-4v3.863h4c.583 0 1.08-.17 1.464-.496z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Bootstrap
                </p>
            </div>
            <div id="card8" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-[#38B2AC] transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>Tailwind CSS icon</title><path d="M12.001,4.8c-3.2,0-5.2,1.6-6,4.8c1.2-1.6,2.6-2.2,4.2-1.8c0.913,0.228,1.565,0.89,2.288,1.624 C13.666,10.618,15.027,12,18.001,12c3.2,0,5.2-1.6,6-4.8c-1.2,1.6-2.6,2.2-4.2,1.8c-0.913-0.228-1.565-0.89-2.288-1.624 C16.337,6.182,14.976,4.8,12.001,4.8z M6.001,12c-3.2,0-5.2,1.6-6,4.8c1.2-1.6,2.6-2.2,4.2-1.8c0.913,0.228,1.565,0.89,2.288,1.624 c1.177,1.194,2.538,2.576,5.512,2.576c3.2,0,5.2-1.6,6-4.8c-1.2,1.6-2.6,2.2-4.2,1.8c-0.913-0.228-1.565-0.89-2.288-1.624 C10.337,13.382,8.976,12,6.001,12z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Tailwind
                </p>
            </div>
            <div id="card9" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-gray-100 transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>GitHub icon</title><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Github
                </p>
            </div>
            <div id="card10" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-[#007ACC] transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>Visual Studio Code icon</title><path d="M23.15 2.587L18.21.21a1.494 1.494 0 0 0-1.705.29l-9.46 8.63-4.12-3.128a.999.999 0 0 0-1.276.057L.327 7.261A1 1 0 0 0 .326 8.74L3.899 12 .326 15.26a1 1 0 0 0 .001 1.479L1.65 17.94a.999.999 0 0 0 1.276.057l4.12-3.128 9.46 8.63a1.492 1.492 0 0 0 1.704.29l4.942-2.377A1.5 1.5 0 0 0 24 20.06V3.939a1.5 1.5 0 0 0-.85-1.352zm-5.146 14.861L10.826 12l7.178-5.448v10.896z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    VS Code
                </p>
            </div>
            <div id="card11" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-[#21759B] transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>WordPress icon</title><path d="M21.469 6.825c.84 1.537 1.318 3.3 1.318 5.175 0 3.979-2.156 7.456-5.363 9.325l3.295-9.527c.615-1.54.82-2.771.82-3.864 0-.405-.026-.78-.07-1.11m-7.981.105c.647-.03 1.232-.105 1.232-.105.582-.075.514-.93-.067-.899 0 0-1.755.135-2.88.135-1.064 0-2.85-.15-2.85-.15-.585-.03-.661.855-.075.885 0 0 .54.061 1.125.09l1.68 4.605-2.37 7.08L5.354 6.9c.649-.03 1.234-.1 1.234-.1.585-.075.516-.93-.065-.896 0 0-1.746.138-2.874.138-.2 0-.438-.008-.69-.015C4.911 3.15 8.235 1.215 12 1.215c2.809 0 5.365 1.072 7.286 2.833-.046-.003-.091-.009-.141-.009-1.06 0-1.812.923-1.812 1.914 0 .89.513 1.643 1.06 2.531.411.72.89 1.643.89 2.977 0 .915-.354 1.994-.821 3.479l-1.075 3.585-3.9-11.61.001.014zM12 22.784c-1.059 0-2.081-.153-3.048-.437l3.237-9.406 3.315 9.087c.024.053.05.101.078.149-1.12.393-2.325.609-3.582.609M1.211 12c0-1.564.336-3.05.935-4.39L7.29 21.709C3.694 19.96 1.212 16.271 1.211 12M12 0C5.385 0 0 5.385 0 12s5.385 12 12 12 12-5.385 12-12S18.615 0 12 0"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    Wordpress
                </p>
            </div>
            <div id="card12" class="tech-card group relative border border-gray-600 rounded-2xl p-10 transition duration-300 ease-in-out hover:border-white hover:shadow-xl cursor-pointer h-60 flex items-center justify-center">
                <div class="transform transition duration-300 group-hover:-translate-y-2">
                    <svg viewBox="0 0 24 24" role="img" xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mx-auto text-white group-hover:text-[#88CE02] transition-colors duration-300 fill-current"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><title>GreenSock icon</title><path d="M13.473 23.948c-.311-.053-.639-.2-.82-.365a.702.702 0 0 1-.198-.487c0-.18.09-.44.242-.696a2.2 2.2 0 0 0 .209-.465c.03-.113.096-.3.147-.417l.091-.212.024-1.436c.013-.79.037-1.526.053-1.638a4.94 4.94 0 0 1 .253-.962l.084-.209-.123-.073c-.115-.069-.21-.168-.58-.608-.457-.54-1.057-.836-1.908-.94a16 16 0 0 0-.84-.057c-1.252-.058-1.966-.319-2.61-.952-.48-.474-.731-1.025-.833-1.836-.138-1.098-.29-1.663-.57-2.12a2.035 2.035 0 0 0-.237-.315l-.114-.113-.25.16c-.139.088-.329.203-.423.255a8.301 8.301 0 0 0-.896.571c-.696.507-1.459 1.142-1.877 1.562-.188.188-.287.267-.327.262-.288-.043-.066-1.28.396-2.21.595-1.197 1.593-2.303 2.752-3.051.95-.613 2.485-1.456 3.196-1.753 1.26-.527 2.892-1.098 4.01-1.403.236-.064.381-.122.477-.19.22-.153.434-.225.681-.23.206-.003.226-.01.425-.14.235-.152.302-.162.538-.08l.154.055.187-.127.188-.127.27.006c.205.004.299-.007.387-.046.114-.05.118-.056.15-.245.072-.406.08-.61.037-.854-.058-.324-.063-1.584-.008-1.829.059-.266.097-.319.295-.417.297-.147.519-.188 1.032-.188.624 0 .94.084 1.174.31.212.206.248.68.137 1.8-.064.642-.072.823-.055 1.244.01.271.027.524.037.561.017.065.032.069.259.074.221.004.26.014.471.125.204.106.356.234.386.325.006.02.04.021.104.003.118-.035.225.003.309.109.051.065.083.078.203.078.16 0 .216.038.349.234a.598.598 0 0 0 .246.2c.22.11.515.414.63.654.175.362.212.777.101 1.154-.04.14-.04.145.042.282.174.29.237.65.167.954l-.041.18.118.117c.583.583.497 1.531-.215 2.38-.076.09-.165.232-.198.318-.14.363-.294.618-.499.828l-.204.21h-.214c-.21 0-.505-.07-.685-.165-.075-.039-.08-.038-.065.01.05.172.164.87.205 1.255.063.594.044 1.458-.04 1.829a2.85 2.85 0 0 1-.297.785c-.076.126-.083.155-.066.304.024.217-.027.576-.115.813-.104.277-.143.407-.166.546-.02.122-.02.123.066.123.115 0 .175.068.175.199 0 .06-.05.374-.112.7-.171.908-.182 1.013-.184 1.895-.003.909-.004.902.204 1.037l.464.302a6.37 6.37 0 0 0 1.276.656c.653.258.743.324.743.544 0 .095-.019.133-.1.211-.252.243-.744.297-1.737.192a16.668 16.668 0 0 0-1.55-.075c-1.365-.02-1.567-.067-1.745-.408-.102-.195-.084-.45.059-.868a5.86 5.86 0 0 0 .163-.56c.093-.455.03-.993-.188-1.604l-.185-.518c-.186-.528-.286-1.494-.176-1.712.02-.04.054-.05.146-.044l.119.01.096-.204c.075-.158.158-.264.38-.488l.285-.286-.014-.182c-.014-.174-.243-1.027-.276-1.025-.009 0-.073.088-.144.195-.17.258-.552.658-.766.802-.094.064-.172.137-.172.162-.002.103-.094.31-.201.455-.106.143-.113.163-.097.29.024.183-.033.773-.104 1.08a3.482 3.482 0 0 1-.257.665c-.27.56-.472 1.058-.525 1.29a5.534 5.534 0 0 0-.068.673c-.013.264-.04.524-.06.577-.026.075-.027.132-.002.246.018.082.03.266.027.41-.004.2.012.33.07.564.1.397.122.647.073.796-.03.092-.064.129-.166.18-.237.12-.899.185-1.259.123zm.95-.318c.137-.039.252-.159.252-.263 0-.045-.04-.183-.087-.306-.09-.23-.254-.837-.254-.938 0-.054.004-.055.085-.012l.085.045-.002-.11a1.326 1.326 0 0 0-.064-.295c-.074-.223-.084-.228-.554-.254l-.322-.017-.115.113a1.03 1.03 0 0 0-.169.22c-.052.103-.072.255-.038.29.008.009.085 0 .17-.02a.755.755 0 0 1 .155-.029c0 .005-.086.073-.192.151-.47.348-.633.564-.633.837 0 .286.266.508.688.574.29.046.854.054.995.014zm6.354-.465c.117-.142.103-.285-.046-.45a1.223 1.223 0 0 0-.292-.22 3.668 3.668 0 0 1-.328-.195c-.086-.06-.362-.22-.612-.36a8.634 8.634 0 0 1-.527-.306l-.07-.055v-1.075c0-1.06-.002-1.073-.058-1.058-.273.075-.726.109-1.302.098l-.619-.01.088.218c.167.416.254.97.253 1.606 0 .238-.014.326-.073.492a8.78 8.78 0 0 0-.087.249c-.011.035.032.06.194.108.115.035.289.071.387.082l.178.018-.018.181c-.02.205.003.418.054.486.029.038.149.052.69.078.412.02.858.062 1.209.114.305.045.633.084.73.085.162.003.18-.003.249-.086zm-6.252-1.831c.015-.01.037-.222.047-.471.031-.7.057-.8.41-1.589.287-.644.441-1.245.427-1.673l-.008-.254-.107.106c-.227.225-.071-.13.197-.447.203-.24.273-.375.307-.587.014-.086.047-.13.18-.23.502-.385.867-.87 1.103-1.463.056-.141.198-.62.315-1.066.218-.826.335-1.179.44-1.333.04-.059.055-.068.045-.029-.06.235-.08.33-.13.637-.032.191-.09.564-.128.828-.078.532-.156.876-.261 1.144l-.07.178.189.485c.193.497.283.855.302 1.205l.01.192-.154.032a.784.784 0 0 0-.497.314c-.136.19-.208.348-.173.384.015.015.207.05.425.075.342.04.47.042.919.013a5.73 5.73 0 0 0 .579-.056c.042-.015.083-.101.154-.323.162-.51.193-.693.174-1.05l-.016-.317.082-.105c.192-.244.34-.682.4-1.19.075-.626-.06-1.936-.315-3.062-.12-.533-.17-.854-.17-1.09v-.207l-.193.02c-.687.076-1.518.055-2.028-.05-.276-.056-.976-.26-1.13-.329l-.119-.053-.017.135a2.093 2.093 0 0 1-.04.226c-.023.085-.014.102.131.246.18.18.236.276.236.402 0 .075-.045.138-.263.367-.16.168-.364.345-.52.449l-.256.172-.106.376c-.394 1.39-.468 2.649-.21 3.554l.064.226-.125.155c-.47.592-.746 1.041-.788 1.29-.008.05-.048.215-.088.367-.1.386-.178.953-.212 1.566a43.822 43.822 0 0 1-.104 1.539l-.017.152.195.06c.108.033.273.073.367.087.174.028.499.023.548-.008zm3.793-2.148c.303-.018.581-.066.635-.11.023-.018.05-.1.06-.18l.017-.148-.24.053a5.127 5.127 0 0 1-.577.078 9.474 9.474 0 0 1-1.382-.04c-.152-.023-.153-.022-.103.177.037.15.045.154.445.185.266.02.634.016 1.145-.015zm-.255-.68c.396-.033.894-.118.99-.17.031-.017.061-.074.072-.139l.036-.208.018-.099-.096.02c-.433.091-.762.118-1.42.118-.639 0-.712-.006-.88-.063a.603.603 0 0 0-.206-.048c-.028.017-.03.24-.005.395.015.095.03.111.125.138.297.08.796.101 1.366.055zm-7.249-3.523c1-.286 2.308-1.137 3.377-2.197.35-.348.39-.397.437-.55l.064-.199c.007-.016-.056-.045-.138-.065-.216-.052-.377-.174-.379-.288 0-.066-.04-.132-.15-.255-.187-.208-.233-.298-.253-.497l-.016-.158-.216-.158c-.124-.09-.564-.332-1.028-.565a14.706 14.706 0 0 1-1.039-.561c-.39-.264-.653-.576-.8-.947-.029-.076-.063-.138-.075-.138-.033 0-.91.85-1.286 1.247-.924.977-1.563 1.825-1.964 2.61-.1.195-.21.425-.242.511l-.06.157.073.26c.04.143.13.373.2.51.246.484.541.777 1.024 1.015.52.257.98.346 1.75.337.416-.005.535-.016.721-.07zM2.921 11.56c.657-.582 1.563-1.22 2.674-1.882.727-.433 3.533-1.837 4.826-2.414.234-.105.446-.201.47-.215.023-.013.091-.18.151-.369.276-.869.412-1.041.908-1.153.063-.014.078-.034.078-.099 0-.11.078-.374.145-.491.05-.09.051-.095.005-.082-.027.009-.19.056-.363.106-2.38.687-4.462 1.6-6.26 2.742-1.92 1.22-3.022 2.68-3.343 4.429l-.024.13.232-.231c.127-.127.353-.339.501-.47zm17.677.33c.19-.098.487-.548.597-.902a.812.812 0 0 1 .182-.338c.292-.32.531-.905.532-1.304 0-.62-.349-.859-.863-.592-.064.033-.121.055-.127.048-.029-.029.117-.17.29-.28.233-.15.277-.24.277-.566 0-.271-.069-.493-.225-.733-.07-.105-.098-.177-.087-.218l.072-.264c.096-.347.061-.744-.088-.997-.227-.385-.506-.573-.894-.602l-.225-.017.134.147c.392.429.583.963.508 1.42-.039.232-.077.28-.303.372l-.13.053.015.179c.026.295-.024.508-.212.905-.093.198-.164.376-.157.395.025.067.28.195.483.244l.2.048-.125.04c-.161.053-.198.126-.199.397 0 .245.035.346.217.616.08.118.135.237.144.308.015.111.01.118-.08.151a1.52 1.52 0 0 1-.31.05c-.367.023-.395.033-.558.19-.15.143-.153.151-.153.307 0 .215.08.645.132.704.064.073.436.255.58.283a.544.544 0 0 0 .373-.044zm-5.41-.294c.239-.187.425-.374.532-.535.065-.098.069-.117.036-.197-.052-.127-.411-.45-.611-.547a1.463 1.463 0 0 0-.418-.12l-.246-.034-.154-.192c-.314-.39-.49-.693-.742-1.282-.216-.502-.409-.795-.713-1.082-.125-.118-.217-.215-.206-.215.012 0 .161.073.333.163.288.151.321.162.451.146.465-.056.873-.591 1.024-1.34.043-.214.127-.429.218-.557.004-.005.182.115.395.267.534.38.652.436.955.448.202.008.314-.008.672-.094.765-.186 1.594-.262 2.1-.193.285.039.692.163 1 .306.301.14.359.148.457.062.08-.07.081-.08.08-.342-.002-.386-.144-.804-.36-1.053-.11-.129-.415-.34-.72-.501a12.227 12.227 0 0 1-.327-.178c-.142-.083-.309-.073-.752.044-.515.135-.652.133-1.243-.018-.499-.128-.532-.13-1.022-.053-.261.041-.918.017-1.138-.041a4.228 4.228 0 0 1-.325-.104c-.19-.071-.522-.083-.822-.03-.6.107-1.054.43-1.218.87-.084.224-.073.329.042.401.177.112.416.421.416.54 0 .01-.056-.041-.125-.113-.166-.174-.32-.251-.5-.251a.62.62 0 0 0-.55.362 3.846 3.846 0 0 0-.133.42c-.11.402-.206.612-.412.892-.186.253-.211.366-.146.651.164.719.448 1.011 1.639 1.686.625.354.948.553 1.182.729.179.133.184.14.168.243-.037.233.112.49.406.695.075.052.122.107.122.143 0 .072.196.17.343.17.08 0 .145-.034.313-.166zm4.815-1.314c.454.013.479-.019.235-.301-.254-.295-.323-.532-.23-.794l.037-.102-.159-.078a1.167 1.167 0 0 0-.163-.072 7.965 7.965 0 0 0-.079.31c-.058.24-.13.856-.131 1.12 0 .023.038.013.105-.028.092-.055.14-.062.385-.055zm-1.518-.025c.273-.015.556-.036.63-.046l.134-.018.017-.452c.023-.64.115-1.018.393-1.622.198-.43.281-.807.24-1.086-.018-.122-.025-.13-.188-.201a4.295 4.295 0 0 0-.934-.253c-.31-.045-1.214-.019-1.555.045-.14.026-.397.08-.57.121-.426.1-.652.087-.976-.055-.32-.141-.524-.268-.717-.448-.151-.141-.165-.148-.206-.101-.03.035-.047.14-.057.368-.014.347-.066.53-.22.777a.832.832 0 0 0-.086.16c0 .012.119.146.264.296.499.52.744.654 1.138.624.13-.01.22-.006.22.01 0 .029-.254.23-.361.286-.048.025-.06.053-.052.113l.06.408c.036.236.064.343.1.375.093.08.577.33.88.452.401.163.617.221.924.247.373.031.381.031.922 0zm-3.23-.427c.008-.015-.022-.24-.07-.502-.063-.356-.098-.485-.137-.518-.03-.024-.173-.128-.319-.231a5.503 5.503 0 0 1-.537-.452l-.271-.264-.142.075-.154.083a.7.7 0 0 0 .064.176c.04.093.126.323.19.51.165.489.291.723.616 1.148l.124.161.23.036c.126.02.257.046.291.059.056.021.063.012.08-.115a.755.755 0 0 1 .034-.166zm4.948-4.86c.2-.019.234-.05.147-.138-.052-.053-.179-.059-.28-.012-.068.031-.082.024-.187-.09-.11-.122-.116-.125-.254-.108-.125.015-.144.01-.16-.041a1.078 1.078 0 0 0-.18-.226.842.842 0 0 0-.325-.23c-.243-.092-.378-.082-.609.045-.667.365-.64.361-1.283.197-.603-.154-1.054-.307-1.197-.407-.12-.083-.379-.17-.593-.2-.16-.022-.264.013-.416.14l-.08.066.072.128c.102.181.092.191-.062.06-.163-.14-.357-.234-.485-.235-.089 0-.355.115-.387.168-.009.014.073.033.181.043.115.01.271.048.376.093.272.116.414.154.664.18.273.026.771-.005.828-.053.029-.025.115-.029.296-.015.238.018.337.04.903.193.328.09.558.083.949-.026.18-.05.406-.1.501-.11.16-.017.192-.01.44.103.4.18.61.296.728.4.117.1.13.103.413.076zm-2.228-.684l.238-.124-.005-.245a11.964 11.964 0 0 0-.022-.495l-.017-.251-.238.184c-.141.11-.278.192-.339.203-.171.033-1.016 0-1.096-.042a2.31 2.31 0 0 1-.291-.259l-.22-.22-.003.13c0 .07-.011.192-.024.268-.022.138-.02.142.13.333.084.107.152.202.152.212 0 .076 1.128.426 1.382.43.081 0 .184-.036.353-.124zm-.452-.889c.282-.054.42-.258.523-.773.11-.554.166-1.425.113-1.797-.047-.334-.419-.485-1.136-.461-.444.015-.628.059-.779.187-.094.08-.096.087-.137.457-.08.711.036 1.724.24 2.105.097.183.184.25.365.28.203.035.635.036.811.002z"></path></g></svg>
                </div>
                <p class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    GreenSock
                </p>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.from("#tech-heading", {
            scrollTrigger: {
                trigger: "#tech-heading",
                start: "top 90%",
                end: "top 50%",
                scrub: true,
            },
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out",
        });

        gsap.utils.toArray(".tech-card").forEach((card) => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: "top 80%",
                    end: "top 40%",
                    scrub: true,
                },
                opacity: 0,
                y: 30,
                duration: 0.6,
                ease: "power2.out",
            });
        });
    </script>

    <section class="min-h-[80vh] px-6 py-10 md:px-32 md:py-10 flex flex-col items-center justify-center text-center text-white">
        <div id="quoteBox" class="transition-all duration-700 opacity-100 transform translate-y-0 
                text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text 
                leading-tight font-bold text-4xl md:text-7xl mx-auto text-center">
                “The only way to do great work is to love what you do.”
        </div>
    </section>

    <script>
        const quotes = [
            "“Talk is cheap. Show me the code.” – Linus Torvalds",
            "“It works on my machine.” – Every Developer Ever",
            "“Code never lies, comments sometimes do.”",
            "“First, solve the problem. Then, blame JavaScript.”",
            "“I have not failed. I’ve just found 10,000 ways that won’t compile.”",
            "“Why do Java developers wear glasses? Because they don’t C#.”",
            "“Programmer: A machine that turns coffee into code.”",
            "“There’s no place like 127.0.0.1.”",
            "“Computers are fast; developers keep them slow.”"
        ];

        const quoteBox = document.getElementById("quoteBox");
        let index = 0;

        setInterval(() => {
            quoteBox.classList.remove("opacity-100", "translate-y-0");
            quoteBox.classList.add("opacity-0", "-translate-y-4");
            
            setTimeout(() => {
                index = (index + 1) % quotes.length;
                quoteBox.textContent = quotes[index];

                quoteBox.classList.remove("opacity-0", "-translate-y-4");
                quoteBox.classList.add("opacity-100", "translate-y-0");
            }, 700);
        }, 4000);
    </script>

    <section class="min-h-screen px-6 py-10 md:px-32 md:py-10 flex flex-col items-center justify-center text-white">
        <h2 class="work-heading text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text leading-tight font-bold text-8xl mb-16 text-center">
            <div>Let’s Work Together</div>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="work-card group flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300">
                <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 01</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="17" cy="4" r="2"/>
                        <path d="M15.59 5.41 5.41 15.59"/>
                        <circle cx="4" cy="17" r="2"/>
                        <path d="M12 22s-4-9-1.5-11.5S22 12 22 12"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-bold group-hover:text-black">UI/UX <br>Design</h3>
                <p class="text-gray-400 text-sm group-hover:text-black">Designing clean, intuitive, and user-friendly interfaces that elevate the experience.</p>
            </div>
            <div class="work-card group flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300">
                <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 02</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="18" height="12" x="3" y="4" rx="2" ry="2"/>
                        <line x1="2" x2="22" y1="20" y2="20"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-bold group-hover:text-black">Website and App Design</h3>
                <p class="text-gray-400 text-sm group-hover:text-black">Modern, responsive designs that look great and work seamlessly on all devices.</p>
            </div>
            <div class="work-card group flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300">
                <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 03</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20v2"/><path d="M12 2v2"/><path d="M17 20v2"/><path d="M17 2v2"/><path d="M2 12h2"/><path d="M2 17h2"/><path d="M2 7h2"/><path d="M20 12h2"/><path d="M20 17h2"/><path d="M20 7h2"/><path d="M7 20v2"/><path d="M7 2v2"/>
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <rect x="8" y="8" width="8" height="8" rx="1"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-bold group-hover:text-black">IoT <br>Solutions</h3>
                <p class="text-gray-400 text-sm group-hover:text-black">Smart systems that connect devices, collect data, and automate tasks.</p>
            </div>
            <div class="work-card group flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300">
                <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 04</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-bold group-hover:text-black">Website Development</h3>
                <p class="text-gray-400 text-sm group-hover:text-black">Fast, secure, and scalable websites tailored to your business needs.</p>
            </div>
            <div class="work-card group flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300">
                <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 05</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="10" height="14" x="3" y="8" rx="2"/>
                        <path d="M5 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2h-2.4"/>
                        <path d="M8 18h.01"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-bold group-hover:text-black">Mobile App Development</h3>
                <p class="text-gray-400 text-sm group-hover:text-black">Fast, secure, and scalable websites tailored to your business needs.</p>
            </div>
            <div class="work-card group flex flex-col bg-[#121212] p-10 space-y-8 rounded-3xl hover:bg-white hover:text-black transition duration-300">
                <span class="text-sm text-gray-400 mb-2 group-hover:text-black">/ 06</span>
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 group-hover:stroke-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="8" x="2" y="2" rx="2" ry="2"/>
                        <rect width="20" height="8" x="2" y="14" rx="2" ry="2"/>
                        <line x1="6" x2="6.01" y1="6" y2="6"/>
                        <line x1="6" x2="6.01" y1="18" y2="18"/>
                    </svg>
                </div>
                <h3 class="text-4xl font-bold group-hover:text-black">Hosting & Deploy Services</h3>
                <p class="text-gray-400 text-sm group-hover:text-black">We deploy and host your site with speed, security, and zero hassle.</p>
            </div>
        </div>
    </section>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.from(".work-heading", {
            scrollTrigger: {
                trigger: ".work-heading",
                start: "top 90%",
                end: "top 70%",
                scrub: true,
            },
            opacity: 0,
            y: 50,
            duration: 1,
            ease: "power3.out",
        });
        
        gsap.utils.toArray(".work-card").forEach((card, i) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: `top+=${i * 100} bottom`,
                end: `top+=${i * 100 + 200} bottom`,
                scrub: true,
            },
            opacity: 0,
            y: 30,
            duration: 1,
            ease: "power2.out",
        });
        });
    </script>

    <?php include 'footer.php';?>

</body>
</html>