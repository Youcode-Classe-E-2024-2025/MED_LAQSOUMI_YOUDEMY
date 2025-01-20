<?php
session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login - Youdemy</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                primary: '#2563eb',
                secondary: '#1e40af'
              },
              fontFamily: {
                'sans' : ['Roboto Condensed', 'sans-serif']
              }
            }
          }
        }
    </script>
</head>
<body class="font-sans font-normal antialiased bg-white text-gray-900">
    <!-- Header Section -->
    <div class="h-24 w-full absolute top-0 left-0 bg-primary"></div>
    <div class="h-24 z-20 relative container mx-auto flex items-center justify-between px-6">
        <a href="index.php?action=home" class="text-xl font-extrabold italic tracking-tighter text-white uppercase">Youdemy</a>
        <div x-data="{ mobileMenu : false }" class="text-white text-lg fixed bottom-0 left-0 lg:relative p-6 lg:p-0 w-full lg:w-auto max-w-lg">
            <div x-bind:class="{ 'flex' : mobileMenu, 'hidden' : !mobileMenu }" class="lg:flex flex-col lg:flex-row items-center justify-center bg-primary lg:bg-transparent pt-6 pb-8 lg:p-0 -mb-6 lg:m-0 rounded-t-3xl shadow-2xl lg:shadow-none">
                <?php if ($role === 'etudiant'): ?>
                    <span class="my-2 lg:ml-6">Welcome <?php echo htmlspecialchars($userName); ?> <strong><?php echo htmlspecialchars($role); ?></strong></span>
                    <a href="index.php?action=myCourses" class="my-2 lg:ml-6">My Courses</a>
                    <a href="index.php?action=profile" class="my-2 lg:ml-6">Profile</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php elseif ($role === 'enseignant'): ?>
                    <span class="my-2 lg:ml-6">Welcome <?php echo htmlspecialchars($userName); ?> <strong><?php echo htmlspecialchars($role); ?></strong></span>
                    <a href="index.php?action=teacherDashboard" class="my-2 lg:ml-6">Dashboard</a>
                    <a href="index.php?action=createCourse" class="my-2 lg:ml-6">Create Course</a>
                    <a href="index.php?action=profile" class="my-2 lg:ml-6">Profile</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php elseif ($role === 'administrateur'): ?>
                    <span class="my-2 lg:ml-6">Welcome <?php echo htmlspecialchars($userName); ?> <strong><?php echo htmlspecialchars($role); ?></strong></span>
                    <a href="index.php?action=adminDashboard" class="my-2 lg:ml-6">Dashboard</a>
                    <a href="index.php?action=profile" class="my-2 lg:ml-6">Profile</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php else: ?>
                    <a href="index.php?action=courses" class="my-2 lg:ml-6">Courses</a>
                    <a href="index.php?action=loginPage" class="my-2 lg:ml-6">Login</a>
                    <a href="index.php?action=registerPage" class="my-2 lg:ml-6">Register</a>
                <?php endif; ?>
            </div>
            <!-- Mobile Menu Button -->
            <button x-on:click="mobileMenu = !mobileMenu" type="button" class="lg:hidden bg-primary text-white rounded-3xl w-full py-4 text-center uppercase text-sm shadow-2xl lg:shadow-none focus:outline-none">
                <template x-if="!mobileMenu">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
                        </svg>
                         Menu
                    </div>
                </template>
                <template x-if="mobileMenu">
                    <div class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                         Close
                    </div>
                </template>
            </button>
        </div>
    </div>
    <!-- Hero Section -->
    <div class="-mt-32 relative w-full bg-primary pt-64 pb-24">
        <div class="relative z-10 text-center text-white text-center mx-auto max-w-xl">
            <h1 class="text-3xl lg:text-7xl mb-4 font-bold uppercase italic">Learn Without Limits</h1>
            <p class="text-xl mb-6">Start, switch, or advance your career with thousands of courses from expert teachers</p>
            <a href="index.php?action=courses" class="inline-block rounded-full border-2 border-white text-lg px-8 py-3 hover:bg-white hover:text-primary">Browse Courses</a>
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary to-secondary opacity-90"></div>
    </div>

    <!-- Featured Categories -->
    <div class="container mx-auto px-6 py-24">
        <h2 class="text-5xl mb-12 font-bold uppercase italic text-center">Top Categories</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <a href="index.php?action=courses" class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-shadow">
                <div class="text-primary text-4xl mb-4">💻</div>
                <h3 class="text-xl font-bold mb-2">Development</h3>
                <p class="text-gray-600">Learn programming and web development</p>
            </a>
            <a href="index.php?action=courses" class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-shadow">
                <div class="text-primary text-4xl mb-4">📊</div>
                <h3 class="text-xl font-bold mb-2">Business</h3>
                <p class="text-gray-600">Expand your business knowledge</p>
            </a>
            <a href="index.php?action=courses" class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-shadow">
                <div class="text-primary text-4xl mb-4">🎨</div>
                <h3 class="text-xl font-bold mb-2">Design</h3>
                <p class="text-gray-600">Master design principles and tools</p>
            </a>
            <a href="index.php?action=courses" class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-shadow">
                <div class="text-primary text-4xl mb-4">📱</div>
                <h3 class="text-xl font-bold mb-2">Marketing</h3>
                <p class="text-gray-600">Learn digital marketing strategies</p>
            </a>
        </div>
    </div>
    <!-- Call to Action for Teachers -->
    <div class="relative w-full bg-primary py-32">
        <div class="relative z-10 text-center text-white text-center mx-auto max-w-xl">
            <h2 class="text-3xl lg:text-5xl mb-4 font-bold uppercase italic">Become an Instructor</h2>
            <p class="text-xl mb-6">Share your knowledge and earn money teaching on Youdemy</p>
            <a href="index.php?action=registerPage" class="inline-block rounded-full border-2 border-white text-lg px-8 py-3 hover:bg-white hover:text-primary">Start Teaching</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-6 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">About Youdemy</h3>
                    <ul class="space-y-2">
                        <li><a href="/about.html" class="hover:text-primary">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Learn</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php?action=courses" class="hover:text-primary">All Courses</a></li>
                        <li><a href="index.php?action=loginPage" class="hover:text-primary">Login</a></li>
                        <li><a href="index.php?action=registerPage" class="hover:text-primary">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Newsletter</h3>
                    <p class="mb-4">Get the latest updates and offers</p>
                    <form class="flex">
                        <input type="email" placeholder="Enter your email" class="flex-1 p-2 rounded-l text-gray-900">
                        <button class="bg-primary px-4 rounded-r hover:bg-secondary">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-800">
            <div class="container mx-auto px-6 py-6 text-center">
                <p>&copy; 2025 Youdemy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>

