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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
          theme: {
            extend: {
              colors: {
                primary: '#3B82F6',
                secondary: '#1E40AF',
                accent: '#F59E0B'
              },
              fontFamily: {
                'sans': ['Poppins', 'sans-serif']
              }
            }
          }
        }
    </script>
</head>
<body class="font-sans font-normal antialiased bg-gray-50 text-gray-900">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Sidebar -->
        <aside class="w-full lg:w-64 bg-gradient-to-b from-primary to-secondary text-white">
            <div class="p-6">
                <h1 class="text-3xl font-bold">Youdemy</h1>
                <p class="text-sm opacity-75">Student Dashboard</p>
            </div>
            <nav class="mt-6">
                <a href="index.php?action=home" class="block py-3 px-6 hover:bg-white/10 transition duration-200">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="index.php?action=myCourses" class="block py-3 px-6 bg-white/20">
                    <i class="fas fa-book-open mr-2"></i> My Courses
                </a>
                <a href="index.php?action=courses" class="block py-3 px-6 hover:bg-white/10 transition duration-200">
                    <i class="fas fa-search mr-2"></i> Browse Courses
                </a>
                <a href="index.php?action=logout" class="block py-3 px-6 hover:bg-white/10 transition duration-200">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <div class="p-6 lg:p-10">
                <h2 class="text-4xl font-bold mb-8 text-gray-800">My Courses</h2>

                <!-- Course Progress -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h3 class="text-2xl font-semibold mb-6 text-gray-700">Course Progress</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md transition duration-300 hover:shadow-xl">
                            <h4 class="font-semibold mb-3 text-lg">Introduction to Python</h4>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-accent h-3 rounded-full" style="width: 75%"></div>
                            </div>
                            <p class="text-sm mt-3 text-gray-600">75% Complete</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md transition duration-300 hover:shadow-xl">
                            <h4 class="font-semibold mb-3 text-lg">Web Development Bootcamp</h4>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-accent h-3 rounded-full" style="width: 40%"></div>
                            </div>
                            <p class="text-sm mt-3 text-gray-600">40% Complete</p>
                        </div>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-md transition duration-300 hover:shadow-xl">
                            <h4 class="font-semibold mb-3 text-lg">Data Science Fundamentals</h4>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-accent h-3 rounded-full" style="width: 20%"></div>
                            </div>
                            <p class="text-sm mt-3 text-gray-600">20% Complete</p>
                        </div>
                    </div>
                </div>

                <!-- My Courses -->
                <?php if ($role === 'etudiant') :?>
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <h3 class="text-2xl font-semibold mb-6 text-gray-700">My Courses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($courses as $item) : ?>
                            <div class="bg-gray-50 rounded-lg shadow-md overflow-hidden transition duration-300 hover:shadow-xl">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="Thumbnail" class="w-full h-48 object-cover">
                                <div class="p-6">
                                    <h4 class="font-semibold mb-2 text-lg"><?= htmlspecialchars($item['titre']) ?></h4>
                                    <p class="text-sm mb-2 text-gray-600"><i class="fas fa-chalkboard-teacher mr-2"></i>Teacher: <?= htmlspecialchars($item['teacher_name']) ?></p>
                                    <p class="text-sm mb-2 text-gray-600"><i class="fas fa-tag mr-2"></i>Category: <?= htmlspecialchars($item['category_name']) ?></p>
                                    <p class="text-sm mb-4 text-gray-600"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                                    <a href="index.php?action=course&id=<?= htmlspecialchars($item['id']) ?>" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition duration-300 inline-block">View Course</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>
</html>