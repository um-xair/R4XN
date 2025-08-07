<?php
// Database connection for case studies
include 'dashboard/config.php';

// Get project_id from URL parameter
$project_id = $_GET['project_id'] ?? null;

if (!$project_id) {
    header("Location: services.php");
    exit();
}

// Get project and case study details
$project_query = "SELECT p.*, s.name as service_name, s.slug as service_slug 
                  FROM projects p 
                  LEFT JOIN services s ON p.service_id = s.id 
                  WHERE p.id = ?";
$project_stmt = $conn->prepare($project_query);
$project_stmt->bind_param("i", $project_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();
$project = $project_result->fetch_assoc();

if (!$project) {
    header("Location: services.php");
    exit();
}

// Get case studies for this project
$case_studies_query = "SELECT * FROM case_studies WHERE project_id = ? AND status = 'active' ORDER BY created_at DESC";
$case_studies_stmt = $conn->prepare($case_studies_query);
$case_studies_stmt->bind_param("i", $project_id);
$case_studies_stmt->execute();
$case_studies_result = $case_studies_stmt->get_result();
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
    
    <title><?php echo htmlspecialchars($project['name']); ?> Case Studies - R4XN Web Development Portfolio | Malaysia</title>
    <meta name="description" content="Detailed case studies of <?php echo htmlspecialchars($project['name']); ?> project showcasing challenges, solutions, and results." />
    <meta name="keywords" content="<?php echo htmlspecialchars($project['name']); ?> case studies, web development portfolio, Malaysia developer" />
    <meta name="author" content="R4XN Team" />
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <link rel="canonical" href="https://r4xn.com/case-study-dynamic?project_id=<?php echo $project_id; ?>" />
    
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
    <meta property="og:title" content="<?php echo htmlspecialchars($project['name']); ?> Case Studies - R4XN Web Development Portfolio" />
    <meta property="og:description" content="Detailed case studies of <?php echo htmlspecialchars($project['name']); ?> project showcasing challenges, solutions, and results." />
    <meta property="og:url" content="https://r4xn.com/case-study-dynamic?project_id=<?php echo $project_id; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="https://r4xn.com/og-image.png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="R4XN <?php echo htmlspecialchars($project['name']); ?> Case Studies" />
    <meta property="og:locale" content="en_US" />
    <meta property="fb:app_id" content="1778052509499669" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@R4XN" />
    <meta name="twitter:creator" content="@rawxair" />
    <meta name="twitter:title" content="<?php echo htmlspecialchars($project['name']); ?> Case Studies - R4XN Web Development Portfolio" />
    <meta name="twitter:description" content="Detailed case studies of <?php echo htmlspecialchars($project['name']); ?> project." />
    <meta name="twitter:image" content="https://r4xn.com/og-image.png" />
    <meta name="twitter:image:alt" content="R4XN <?php echo htmlspecialchars($project['name']); ?> Case Studies" />
    
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
        "name": "<?php echo htmlspecialchars($project['name']); ?> Case Studies",
        "description": "Detailed case studies of <?php echo htmlspecialchars($project['name']); ?> project showcasing challenges, solutions, and results.",
        "url": "https://r4xn.com/case-study-dynamic?project_id=<?php echo $project_id; ?>",
        "mainEntity": {
            "@type": "ItemList",
            "name": "<?php echo htmlspecialchars($project['name']); ?> Case Studies",
            "description": "Collection of detailed case studies for <?php echo htmlspecialchars($project['name']); ?> project developed by R4XN"
        }
    }
    </script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-CD69HEJBPY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-CD69HEJBPY', {
            'page_title': 'R4XN <?php echo htmlspecialchars($project['name']); ?> Case Studies',
            'page_location': 'https://r4xn.com/case-study-dynamic?project_id=<?php echo $project_id; ?>'
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
        
        .case-study-card {
            transition: all 0.3s ease;
        }
        
        .case-study-card:hover {
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
        <section class="min-h-screen px-6 py-20 flex flex-col items-center justify-center text-black dark:text-white relative mt-0 lg:mt-20" role="region" aria-label="Case Studies">
            <div class="container mx-auto max-w-7xl space-y-16">
                <?php while ($case_study = $case_studies_result->fetch_assoc()): ?>
                <div class="space-y-12" id="case-studies-grid">
                    <div class="relative overflow-hidden rounded-3xl">
                        <img src="<?php echo !empty($case_study['hero_image_url']) && !filter_var($case_study['hero_image_url'], FILTER_VALIDATE_URL) ? htmlspecialchars($case_study['hero_image_url']) : htmlspecialchars($case_study['hero_image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($case_study['title']); ?> Case Study" 
                             class="w-full h-96 object-cover"
                             loading="lazy" decoding="async">
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
                        <div class="lg:col-span-2 space-y-8">
                            <div class="space-y-6">
                                <h3 class="text-4xl font-bold"><?php echo htmlspecialchars($case_study['title']); ?></h3>
                                <p class="text-xl text-gray-600 dark:text-gray-300 leading-relaxed"><?php echo htmlspecialchars($case_study['description']); ?></p>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <?php if (!empty($case_study['preview_button_url'])): ?>
                                <a href="<?php echo htmlspecialchars($case_study['preview_button_url']); ?>"
                                   class="w-full inline-flex items-center justify-center bg-black dark:bg-white text-white dark:text-black font-semibold py-4 px-8 rounded-xl transition-colors duration-300 hover:bg-gray-800 dark:hover:bg-gray-200">
                                    <?php echo htmlspecialchars($case_study['preview_button_text']); ?>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="lg:col-span-1 space-y-6">
                            <h4 class="text-2xl font-bold">Features Included:</h4>
                            <div class="flex flex-wrap gap-3">
                                <?php
                                // Get features for this case study
                                $features_query = "SELECT * FROM case_study_features WHERE case_study_id = ? ORDER BY sort_order";
                                $features_stmt = $conn->prepare($features_query);
                                $features_stmt->bind_param("i", $case_study['id']);
                                $features_stmt->execute();
                                $features_result = $features_stmt->get_result();
                                
                                while ($feature = $features_result->fetch_assoc()):
                                ?>
                                <span class="<?php echo htmlspecialchars($feature['color_class']); ?> px-6 py-3 rounded-full text-sm font-medium">
                                    <?php echo htmlspecialchars($feature['feature_name']); ?>
                                </span>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 100
        });

        gsap.registerPlugin(ScrollTrigger);
         
        gsap.from('.case-study-card', {
            duration: 0.8,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            scrollTrigger: {
                trigger: '.case-study-card',
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
