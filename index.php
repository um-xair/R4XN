<?php
    require 'dashboard/config.php';

    $images = [];
    $result = $conn->query("SELECT image_path FROM project_images ORDER BY created_at DESC");

    while ($row = $result->fetch_assoc()) {
        $images[] = $row['image_path'];
    }

    // Get current project
    $stmt = $conn->prepare("SELECT image_path, link_url FROM current_project LIMIT 1");
    $stmt->execute();
    $stmt->bind_result($image_path, $link_url);
    $stmt->fetch();
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no">
<title>R4XN</title>
<link rel="icon" href="assets/r4xn-black.png" type="image/png" sizes="32x32">
<link href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@200,300,400,500,600,700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                fontFamily: {
                    'poppins': ['Poppins', 'sans-serif'],
                    'roboto': ['Roboto', 'sans-serif'],
                    'clash-grotesk': ['Clash Grotesk', 'sans-serif']
                }
            }
        }
    }
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
<style>
    body {
        font-family: 'Clash Grotesk', sans-serif;
        background-color: #f4f4f4;
    }
    html{
        overflow-x: hidden;
    }
    .{
        font-family: "Roboto", sans-serif;
    }
    ::-webkit-scrollbar {
        display: none;
    }
    body::before {
        --size: 45px;
        --line: rgba(0, 0, 0, 0.1);
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
    
    /* Dark mode styles */
    .dark body {
        background-color: #080808;
    }
    
    .dark body::before {
        --line: rgba(255, 255, 255, 0.42);
    }
    
    .wrapper {
        display: flex;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Responsive grid */
        gap: 16px; /* Space between images */
        padding: 0;
        margin: 0;
        list-style: none;
    }
    li {
        flex-shrink: 0;
        width: clamp(500px, 60vw, 800px);
        padding-right: 1rem;
    }
    img {
        width: 100%;
        height: auto;
        background: #f0f0f0;
    }
</style>
</head>

<body class="max-w-screen overflow-x-hidden bg-[#f4f4f4] dark:bg-[#080808]">

    <?php include 'header.php';?>

    <section class="min-h-screen px-6 py-10 md:px-32 md:py-10 flex items-center justify-start w-full">
        <h1 id="animatedText" class="max-w-4xl text-transparent bg-gradient-to-t from-gray-600 to-black dark:from-gray-400 dark:to-white bg-clip-text leading-[1] font-bold text-6xl md:text-[8rem] lg:text-[10rem]">
            R4XN Projects & Solutions.
        </h1>
    </section>
        
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const textElement = document.getElementById("animatedText");
            const text = textElement.textContent;
    
            gsap.registerPlugin(TextPlugin);
    
            textElement.textContent = "";
    
            gsap.to(textElement, {
                duration: 3,
                text: text,
                ease: "power2.out",
                delay: 0.5,
            });
        });
    </script>
    
    <?php
        $result = $conn->query("SELECT image_path FROM project_images ORDER BY created_at ASC LIMIT 10");
        $images = array_map(function($path) {
            return "dashboard/" . $path;
        }, array_column($result->fetch_all(MYSQLI_ASSOC), 'image_path'));
    ?>
    
    <script>
        const w = 1240;
        const h = 874;
        const images = <?= json_encode($images) ?>;

        document.write([...Array(2)].map((_, sectionIndex) => `
            <section class="demo-gallery overflow-x-auto w-screen py-4">
                <ul class="wrapper flex gap-4">
                    ${images.slice(sectionIndex * 5, (sectionIndex + 1) * 5).map((img) => `
                    <li><img src="${img}" width="${w}" height="${h}" alt="Project Image"></li>`).join('')}
                </ul>
            </section>
        `).join(''));
    </script>
        
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.utils.toArray('section').forEach((section, index) => {
                const wrapper = section.querySelector('.wrapper');
                if (wrapper) {
                    const [x, xEnd] = index % 2
                        ? ['100%', (wrapper.scrollWidth - section.offsetWidth) * -1]
                        : [wrapper.scrollWidth * -1, 0];

                    gsap.fromTo(wrapper, { x }, {
                        x: xEnd,
                        scrollTrigger: {
                            trigger: section,
                            scrub: 0.5,
                        },
                    });
                }
            });
        });
    </script>

    <style>
        .animate-border {
            background: linear-gradient(270deg, #4D4D4D, #737373, #F4F4F4, #737373);
            background-size: 300% 300%;
            animation: gradientMove 3s ease infinite;
            mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            mask-composite: exclude;
            -webkit-mask-composite: xor;
        }
        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>

    <section class="min-h-screen px-6 py-10 flex flex-col items-center justify-center text-center w-full space-y-20">
        <h2 id="revealText" class="max-w-5xl text-2xl md:text-5xl font-medium text-black dark:text-white">
            We transform your ideas into <br>powerful digital experiences—designed with purpose, built with precision, and marketed with transparency, efficiency, and measurable impact at every step of the journey.
        </h2>
        <div class="flex flex-col lg:flex-row gap-10 justify-center w-full max-w-6xl">
            <div class="flex flex-col gap-8 md:gap-14">
                <div class="box left-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight">5+ Years</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left">
                            Practical experience in developing and optimizing front-end and back-end systems.
                        </p>
                    </div>
                </div>
                <div class="box left-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight">120+ Interfaces</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left">
                            Developed and optimized 120+ responsive user interfaces.
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-14 lg:mt-32">
                <div class="box right-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight">10+ Technology</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left">
                            Adaptable to a variety of modern tools and frameworks, ensuring seamless project execution.
                        </p>
                    </div>
                </div>                
                <div class="box right-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight">99% Feedback</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left">
                            Maintained a 99% approval rating by consistently meeting deadlines and project goals.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
        gsap.registerPlugin(ScrollTrigger);

        // 1. Text Reveal Animation (Preserve Line Breaks Neatly)
        const textElement = document.getElementById("revealText");
        if (textElement) {
            const content = textElement.innerHTML;
            const wordsWithBreaks = content.split(/(<br>|&nbsp;|\s+)/).map(word => {
                if (word === "<br>") return "<br>";
                return word.trim() ? `<span style="opacity:0; display:inline;">${word}</span>` : word;
            }).join("");
            textElement.innerHTML = wordsWithBreaks;
            // Animate words on scroll (Neat Fade-In Effect)
            gsap.to("#revealText span", {
                opacity: 1,
                stagger: 0.5,
                duration: 4.0,
                ease: "power2.inOut",
                scrollTrigger: {
                    trigger: textElement,
                    start: "top 70%",
                    end: "top 20%",
                    scrub: 2,
                },
            });
        }
        // 2. Left and Right Box Animation (Sequential Entry with Delay)
        const boxes = [
            document.querySelectorAll(".left-box")[0], // 5+ Years
            document.querySelectorAll(".right-box")[0], // 500+
            document.querySelectorAll(".left-box")[1], // 10+
            document.querySelectorAll(".right-box")[1] // 99%
        ];
        boxes.forEach((box, index) => {
            if (!box) return;
            const isLeftBox = box.classList.contains("left-box");
            gsap.fromTo(
                box,
                {
                    opacity: 0,
                    x: isLeftBox ? -450 : 450,
                    rotate: isLeftBox ? -10 : 10,
                },
                {
                    opacity: 1,
                    x: 0,
                    rotate: 0,
                    duration: 2,
                    ease: "power3.out",
                    scrollTrigger: {
                        trigger: box,
                        start: "top 90%",
                        end: "top 40%",
                        scrub: true,
                    },
                    delay: index * 1.5,
                }
            );
        });
    });
    </script>

    <section class="min-h-screen px-6 py-10 flex flex-col items-center justify-center text-center w-full space-y-12">
        <h2 id="projectTitle" class="max-w-7xl text-4xl md:text-7xl font-semibold leading-tight opacity-0 translate-y-20 text-black dark:text-white">
            Behind the Code<br>A Peek Into My Present Creation.
        </h2>
        <p id="projectDesc" class="text-sm md:text-xl max-w-4xl leading-tight text-gray-600 dark:text-[#737373] opacity-0 translate-y-20">
            Our latest project focuses on building a seamless, user-friendly platform that prioritizes efficiency and transparency.
        </p>
        <?php if ($image_path && $link_url): ?>
            <a href="<?= htmlspecialchars($link_url) ?>" target="_blank" rel="noopener noreferrer">
                <img id="projectImage" src="dashboard/<?= htmlspecialchars($image_path) ?>" alt="Project Image" class="max-w-full w-full md:w-4/5 h-auto mx-auto rounded-[30px] shadow-lg opacity-0 rotate-12 scale-75">
            </a>
        <?php else: ?>
            <p>No current project image available.</p>
        <?php endif; ?>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.to("#projectTitle", {
                opacity: 1,
                y: 0,
                duration: 1.5,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#projectTitle",
                    start: "top 90%",
                    end: "top 50%",
                    scrub: 1,
                },
            });
            gsap.to("#projectDesc", {
                opacity: 1,
                y: 0,
                duration: 1.5,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#projectDesc",
                    start: "top 85%",
                    end: "top 50%",
                    scrub: 1,
                },
            });
            gsap.to("#projectImage", {
                opacity: 1,
                rotate: 0,
                scale: 1,
                duration: 2,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#projectImage",
                    start: "top 75%",
                    end: "top 40%",
                    scrub: 1,
                },
            });
        });
    </script>

    <section class="min-h-screen px-6 py-10 flex flex-col items-center justify-center text-center w-full space-y-16">
        <h2 id="servicesTitle" class="text-4xl md:text-[10rem] font-semibold leading-tight opacity-0 translate-y-10 text-black dark:text-white">
            Services
        </h2>
        <p id="shortDesc" class="text-sm md:text-xl max-w-2xl text-gray-600 dark:text-[#737373] opacity-0 translate-y-10">
            Explore the services we provide, crafted to bring your ideas to life with precision and innovation.
        </p>
        <div id="verticalLine" class="h-80 w-0.5 bg-black dark:bg-white opacity-0 scale-y-0"></div>
        <p id="longDesc" class="text-lg md:text-3xl max-w-2xl opacity-0 translate-y-10 text-black dark:text-white">
            We go beyond simple requests like 'I need a website.' Our process starts by understanding your true objective—whether it's gaining organic users or driving conversions—and delivering a complete solution from A to Z.
        </p>
        <div id="servicesGrid" class="flex flex-wrap gap-8 w-full max-w-6xl mx-auto">
            <div id="section-1" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-1 relative overflow-hidden">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white">Website</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]">
                        We create more than just websites—we design powerful, results-driven platforms that consistently deliver strong sales and marketing outcomes.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/laptop.png" alt="Website Showcase" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto">
                </div>
            </div>
            <div id="section-2" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-2 relative overflow-hidden">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white">Mobile Apps</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]">
                        Our expertise in UX and user engagement ensures mobile applications that offer intuitive, smooth, and memorable user experiences.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/phone.png" alt="Mobile App Showcase" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto">
                </div>
            </div>
            <div id="section-3" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-3 relative overflow-hidden">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white">Development</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]">
                        From concept to execution, we handle your product's development with precision—optimizing resources and ensuring smooth, scalable performance.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/development.png" alt="Development Showcase" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto">
                </div>
            </div>
            <div id="section-4" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-4 relative overflow-hidden">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white">Strategy</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]">
                        We design end-to-end strategies that turn innovative ideas into thriving digital products—driving market relevance and sustainable growth.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/strategy.png" alt="Strategy Showcase" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto">
                </div>
            </div>
        </div>        
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            gsap.registerPlugin(ScrollTrigger);

            gsap.to("#servicesTitle", {
                opacity: 1,
                y: 0,
                duration: 1.5,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#servicesTitle",
                    start: "top 90%",
                    end: "top 50%",
                    scrub: 1,
                },
            });

            gsap.to("#shortDesc", {
                opacity: 1,
                y: 0,
                duration: 1.5,
                delay: 0.2,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#shortDesc",
                    start: "top 85%",
                    end: "top 50%",
                    scrub: 1,
                },
            });

            gsap.to("#verticalLine", {
                opacity: 1,
                scaleY: 1,
                transformOrigin: "top center",
                duration: 1.5,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#verticalLine",
                    start: "top 80%",
                    end: "top 50%",
                    scrub: 1,
                },
            });

            gsap.to("#longDesc", {
                opacity: 1,
                y: 0,
                duration: 1.5,
                delay: 0.4,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#longDesc",
                    start: "top 75%",
                    end: "top 50%",
                    scrub: 1,
                },
            });

            // Animate All Boxes Sequentially
            const serviceCards = ["#section-1", "#section-2", "#section-3", "#section-4"];
            serviceCards.forEach((card, index) => {
                gsap.from(card, {
                    scale: 0.5,
                    opacity: 0,
                    duration: 2.0,
                    ease: "elastic.out(1, 3.0)",
                    scrollTrigger: {
                        trigger: card,
                        start: "top 85%",
                        end: "top 50%",
                        scrub: true,
                    },
                    delay: index * 0.5,
                });
            });
        });
    </script>

    <section class="min-h-screen flex items-center justify-center w-full">
        <div id="innovateSection" class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center px-6 sm:px-12">
            <div id="innovateText" class="p-6 sm:p-12 opacity-0 translate-y-10 space-y-8">
                <h2 class="text-4xl sm:text-6xl lg:text-8xl font-bold leading-tight text-black dark:text-white">
                    Build Beyond Limits
                </h2>
                <p class="text-lg sm:text-xl text-gray-600 dark:text-[#737373] leading-relaxed">
                    Transform ideas into powerful digital experiences that inspire and engage. Let's build the future together.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <a href="mailto:r4xntech@gmail.com" class="px-8 py-4 w-full sm:w-60 text-center bg-black dark:bg-white text-white dark:text-black text-lg rounded-full hover:bg-gray-800 dark:hover:bg-gray-300 transition duration-300">
                        Start Building
                    </a>
                </div>
            </div>
            <div id="innovateImage" class="flex justify-center opacity-0 scale-75 translate-x-full">
                <img src="assets/console.png" alt="Innovation Image"
                class="w-full sm:w-4/5 max-w-md rounded-lg bg-transparent" />
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            gsap.registerPlugin(ScrollTrigger);

            // Text Box Animation
            gsap.to("#innovateText", {
                opacity: 1,
                y: 0,
                duration: 3.0,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#innovateSection",
                    start: "top 70%",
                    end: "top 50%",
                    scrub: true,
                }
            });

            // Image Animation
            gsap.to("#innovateImage", {
                opacity: 1,
                scale: 1,
                x: 0,
                duration: 3.0,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#innovateSection",
                    start: "top 70%",
                    end: "top 50%",
                    scrub: true,
                }
            });
        });
    </script>

    <?php include 'footer.php';?>

</body>
</html>