<?php
session_start();
require 'config.php';

$error = '';
$login_success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_image'] = $user['profile_image'];
        $login_success = true;
    } else {
        $error = "Invalid email or password";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link href="https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>
<style>
    body {
        font-family: "Poppins", sans-serif;
    }
    ::-webkit-scrollbar {
        display: none;
    }
    @keyframes slide-in-left {
        0% {
            transform: translateX(100%);
            opacity: 0;
        }
        100% {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slide-out-right {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    .animate-slide-in-left {
        animation: slide-in-left 0.5s forwards;
    }
    .animate-slide-out-right {
        animation: slide-out-right 0.5s forwards;
    }
</style>
</head>
<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="w-full max-w-[90%] lg:max-w-lg px-4 py-6 sm:px-6 sm:py-8 mx-auto">
        <form method="POST" class="bg-white p-8 rounded-xl space-y-6 border border-gray-200">
            <h2 class="text-3xl font-bold text-center">Welcome Back</h2>    
            <div>
                <label for="email" class="block mb-1 font-medium">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required class="border rounded-md px-4 py-3 w-full">
            </div>  
            <div>
            	<label for="password" class="block mb-1 font-medium">Password</label>
            	<div class="relative">
            		<input type="password" name="password" id="password" placeholder="Password" required class="border rounded-md px-4 py-3 w-full pr-12">
            		<button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 focus:outline-none">
            			<i class="far fa-eye-slash"></i>
            		</button>
            	</div>
            </div>
            <button type="submit" class="w-full bg-black text-white px-6 py-3 rounded-md">
                Login
            </button>
        </form>
    </div>

    <div id="toast" class="fixed top-5 right-5 z-50 px-6 py-3 rounded-md shadow-lg text-white flex items-center gap-2bg-green-500 pointer-events-none opacity-0"></div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
        	const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        	passwordInput.setAttribute('type', type);
        	togglePassword.innerHTML = type === 'password'
        		? '<i class="far fa-eye-slash"></i>'
        		: '<i class="far fa-eye"></i>';
        });


        const toast = document.getElementById('toast');

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.remove('bg-green-500', 'bg-red-700');
            if (type === 'success') {
                toast.classList.add('bg-green-500');
            } else if (type === 'error') {
                toast.classList.add('bg-red-700');
            }
            toast.classList.remove('opacity-0', 'pointer-events-none', 'animate-slide-out-right', 'animate-slide-in-left');
            toast.classList.add('animate-slide-in-left');
            setTimeout(() => {
                toast.classList.remove('animate-slide-in-left');
                toast.classList.add('animate-slide-out-right');
            
                toast.addEventListener('animationend', () => {
                    toast.classList.add('opacity-0', 'pointer-events-none');
                    toast.classList.remove('animate-slide-out-right');
                }, { once: true });
            }, 3000);
        }

        window.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

            if (params.get('logout') === '1' && !isLoggedIn) {
                showToast('Logged out successfully.', 'success');
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        });

        <?php if ($login_success): ?>
            showToast("Login successful! Redirecting...", "success");
            setTimeout(() => {
                window.location.href = "dashboard.php";
            }, 2500);
        <?php elseif ($error): ?>
            showToast("<?= htmlspecialchars($error) ?>", "error");
        <?php endif; ?>

    </script>
</body>
</html>