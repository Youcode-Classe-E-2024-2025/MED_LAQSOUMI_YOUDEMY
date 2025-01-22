<?php
require_once __DIR__ . '/../config/error_reporting.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Course - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
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
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Add New Course</h2>
                <a href="index.php?action=teacherDashboard" class="text-blue-600 hover:text-blue-800">Back to Dashboard</a>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php
            // Debug output
            if (!isset($categories) || empty($categories)) {
                echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">';
                echo 'Debug: No categories found. Check database connection.';
                echo '</div>';
            }
            ?>

            <form action="index.php?action=ajouterCours" method="POST" class="space-y-6">
                <div>
                    <label for="titre" class="block text-sm font-medium text-gray-700">Course Title</label>
                    <input type="text" name="titre" id="titre" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label for="contenu" class="block text-sm font-medium text-gray-700">Content (Video URL or Document)</label>
                    <textarea name="contenu" id="contenu" rows="4" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input type="text" name="image" id="image" placeholder="https://placehold.co/300"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">Leave empty to use default placeholder image</p>
                </div>

                <div>
                    <label for="categorie_id" class="block text-sm font-medium text-gray-700">Category</label>
                    <select name="categorie_id" id="categorie_id" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a category</option>
                        <?php if (isset($categories) && is_array($categories)): ?>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['id']); ?>">
                                    <?php echo htmlspecialchars($category['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="index.php?action=teacherDashboard" 
                       class="bg-gray-200 py-2 px-4 rounded hover:bg-gray-300">Cancel</a>
                    <button type="submit" 
                            class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                        Add Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>