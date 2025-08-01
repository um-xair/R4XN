<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);    require 'erp.r4xn.com/config.php';

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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!-- Google Site Verification -->
<meta name="google-site-verification" content="fQ9POhOfPTtny4LONlGJkPiB_io5ZfEuHUao3jcUQ-8" />

<!-- Bing Webmaster Tools -->
<meta name="msvalidate.01" content="391F96B8287DC1F160473A94CFE46296" />

<!-- Yandex Webmaster -->
<meta name="yandex-verification" content="eb3fdd51857fccb3" />

<!-- Primary Meta Tags -->
<title>R4XN | Full-Stack Web & IoT Solutions for Modern Brands | Malaysia</title>
<meta name="description" content="R4XN transforms your digital ideas into powerful web platforms, mobile apps, and IoT systems. 5+ years experience, 120+ interfaces, 99% client satisfaction. Get transparent, innovative solutions for your brand." />
<meta name="keywords" content="R4XN, web development, IoT solutions, UI/UX design, system design, full-stack developer, frontend development, mobile apps, Malaysia tech, web design, digital transformation, custom software development, responsive design, e-commerce development, API development, cloud solutions, database design, SEO optimization, performance optimization" />
<meta name="author" content="R4XN Team" />
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
<meta name="googlebot" content="index, follow" />
<meta name="bingbot" content="index, follow" />
<link rel="canonical" href="https://r4xn.com/" />

<!-- Language and Region -->
<meta name="language" content="English" />
<meta name="geo.region" content="MY" />
<meta name="geo.placename" content="Malaysia" />
<meta name="geo.position" content="3.1390;101.6869" />
<meta name="ICBM" content="3.1390, 101.6869" />

<!-- Favicon and App Icons -->
<link rel="icon" type="image/png" href="/r4xn-favicon/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/r4xn-favicon/favicon.svg" />
<link rel="shortcut icon" href="/r4xn-favicon/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/r4xn-favicon/apple-touch-icon.png" />
<meta name="apple-mobile-web-app-title" content="R4XN" />
<link rel="manifest" href="/r4xn-favicon/site.webmanifest" />

<!-- Open Graph Meta Tags (Facebook, LinkedIn) -->
<meta property="og:site_name" content="R4XN" />
<meta property="og:title" content="R4XN – Full-Stack Web & IoT Solutions for Modern Brands" />
<meta property="og:description" content="Transform your ideas into powerful digital experiences. 5+ years experience, 120+ interfaces, 99% client satisfaction. Web, mobile, and IoT solutions with transparency and innovation." />
<meta property="og:url" content="https://r4xn.com/" />
<meta property="og:type" content="website" />
<meta property="og:image" content="https://r4xn.com/og-image.png" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta property="og:image:alt" content="R4XN - Full-Stack Web & IoT Solutions" />
<meta property="og:locale" content="en_US" />
<meta property="fb:app_id" content="1778052509499669" />


<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:site" content="@R4XN" />
<meta name="twitter:creator" content="@rawxair" />
<meta name="twitter:title" content="R4XN – Full-Stack Web & IoT Solutions for Modern Brands" />
<meta name="twitter:description" content="Smart solutions in web, mobile, and IoT development—R4XN is your tech partner for modern growth. 5+ years experience, 99% client satisfaction." />
<meta name="twitter:image" content="https://r4xn.com/og-image.png" />
<meta name="twitter:image:alt" content="R4XN - Full-Stack Web & IoT Solutions" />

<!-- Additional Meta Tags -->
<meta name="format-detection" content="telephone=no" />
<meta name="mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
<meta name="apple-mobile-web-app-title" content="R4XN" />

<!-- Preconnect to external domains for performance -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="preconnect" href="https://www.googletagmanager.com" />
<link rel="preconnect" href="https://www.google-analytics.com" />
<link rel="preconnect" href="https://cdnjs.cloudflare.com" />
<link rel="preconnect" href="https://cdn.jsdelivr.net" />

<!-- DNS Prefetch for performance -->
<link rel="dns-prefetch" href="//api.fontshare.com" />
<link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />
<link rel="dns-prefetch" href="//cdn.jsdelivr.net" />

<!-- Enhanced JSON-LD Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "R4XN",
  "url": "https://r4xn.com/",
  "logo": "https://r4xn.com/assets/logo.png",
  "description": "R4XN provides full-stack development, mobile apps, UI/UX, and IoT solutions with over 5 years of experience and 99% client satisfaction.",
  "foundingDate": "2019",
  "numberOfEmployees": "5-10",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "MY",
    "addressRegion": "Malaysia"
  },
  "contactPoint": [{
    "@type": "ContactPoint",
    "email": "walkers287@gmail.com",
    "contactType": "customer support",
    "areaServed": "MY",
    "availableLanguage": "English"
  }],
  "sameAs": [
    "https://dribbble.com/R4XN",
    "https://github.com/um-xair",
    "https://www.tiktok.com/@rawxair"
  ],
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "name": "R4XN Services",
    "itemListElement": [
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Web Development",
          "description": "Custom website development with responsive design and SEO optimization"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "Mobile App Development",
          "description": "Native and cross-platform mobile application development"
        }
      },
      {
        "@type": "Offer",
        "itemOffered": {
          "@type": "Service",
          "name": "IoT Solutions",
          "description": "Internet of Things development and system integration"
        }
      }
    ]
  }
}
</script>

<!-- Local Business Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "R4XN",
  "description": "Full-Stack Web & IoT Solutions for Modern Brands",
  "url": "https://r4xn.com/",
  "telephone": "+60-XX-XXXX-XXXX",
  "email": "walkers287@gmail.com",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "MY"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "3.1390",
    "longitude": "101.6869"
  },
  "openingHours": "Mo-Fr 09:00-18:00",
  "priceRange": "$$",
  "currenciesAccepted": "MYR, USD",
  "paymentAccepted": "Credit Card, Bank Transfer",
  "areaServed": "MY",
  "serviceArea": {
    "@type": "Country",
    "name": "Malaysia"
  }
}
</script>

<!-- WebSite Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "R4XN",
  "url": "https://r4xn.com/",
  "description": "Full-Stack Web & IoT Solutions for Modern Brands",
  "publisher": {
    "@type": "Organization",
    "name": "R4XN"
  },
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://r4xn.com/search?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-CD69HEJBPY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-CD69HEJBPY', {
    'page_title': 'R4XN Projects & Solutions',
    'page_location': 'https://r4xn.com/'
  });
</script>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PHGQ3WPG');</script>
<!-- End Google Tag Manager -->

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "url": "https://r4xn.com",
  "name": "R4XN",
  "logo": "https://r4xn.com/assets/logo.png"
}
</script>

<link rel="icon" href="https://r4xn.com/assets/logo.png" type="image/png" sizes="32x32">
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

<body class="max-w-screen overflow-x-hidden bg-[#f4f4f4] dark:bg-[#080808]" itemscope itemtype="https://schema.org/WebPage">
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PHGQ3WPG"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    <?php include 'header.php';?>

    <!-- Main Hero Section -->
    <main>
        <section class="min-h-screen px-6 py-10 md:px-32 md:py-10 flex items-center justify-start w-full" role="banner" aria-label="Hero Section">
            <h1 id="animatedText" class="max-w-4xl text-transparent bg-gradient-to-t from-gray-600 to-black dark:from-gray-400 dark:to-white bg-clip-text leading-[1] font-bold text-6xl md:text-[8rem] lg:text-[10rem]" itemprop="headline">
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
            return "erp.r4xn.com/" . $path;
        }, array_column($result->fetch_all(MYSQLI_ASSOC), 'image_path'));
    ?>
    
    <!-- Project Gallery Section -->
    <section class="project-gallery" role="region" aria-label="Project Portfolio Gallery">
        <script>
            const w = 1240;
            const h = 874;
            const images = <?= json_encode($images) ?>;

            document.write([...Array(2)].map((_, sectionIndex) => `
                <section class="demo-gallery overflow-x-auto w-screen py-4" aria-label="Project Showcase ${sectionIndex + 1}">
                    <ul class="wrapper flex gap-4">
                        ${images.slice(sectionIndex * 5, (sectionIndex + 1) * 5).map((img, imgIndex) => `
                        <li><img src="${img}" width="${w}" height="${h}" alt="R4XN Project Showcase ${sectionIndex * 5 + imgIndex + 1}" loading="lazy" decoding="async"></li>`).join('')}
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

    <!-- Statistics Section -->
    <section class="min-h-screen px-6 py-10 flex flex-col items-center justify-center text-center w-full space-y-20" role="region" aria-label="Company Statistics and Achievements">
        <h2 id="revealText" class="max-w-5xl text-2xl md:text-5xl font-medium text-black dark:text-white" itemprop="description">
            We transform your ideas into <br>powerful digital experiences—designed with purpose, built with precision, and marketed with transparency, efficiency, and measurable impact at every step of the journey.
        </h2>
        <div class="flex flex-col lg:flex-row gap-10 justify-center w-full max-w-6xl" itemscope itemtype="https://schema.org/Organization">
            <div class="flex flex-col gap-8 md:gap-14">
                <article class="box left-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden" itemscope itemtype="https://schema.org/QuantitativeValue">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight" itemprop="value">5+ Years</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left" itemprop="description">
                            Practical experience in developing and optimizing front-end and back-end systems.
                        </p>
                    </div>
                </article>
                <article class="box left-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden" itemscope itemtype="https://schema.org/QuantitativeValue">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight" itemprop="value">120+ Interfaces</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left" itemprop="description">
                            Developed and optimized 120+ responsive user interfaces.
                        </p>
                    </div>
                </article>
            </div>
            <div class="flex flex-col gap-14 lg:mt-32">
                <article class="box right-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden" itemscope itemtype="https://schema.org/QuantitativeValue">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight" itemprop="value">10+ Technology</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left" itemprop="description">
                            Adaptable to a variety of modern tools and frameworks, ensuring seamless project execution.
                        </p>
                    </div>
                </article>                
                <article class="box right-box bg-white dark:bg-[#080808] relative p-8 md:px-10 rounded-[30px] shadow-lg flex flex-col justify-center max-w-[550px] md:aspect-[2/1] mx-auto overflow-hidden" itemscope itemtype="https://schema.org/QuantitativeValue">
                    <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                    <div class="relative z-10">
                        <h3 class="text-left text-4xl md:text-[4rem] text-black dark:text-white leading-tight" itemprop="value">99% Feedback</h3>
                        <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373] text-left" itemprop="description">
                            Maintained a 99% approval rating by consistently meeting deadlines and project goals.
                        </p>
                    </div>
                </article>
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

    <!-- Current Project Section -->
    <section class="min-h-screen px-6 py-10 flex flex-col items-center justify-center text-center w-full space-y-10" role="region" aria-label="Current Project Showcase" itemscope itemtype="https://schema.org/CreativeWork">
        <h2 id="projectTitle" class="max-w-7xl text-4xl md:text-7xl font-semibold leading-tight opacity-0 translate-y-20 text-black dark:text-white" itemprop="name">
            Behind the Code<br>A Peek Into My Present Creation.
        </h2>
        <p id="projectDesc" class="text-sm md:text-4xl max-w-4xl leading-tight text-gray-600 dark:text-[#737373] opacity-0 translate-y-20" itemprop="description">
            Our latest project focuses on building a seamless, user-friendly platform that prioritizes efficiency and transparency.
        </p>
        <?php if ($image_path && $link_url): ?>
            <a href="<?= htmlspecialchars($link_url) ?>" target="_blank" rel="noopener noreferrer" itemprop="url">
                <img id="projectImage" src="erp.r4xn.com/<?= htmlspecialchars($image_path) ?>" alt="R4XN Current Project Showcase" class="max-w-full w-full md:w-4/5 h-auto mx-auto rounded-[30px] shadow-lg opacity-0 rotate-12 scale-75" itemprop="image" loading="lazy" decoding="async">
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

    <!-- Services Section -->
    <section class="min-h-screen px-6 py-10 flex flex-col items-center justify-center text-center w-full space-y-6" role="region" aria-label="Our Services" itemscope itemtype="https://schema.org/Service">
        <h2 id="servicesTitle" class="text-4xl md:text-[10rem] font-semibold leading-tight opacity-0 translate-y-10 text-black dark:text-white" itemprop="name">
            Services
        </h2>
        <p id="shortDesc" class="text-sm md:text-4xl max-w-4xl text-gray-600 dark:text-[#737373] opacity-0 translate-y-10" itemprop="description">
            Explore the services we provide, crafted to bring your ideas to life with precision and innovation.
        </p>
        <div id="verticalLine" class="h-80 w-0.5 bg-black dark:bg-white opacity-0 scale-y-0" aria-hidden="true"></div>
        <p id="longDesc" class="text-lg md:text-4xl max-w-4xl opacity-0 translate-y-10 text-black dark:text-white">
            We go beyond simple requests like 'I need a website.' Our process starts by understanding your true objective—whether it's gaining organic users or driving conversions—and delivering a complete solution from A to Z.
        </p>
        <div id="servicesGrid" class="flex flex-wrap gap-8 w-full max-w-6xl mx-auto">
            <article id="section-1" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-1 relative overflow-hidden" itemscope itemtype="https://schema.org/Service">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white" itemprop="name">Website</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]" itemprop="description">
                        We create more than just websites—we design powerful, results-driven platforms that consistently deliver strong sales and marketing outcomes.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/laptop.png" alt="R4XN Website Development Services" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto" itemprop="image" loading="lazy" decoding="async">
                </div>
            </article>
            <article id="section-2" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-2 relative overflow-hidden" itemscope itemtype="https://schema.org/Service">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white" itemprop="name">Mobile Apps</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]" itemprop="description">
                        Our expertise in UX and user engagement ensures mobile applications that offer intuitive, smooth, and memorable user experiences.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/phone.png" alt="R4XN Mobile App Development Services" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto" itemprop="image" loading="lazy" decoding="async">
                </div>
            </article>
            <article id="section-3" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-3 relative overflow-hidden" itemscope itemtype="https://schema.org/Service">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white" itemprop="name">Development</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]" itemprop="description">
                        From concept to execution, we handle your product's development with precision—optimizing resources and ensuring smooth, scalable performance.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/development.png" alt="R4XN Software Development Services" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto" itemprop="image" loading="lazy" decoding="async">
                </div>
            </article>
            <article id="section-4" class="flex-1 min-w-full sm:min-w-[48%] md:basis-[48%] rounded-[30px] bg-gray-100 dark:bg-[#121212] shadow-lg pb-10 md:pb-20 order-4 relative overflow-hidden" itemscope itemtype="https://schema.org/Service">
                <div class="absolute inset-0 rounded-[30px] border-[2.5px] border-transparent pointer-events-none animate-border"></div>
                <div class="relative z-10 px-6 pt-10 md:px-16 md:pt-20 space-y-4">
                    <h3 class="text-4xl md:text-6xl font-semibold text-black dark:text-white" itemprop="name">Strategy</h3>
                    <p class="text-sm md:text-xl text-gray-600 dark:text-[#737373]" itemprop="description">
                        We design end-to-end strategies that turn innovative ideas into thriving digital products—driving market relevance and sustainable growth.
                    </p>
                </div>
                <div class="relative z-10">
                    <img src="assets/strategy.png" alt="R4XN Digital Strategy Services" class="bg-transparent mx-auto w-4/5 sm:w-3/5 h-auto" itemprop="image" loading="lazy" decoding="async">
                </div>
            </article>
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

    <!-- Call to Action Section -->
    <section class="min-h-screen flex items-center justify-center w-full" role="region" aria-label="Get Started" itemscope itemtype="https://schema.org/ContactPage">
        <div id="innovateSection" class="max-w-7xl w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center px-6 sm:px-12">
            <div id="innovateText" class="p-6 sm:p-12 opacity-0 translate-y-10 space-y-8">
                <h2 class="text-4xl sm:text-6xl lg:text-8xl font-bold leading-tight text-black dark:text-white" itemprop="name">
                    Build Beyond Limits
                </h2>
                <p class="text-lg md:text-2xl text-gray-600 dark:text-[#737373] leading-relaxed" itemprop="description">
                    Transform ideas into powerful digital experiences that inspire and engage. Let's build the future together.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                    <a href="mailto:r4xntech@gmail.com" class="px-8 py-5 w-full sm:w-60 text-center bg-black dark:bg-white text-white dark:text-black text-xl rounded-full hover:bg-gray-800 dark:hover:bg-gray-300 transition duration-300" itemprop="url" aria-label="Contact R4XN to start your project">
                        Start Building
                    </a>
                </div>
            </div>
            <div id="innovateImage" class="flex justify-center opacity-0 scale-75 translate-x-full">
                <img src="assets/console.png" alt="R4XN Innovation and Technology Showcase" 
                class="w-full sm:w-4/5 max-w-md rounded-lg bg-transparent" loading="lazy" decoding="async" />
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
    </main>

    <?php include 'footer.php';?>

</body>
</html>