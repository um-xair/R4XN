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
    ::-webkit-scrollbar {
        display: none;
    }
    html {
        scroll-behavior: smooth;
    }
    @keyframes floating {
        0% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0); }
    }
    .container {
        position: relative;
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        perspective: 900px;
        visibility: hidden;
    }
    .hi {
        position: relative;
        z-index: 1;
        text-transform: uppercase;
        text-align: center;
        transform-style: preserve-3d;
    }
    .hi__cuboid {
        position: relative;
        width: 500px;
        height: 70px;
        transform-style: preserve-3d;
        margin: 30px 0;
        transform: rotateX(20deg) rotateY(15deg); /* Unified rotation */
        transition: transform 0.6s ease, width 0.6s ease, height 0.6s ease;
    }
    .face {
        position: absolute;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: black;
        border: 1px solid #5E5D5E;
        transition: transform 0.6s ease, background 0.6s ease, color 0.6s ease;
    }
    .face--front {transform: translateZ(5px); background: black; color: white;}
    .face--back {transform: translateZ(-65px) rotateX(180deg); background: black; color: white;}
    .face--top {transform: rotateX(90deg) translateY(-30px) translateZ(35px); background: white; color: black;}
    .face--bottom {transform: rotateX(-90deg) translateY(30px) translateZ(35px); background: white; color: black;
    }
    .hi__word {
        margin: 0;
        font-size: 90px;
        line-height: 1;
    }
    .hi__base {
        position: absolute;
        z-index: 0;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 752px;
        height: 250px;
    }
    .hi__base-plate {
        width: 100%;
        height: 100%;
        background: black;
        border: 1px solid #5E5D5E;
    }
    .hi__location {
        position: absolute;
        margin: 0;
        font-size: 20px;
    }
    .hi__location--lat {
        top: 50%;
        left: 0;
        transform: rotate(-90deg) translateX(10px);
    }
    .hi__location--long {
        top: 50%;
        right: 0;
        transform: rotate(90deg) translateX(-10px);
    }
    @media (max-width: 1024px) {
        .hi__cuboid {
            width: 350px;
            height: 55px;
            transform: rotateX(20deg) rotateY(15deg);
        }
        .hi__word {
            font-size: 60px;
        }
        .face--front {transform: translateZ(-1px);}
        .face--back {transform: translateZ(-55px) rotateX(180deg);}
        .face--top {transform: rotateX(90deg) translateY(-28px) translateZ(28px);}
        .face--bottom {transform: rotateX(-90deg) translateY(28px) translateZ(28px);}
    }
    @media (max-width: 640px) {
        .hi__cuboid {
            width: 250px;
            height: 45px;
            transform: rotateX(20deg) rotateY(15deg);
        }
        .hi__word {
            font-size: 45px;
        }
        .face--front {transform: translateZ(-1px);}
        .face--back {transform: translateZ(-45px) rotateX(180deg);}
        .face--top {transform: rotateX(90deg) translateY(-23px) translateZ(22px);}
        .face--bottom {transform: rotateX(-90deg) translateY(23px) translateZ(23px);}
        .hi__base {
            width: 80%;
            height: 200px;
        }
    }
</style>
</head>
<body class="font-[Outfit] bg-[#080808] m-0 flex flex-col items-center justify-start text-white overflow-auto">

    <?php include 'header.php';?>

    <div class="container relative w-full min-h-screen flex flex-col items-center justify-center perspective-[900px]">
        <div class="hi space-y-10">
            <div class="hi__cuboid">
                <div class="face face--front"><p class="hi__word">FRONT</p></div>
                <div class="face face--back"><p class="hi__word">FRONT</p></div>
                <div class="face face--top"><p class="hi__word">FRONT</p></div>
                <div class="face face--bottom"><p class="hi__word">FRONT</p></div>
            </div>
            <div class="hi__cuboid">
                <div class="face face--front"><p class="hi__word">END</p></div>
                <div class="face face--back"><p class="hi__word">END</p></div>
                <div class="face face--top"><p class="hi__word">END</p></div>
                <div class="face face--bottom"><p class="hi__word">END</p></div>
            </div>
            <div class="hi__cuboid">
                <div class="face face--front"><p class="hi__word">PROJECTS</p></div>
                <div class="face face--back"><p class="hi__word">PROJECTS</p></div>
                <div class="face face--top"><p class="hi__word">PROJECTS</p></div>
                <div class="face face--bottom"><p class="hi__word">PROJECTS</p></div>
            </div>
        </div>
        <div class="hi__base mt-10">
            <div class="hi__base-plate"></div>
            <p class="hi__location hi__location--lat lg:block hidden">53.3454° N</p>
            <p class="hi__location hi__location--long lg:block hidden">-6.3070° E</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelector('.container').style.visibility = 'visible';
            const cuboids = document.querySelectorAll('.hi__cuboid');

            cuboids.forEach((cuboid, i) => {
                const delay = i * 0.2;
                gsap.to(cuboid, {
                    rotationX: -360,
                    duration: 8,
                    repeat: -1,
                    ease: 'none',
                    delay
                });
            });
        });
    </script>

    <?php
        include 'dashboard/config.php';
        $projects = [];
        $result = $conn->query("SELECT * FROM frontend_projects ORDER BY created_at DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $projects[] = $row;
            }
        }
    ?>

    <section class="py-20 text-white text-center min-h-screen w-full flex flex-col items-center justify-center">
        <div id="parallaxContainer" class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-10 p-5 w-full lg:w-[85%] mx-auto">
            <?php foreach ($projects as $project): ?>
                <a href="<?= htmlspecialchars($project['link_url']) ?>" target="_blank"
                   class="relative group w-full h-full rounded-[20px] overflow-hidden transition-transform duration-300">
                    <img src="dashboard/<?= htmlspecialchars($project['image_path']) ?>" alt="<?= htmlspecialchars($project['title']) ?>" class="w-full h-full object-cover rounded-[20px] group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        <span class="text-white text-xl font-semibold relative">
                            <?= htmlspecialchars($project['title']) ?>
                            <span class="absolute left-0 bottom-[-4px] w-0 h-[2px] bg-white transition-all duration-500 group-hover:w-full"></span>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include 'footer.php';?>

</body>
</html>