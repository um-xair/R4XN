<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0 , user-scalable=no">
<title>About R4XN – Your Full‑Stack Web & IoT Tech Partner</title>
<meta name="description" content="R4XN is a tech collaboration between RAWZEENS TECH and DEVXAIR—delivering modern web development, IoT, and system design with 5+ years of experience." />
<meta name="keywords" content="About R4XN, R4XN team, RAWZEENS TECH, DEVXAIR, tech partnership, full stack developer Malaysia, IoT development team" />
<link rel="canonical" href="https://r4xn.com/about.php" />
<meta property="og:title" content="About R4XN – A Powerful Tech Collaboration" />
<meta property="og:description" content="Meet the team behind R4XN—a combination of full-stack and front-end talent dedicated to building impactful digital experiences." />
<meta property="og:url" content="https://r4xn.com/about.php" />
<meta property="og:image" content="https://r4xn.com/about-preview.jpg" />
<link rel="icon" href="assets/r4xn-black.png" type="image/png" sizes="32x32">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
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
        font-family: "Press Start 2P", sans-serif;
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

    <section id="developersSection" class="min-h-screen px-6 py-32 flex flex-col items-center justify-center text-white">
        <div class="text-center mb-16">
            <h2 class="text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text font-bold text-5xl md:text-7xl">
                Meet Our Developers
            </h2>
            <p class="text-gray-400 mt-4 mx-auto text-lg">
                Our passionate team brings creativity, innovation, and dedication to every line of code.
            </p>
        </div>       

        <?php
            include 'erp.r4xn.com/config.php';
            $devs = $conn->query("SELECT * FROM developers ORDER BY created_at DESC");
    
            $counter = 1;
        ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-5xl w-full">
            <?php while ($d = $devs->fetch_assoc()): ?>
                <div id="dev<?= $counter ?>" class="developer-card relative group rounded-[30px] overflow-hidden flex h-96 bg-cover bg-center" style="background-image: url('erp.r4xn.com/<?= htmlspecialchars($d['image_path']) ?>');">
                    <div class="w-20 bg-black/60 p-4 flex flex-col justify-around items-center">
                        <?php foreach (str_split($d['name']) as $ch): ?>
                            <span class="customfont text-4xl font-extrabold"><?= htmlspecialchars($ch) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= htmlspecialchars($d['link']) ?>" target="_blank"
                         class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black text-white px-6 py-3 rounded-full flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" stroke="currentColor"
                             stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 5H19V11"/>
                            <path d="M19 5L5 19"/>
                        </svg>
                        View Portfolio
                    </a>
                </div>
                <?php $counter++; ?>
            <?php endwhile; ?>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.fromTo("#developersSection > div.text-center", 
          {autoAlpha: 0, y: 50, scale: 0.95, rotationX: 15}, 
          {
            autoAlpha: 1,
            y: 0,
            scale: 1,
            rotationX: 0,
            ease: "power3.out",
            duration: 1.5
          }
        );

        const devCount = <?= $devs->num_rows ?>; 

        for (let i = 1; i <= devCount; i++) {
            const id = '#dev' + i;
            gsap.fromTo(id, 
                {autoAlpha: 0, y: 100, rotation: 15, scale: 0.8, skewY: 10}, 
                {
                    autoAlpha: 1,
                    y: 0,
                    rotation: 0,
                    scale: 1,
                    skewY: 0,
                    ease: "power3.out",
                    duration: 1.2,
                    delay: 0.5 * i  
                }
            );
        }
    </script>

    <!-- Alpine.js CDN for interactivity -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <section class="min-h-screen px-6 py-32 flex flex-col items-center justify-center text-white">
        <div id="values-header" class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-transparent bg-gradient-to-t from-gray-400 to-white bg-clip-text font-bold text-5xl md:text-6xl">
                Our Values
            </h2>
            <p class="text-gray-400 mt-4 text-lg">
                These are the principles that shape who we are and what we do.
            </p>
        </div>

        <div
            x-data="{
                active: null,
                values: [
                    { title: 'Reliability',         description: 'We consistently deliver what we promise—on time, every time, with dependable results.' },
                    { title: 'Solution-Oriented',   description: 'We go beyond writing code by identifying root problems and building smart, effective solutions.' },
                    { title: 'Collaboration',       description: 'We thrive on teamwork, sharing knowledge and ideas to build better products together.' },
                    { title: 'Passion',             description: 'We’re fueled by genuine enthusiasm for our craft and the positive impact we make through technology.' },
                    { title: 'Excellence',          description: 'We aim high in everything we do—quality, performance, and thoughtful execution.' },
                    { title: 'Growth Mindset',      description: 'We embrace curiosity and keep sharpening our skills to stay ahead in a fast-moving field.' },
                    { title: 'Clean Code',          description: 'We write structured, maintainable code that’s easy to read, scale, and improve over time.' },
                    { title: 'Ownership',           description: 'We take full responsibility for our work, owning outcomes from concept to deployment.' },
                    { title: 'User-Centered',       description: 'We design and build with empathy—always putting the user’s needs and experience first.' },
                    { title: 'Secure',              description: 'We prioritize security, protecting data and systems through smart architecture and practices.' },
                    { title: 'Responsive Design',   description: 'We build interfaces that work beautifully across all devices, ensuring seamless experiences.' }
                ]
            }"
            id="values-container"
            class="flex flex-wrap justify-center gap-6">
            
            <template x-for="(value, index) in values" :key="index">
                <div
                  :id="'value-card-' + index"
                  @mouseenter="active = index"
                  @mouseleave="active = null"
                  class="group relative border border-white rounded-[20px] overflow-hidden transition-all duration-500 cursor-pointer"
                  :class="active === index ? 'w-80 h-96' : 'w-20 h-96'">
                  <!-- Card Content -->
                    <div
                      class="flex flex-col h-full transition-all duration-500 p-4 relative"
                      :class="active === index ? 'justify-start items-start' : 'items-center justify-end'">
                        <!-- Vertical Title in Collapsed State -->
                        <h3 class="absolute left-1/2 top-10 transform -translate-x-1/2 text-2xl text-gray-200 font-extrabold tracking-wide"
                            :class="active === index ? 'opacity-0' : '[writing-mode:vertical-rl]'"
                            x-text="value.title"></h3>
                            
                        <!-- Horizontal Title + Description in Expanded State -->
                        <div
                            class="transition-all duration-500 p-4"
                            x-show="active === index"
                            x-transition.opacity>
                            <h3 class="text-2xl text-gray-200 font-extrabold tracking-wide" x-text="value.title"></h3>
                        </div>
                      
                        <!-- Icon + Description -->
                        <div
                            class="flex items-center transition-all duration-500 mt-auto p-2"
                            :class="active === index ? 'gap-4' : 'flex-col-reverse justify-center'">
                            <!-- Icon SVGs -->
                            <template x-if="value.title === 'Reliability'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                  class="w-10 h-10 transition-all duration-300 shrink-0">
                                  <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                                  <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                                  <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                                  <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Solution-Oriented'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M12 5a3 3 0 1 0-5.997.125 4 4 0 0 0-2.526 5.77 4 4 0 0 0 .556 6.588A4 4 0 1 0 12 18Z"/>
                                    <path d="M12 5a3 3 0 1 1 5.997.125 4 4 0 0 1 2.526 5.77 4 4 0 0 1-.556 6.588A4 4 0 1 1 12 18Z"/>
                                    <path d="M15 13a4.5 4.5 0 0 1-3-4 4.5 4.5 0 0 1-3 4"/><path d="M17.599 6.5a3 3 0 0 0 .399-1.375"/>
                                    <path d="M6.003 5.125A3 3 0 0 0 6.401 6.5"/>
                                    <path d="M3.477 10.896a4 4 0 0 1 .585-.396"/>
                                    <path d="M19.938 10.5a4 4 0 0 1 .585.396"/>
                                    <path d="M6 18a4 4 0 0 1-1.967-.516"/>
                                    <path d="M19.967 17.484A4 4 0 0 1 18 18"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Collaboration'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="m11 17 2 2a1 1 0 1 0 3-3"/>
                                    <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/>
                                    <path d="m21 3 1 11h-2"/>
                                    <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/>
                                    <path d="M3 4h8"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Passion'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Excellence'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                                    <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                                    <path d="M4 22h16"/>
                                    <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                                    <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                                    <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Growth Mindset'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
                                    <path d="M22 10v6"/>
                                    <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Clean Code'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="m18 16 4-4-4-4"/>
                                    <path d="m6 8-4 4 4 4"/>
                                    <path d="m14.5 4-5 16"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'Ownership'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M12 12h.01"/>
                                    <path d="M16 6V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                                    <path d="M22 13a18.15 18.15 0 0 1-20 0"/>
                                    <rect width="20" height="14" x="2" y="6" rx="2"/>
                                </svg>
                            </template>
                            <template x-if="value.title === 'User-Centered'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M18 21a6 6 0 0 0-12 0"/>
                                    <circle cx="12" cy="11" r="4"/><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
                            </template>
                            <template x-if="value.title === 'Secure'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </template>
                            <template x-if="value.title === 'Responsive Design'">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"
                                    class="w-10 h-10 transition-all duration-300 shrink-0">
                                    <path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h8"/>
                                    <path d="M10 19v-3.96 3.15"/>
                                    <path d="M7 19h5"/>
                                    <rect width="6" height="10" x="16" y="12" rx="2"/>
                                </svg>
                            </template>
                            <p
                            class="text-sm text-gray-400"
                            x-show="active === index"
                            x-text="value.description"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const header = document.getElementById('values-header');
            const cards = document.querySelectorAll("[id^='value-card-']");
            gsap.set(header, {opacity: 0, y: 30});
            gsap.set(cards, {opacity: 0, y: 50});
            
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        gsap.to(header, {
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            ease: "power3.out",
                        });
                        gsap.to(cards, {
                            opacity: 1,
                            y: 0,
                            duration: 0.8,
                            ease: "power3.out",
                            stagger: 0.2,
                            delay: 0.3
                        });
                        obs.disconnect(); 
                    }
                });
            }, {
                threshold: 0.1
            });
            if (header) observer.observe(header);
        });
    </script>
    
    <?php include 'footer.php';?>

</body>
</html>