<?php
include 'dashboard/config.php';

// Get service slug from URL
$service_slug = $_GET['service'] ?? '';

if (empty($service_slug)) {
    header("Location: services.php");
    exit();
}

// Get service details
$service_query = "SELECT * FROM services WHERE slug = ? AND status = 'active'";
$stmt = $conn->prepare($service_query);
$stmt->bind_param("s", $service_slug);
$stmt->execute();
$service_result = $stmt->get_result();

if ($service_result->num_rows === 0) {
    header("Location: services.php");
    exit();
}

$service = $service_result->fetch_assoc();

// Get projects for this service
$projects_query = "SELECT * FROM projects WHERE service_id = ? AND status = 'active' ORDER BY sort_order";
$stmt = $conn->prepare($projects_query);
$stmt->bind_param("i", $service['id']);
$stmt->execute();
$projects_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <meta name="google-site-verification" content="fQ9POhOfPTtny4LONlGJkPiB_io5ZfEuHUao3jcUQ-8" />
    <meta name="msvalidate.01" content="391F96B8287DC1F160473A94CFE46296" />
    <meta name="yandex-verification" content="eb3fdd51857fccb3" />
    
    <title><?php echo htmlspecialchars($service['name']); ?> Projects - R4XN Web Development Portfolio | Malaysia</title>
    <meta name="description" content="<?php echo htmlspecialchars($service['description']); ?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($service['name']); ?> projects, web development, Malaysia developer, custom solutions" />
    <meta name="author" content="R4XN Team" />
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <link rel="canonical" href="https://r4xn.com/<?php echo $service_slug; ?>-projects" />
    
    <meta name="language" content="English" />
    <meta name="geo.region" content="MY" />
    <meta name="geo.placename" content="Malaysia" />
    <meta name="geo.position" content="3.1390;101.6869" />
    <meta name="ICBM" content="3.1390, 101.6869" />
    
    <link rel="icon" type="image/png" href="/r4xn-favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/r4xn-favicon/favicon.svg" />
    <link rel="shortcut icon" href="/r4xn-favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/r4xn-favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="R4XN" />
    <link rel="manifest" href="/r4xn-favicon/site.webmanifest" />
    
    <meta property="og:site_name" content="R4XN" />
    <meta property="og:title" content="<?php echo htmlspecialchars($service['name']); ?> Projects - R4XN Web Development Portfolio" />
    <meta property="og:description" content="<?php echo htmlspecialchars($service['description']); ?>" />
    <meta property="og:url" content="https://r4xn.com/<?php echo $service_slug; ?>-projects" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://r4xn.com/og-image.png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="R4XN <?php echo htmlspecialchars($service['name']); ?> Projects" />
    <meta property="og:locale" content="en_US" />
    <meta property="fb:app_id" content="1778052509499669" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@R4XN" />
    <meta name="twitter:creator" content="@rawxair" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($service['name']); ?> Projects - R4XN Web Development Portfolio" />
    <meta name="twitter:description" content="<?php echo htmlspecialchars($service['description']); ?>" />
    <meta name="twitter:image" content="https://r4xn.com/og-image.png" />
    <meta name="twitter:image:alt" content="R4XN <?php echo htmlspecialchars($service['name']); ?> Projects" />
    
    <meta name="format-detection" content="telephone=no" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="R4XN" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://www.googletagmanager.com" />
    <link rel="preconnect" href="https://www.google-analytics.com" />
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />
    
    <link rel="dns-prefetch" href="//api.fontshare.com" />
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com" />
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net" />
    
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "<?php echo htmlspecialchars($service['name']); ?> Projects",
        "description": "<?php echo htmlspecialchars($service['description']); ?>",
        "url": "https://r4xn.com/<?php echo $service_slug; ?>-projects",
        "mainEntity": {
            "@type": "ItemList",
            "name": "<?php echo htmlspecialchars($service['name']); ?> Projects",
            "description": "Collection of <?php echo htmlspecialchars($service['name']); ?> projects developed by R4XN"
        }
    }
    </script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CD69HEJBPY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-CD69HEJBPY', {
            'page_title': 'R4XN <?php echo htmlspecialchars($service['name']); ?> Projects',
            'page_location': 'https://r4xn.com/<?php echo $service_slug; ?>-projects'
        });
    </script>
    
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PHGQ3WPG');</script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'clash': ['Clash Grotesk', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://api.fontshare.com/v2/css?f[]=clash-grotesk@200,300,400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js" defer></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
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
        
        .dark body {
            background-color: #080808;
        }
        
        .dark body::before {
            --line: rgba(255, 255, 255, 0.42);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        .project-card {
            transition: all 0.3s ease;
        }
        
        .project-card:hover {
            transform: translateY(-10px);
        }
        
        .tech-stack-item {
            transition: all 0.3s ease;
        }
        
        .tech-stack-item:hover {
            transform: scale(1.05);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dark .gradient-text {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-[#f4f4f4] dark:bg-[#080808] m-0 flex flex-col items-center justify-start text-black dark:text-white overflow-auto" itemscope itemtype="https://schema.org/WebPage">

    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PHGQ3WPG"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

    <?php include 'header.php';?>

    <main>
        <section class="min-h-screen px-6 py-20 flex flex-col items-center justify-center text-black dark:text-white relative mt-0 lg:mt-20" role="region" aria-label="<?php echo htmlspecialchars($service['name']); ?> Projects">
            <div class="container mx-auto max-w-7xl text-center space-y-16">
                <div class="space-y-6" data-aos="fade-up" data-aos-duration="1000">
                    <div class="flex justify-center items-center" data-aos-delay="400">
                        <span class="text-lg text-gray-500"><?php echo htmlspecialchars($service['name']); ?> Solutions</span>
                    </div>
                    <h1 class="text-transparent bg-gradient-to-t from-gray-600 to-black dark:from-gray-400 dark:to-white bg-clip-text font-bold text-5xl md:text-7xl"
                        itemprop="name">
                        <?php echo htmlspecialchars($service['name']); ?> Projects
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mx-auto text-lg md:text-2xl max-w-4xl"
                       itemprop="description" data-aos-delay="200">
                        <?php echo htmlspecialchars($service['description']); ?>
                    </p>
                </div> 
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12" id="projects-grid">
                    <?php while ($project = $projects_result->fetch_assoc()): ?>
                    <div class="project-card bg-white dark:bg-[#121212] rounded-3xl p-12 border-2 border-gray-200 dark:border-gray-700 relative group" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                        <div class="space-y-8">
                            <div class="relative overflow-hidden rounded-2xl">
                                <img src="<?php echo htmlspecialchars($project['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($project['name']); ?>" 
                                     class="w-full h-64 object-cover"
                                     loading="lazy" decoding="async">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            </div>
                            <div class="space-y-6">
                                <h3 class="text-3xl font-semibold"><?php echo htmlspecialchars($project['name']); ?></h3>
                                <p class="text-lg text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars($project['description']); ?></p>                                
                                <div class="flex flex-col sm:flex-row gap-4 w-full">
                                    <a href="<?php echo htmlspecialchars($project['project_url']); ?>"
                                       class="w-full inline-flex items-center justify-center bg-black dark:bg-white text-white dark:text-black font-semibold py-3 px-6 rounded-xl transition-colors duration-300 hover:bg-gray-800 dark:hover:bg-gray-200">
                                        View Project
                                    </a>
                                    <a href="case-study-dynamic.php?project_id=<?php echo $project['id']; ?>"
                                       class="w-full inline-flex items-center justify-center border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold py-3 px-6 rounded-xl transition-colors duration-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        Case Study
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

        <?php
        // Get features for this service
        $features_query = "SELECT * FROM service_features WHERE service_id = ? ORDER BY sort_order";
        $stmt = $conn->prepare($features_query);
        $stmt->bind_param("i", $service['id']);
        $stmt->execute();
        $features_result = $stmt->get_result();
        
        if ($features_result->num_rows > 0):
        ?>
        <section class="px-6 py-20" role="region" aria-label="<?php echo htmlspecialchars($service['name']); ?> Features">
            <div class="container mx-auto max-w-7xl">
                <div class="text-center mb-20" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="text-4xl md:text-6xl font-bold mb-8 bg-gradient-to-r from-gray-800 to-gray-600 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        <?php echo htmlspecialchars($service['name']); ?> Features
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 text-xl max-w-4xl mx-auto leading-relaxed">
                        Our <?php echo htmlspecialchars($service['name']); ?> solutions come with comprehensive features designed to maximize your business success and drive growth.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ($feature = $features_result->fetch_assoc()): ?>
                    <div class="group bg-white dark:bg-[#121212] rounded-3xl p-8 border-2 border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                        <div class="space-y-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-400 dark:to-blue-500 rounded-xl flex items-center justify-center">
                                <?php echo $feature['icon_svg'] ?: '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>'; ?>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-white"><?php echo htmlspecialchars($feature['feature_name']); ?></h3>
                                <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                                    <?php echo htmlspecialchars($feature['feature_description']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        gsap.registerPlugin(ScrollTrigger);
         
        gsap.from('.project-card', {
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            scrollTrigger: {
                trigger: '.project-card',
                start: 'top 80%'
            }
        });

        gsap.from('h1', {
            duration: 1,
            y: 30,
            opacity: 0,
            scrollTrigger: {
                trigger: 'h1',
                start: 'top 80%'
            }
        });
    </script>

    <?php include 'footer.php';?>

</body>
</html>
