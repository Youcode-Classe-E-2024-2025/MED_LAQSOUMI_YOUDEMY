<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">YOUDEMY</a>
            <div class="flex items-center space-x-4">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Teacher'); ?></span>
                <a href="index.php?action=logout" class="bg-blue-700 px-4 py-2 rounded hover:bg-blue-800">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">Total Courses</h3>
                <p class="text-3xl font-bold text-blue-600"><?php echo $totalCourses; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2">Total Students</h3>
                <p class="text-3xl font-bold text-blue-600"><?php echo $totalStudents; ?></p>
            </div>
        </div>

        <!-- Courses Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">My Courses</h2>
                <a href="index.php?action=ajouterCours" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Course</a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($courses)): ?>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($course['titre']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php echo htmlspecialchars($course['description']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php echo htmlspecialchars($course['category_name'] ?? 'Uncategorized'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="index.php?action=modifierCours&id=<?php echo $course['id']; ?>" 
                                           class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                                        <a href="index.php?action=consulterInscriptions&id=<?php echo $course['id']; ?>" 
                                           class="text-green-600 hover:text-green-900 mr-4">View Students</a>
                                        <a href="index.php?action=supprimerCours&id=<?php echo $course['id']; ?>" 
                                           class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No courses found. Click "Add New Course" to create your first course.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
