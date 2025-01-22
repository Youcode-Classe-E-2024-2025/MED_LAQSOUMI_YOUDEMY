<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$image = isset($_SESSION['image']) ? $_SESSION['image'] : '';
$titre = isset($_SESSION['titre']) ? $_SESSION['titre'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Youdemy</title>
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
<body class="font-sans font-normal antialiased bg-gray-100 text-gray-900">
    <div class="flex flex-col md:flex-row min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-full md:w-64 bg-primary text-white">
            <div class="p-4 flex justify-between items-center md:block">
                <div>
                    <h1 class="text-2xl font-bold">Youdemy</h1>
                    <p class="text-sm hidden md:block">Student Dashboard</p>
                </div>
                <!-- Mobile menu button -->
                <button class="md:hidden text-white focus:outline-none" onclick="toggleMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
            <nav id="mobile-menu" class="hidden md:block mt-6">
                <a href="index.php?action=home" class="block py-2 px-4 hover:bg-secondary transition-colors duration-200">Dashboard</a>
                <a href="index.php?action=myCourses" class="block py-2 px-4 bg-secondary">My Courses</a>
                <a href="index.php?action=logout" class="block py-2 px-4 hover:bg-secondary transition-colors duration-200">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-4 md:p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl md:text-3xl font-bold">My Courses</h2>
                </div>

                <!-- My Courses -->
                <?php if ($role === 'etudiant') :?>
                <div class="bg-white rounded-lg shadow p-4 md:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <?php foreach ($courses as $item) : ?>
                            <div class="bg-gray-50 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                                <img src="<?= htmlspecialchars($item['image']) ?>" 
                                     alt="<?= htmlspecialchars($item['titre']) ?>" 
                                     class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h4 class="font-semibold text-lg mb-2 line-clamp-2"><?= htmlspecialchars($item['titre']) ?></h4>
                                    <div class="space-y-2">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Teacher:</span> 
                                            <?= htmlspecialchars($item['enseignant_nom']) ?>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Category:</span> 
                                            <?= htmlspecialchars($item['categorie_nom']) ?>
                                        </p>
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            <span class="font-medium">Description:</span> 
                                            <?= htmlspecialchars($item['description']) ?>
                                        </p>
                                        <p class="text-sm text-gray-600 line-clamp-2">
                                            <span class="font-medium">Content:</span> 
                                            <?= htmlspecialchars($item['contenu']) ?>
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <a href="index.php?action=course&id=<?= htmlspecialchars($item['id']) ?>" 
                                           class="block w-full text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors duration-200">
                                            View Course
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Close menu when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const menuButton = event.target.closest('button');
            if (!menuButton && !menu.classList.contains('hidden') && window.innerWidth < 768) {
                menu.classList.add('hidden');
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const menu = document.getElementById('mobile-menu');
            if (window.innerWidth >= 768) {
                menu.classList.remove('hidden');
            } else {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
