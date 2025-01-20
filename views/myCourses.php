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
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-primary text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Youdemy</h1>
                <p class="text-sm">Student Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Dashboard</a>
                <a href="#" class="block py-2 px-4 bg-secondary">My Courses</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Browse Courses</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Profile</a>
                <a href="#" class="block py-2 px-4 hover:bg-secondary">Settings</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-6">
                <h2 class="text-3xl font-bold mb-6">My Courses</h2>

                <!-- Course Progress -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Course Progress</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Introduction to Python</h4>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-sm mt-2">75% Complete</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Web Development Bootcamp</h4>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 40%"></div>
                            </div>
                            <p class="text-sm mt-2">40% Complete</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-semibold mb-2">Data Science Fundamentals</h4>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-primary h-2.5 rounded-full" style="width: 20%"></div>
                            </div>
                            <p class="text-sm mt-2">20% Complete</p>
                        </div>
                    </div>
                </div>

                <!-- My Courses -->
                <?php if ($role === 'etudiant') :?>
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">My Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($courses as $item) : ?>
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <h4 class="font-semibold mb-2"><?= htmlspecialchars($item['titre']) ?></h4>
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="Thumbnail" class="w-full h-48 object-cover mb-2">
                                <p class="text-sm mb-2">Teacher: <?= htmlspecialchars($item['teacher_name']) ?></p>
                                <p class="text-sm mb-2">Category: <?= htmlspecialchars($item['category_name']) ?></p>
                                <p class="text-sm mb-2">Description: <?= htmlspecialchars($item['description']) ?></p>
                                <p class="text-sm mb-2">contenu: <?= htmlspecialchars($item['contenu']) ?></p>
                                <a href="index.php?action=course&id=<?= htmlspecialchars($item['id']) ?>" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">View Course</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>

