<?php 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
    header('Location: index.php?action=login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - YOUDEMY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white">
            <div class="p-4">
                <h1 class="text-2xl font-bold">YOUDEMY Admin</h1>
            </div>
            <nav class="mt-4">
                <a href="?action=adminDashboard" class="block py-2 px-4 hover:bg-gray-700 <?php echo !isset($_GET['section']) ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                </a>
                <a href="?action=adminDashboard&section=users" class="block py-2 px-4 hover:bg-gray-700 <?php echo isset($_GET['section']) && $_GET['section'] == 'users' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-users mr-2"></i>Users
                </a>
                <a href="?action=adminDashboard&section=courses" class="block py-2 px-4 hover:bg-gray-700 <?php echo isset($_GET['section']) && $_GET['section'] == 'courses' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-book mr-2"></i>Courses
                </a>
                <a href="?action=adminDashboard&section=tags" class="block py-2 px-4 hover:bg-gray-700 <?php echo isset($_GET['section']) && $_GET['section'] == 'tags' ? 'bg-gray-700' : ''; ?>">
                    <i class="fas fa-tags mr-2"></i>Tags
                </a>
                <a href="?action=logout" class="block py-2 px-4 hover:bg-gray-700 mt-8 text-red-400">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $_SESSION['message']; ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($_GET['section'])): ?>
                <!-- Dashboard Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
                        <p class="text-3xl font-bold"><?php echo $totalUsers; ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Total Courses</h3>
                        <p class="text-3xl font-bold"><?php echo $totalCourses; ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Pending Teachers</h3>
                        <p class="text-3xl font-bold"><?php echo $pendingTeachers; ?></p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-gray-500 text-sm font-medium">Total Tags</h3>
                        <p class="text-3xl font-bold"><?php echo $totalTags; ?></p>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Recent Activities</h2>
                    <div class="space-y-4">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="flex items-center justify-between border-b pb-2">
                                <div>
                                    <p class="font-medium"><?php echo htmlspecialchars($activity['action']); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($activity['user']); ?></p>
                                </div>
                                <span class="text-sm text-gray-500">
                                    <?php echo date('M d, Y', strtotime($activity['date'])); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php elseif ($_GET['section'] == 'users'): ?>
                <!-- Users Management -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Users Management</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['nom']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($user['role']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $user['status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                    <?php echo htmlspecialchars($user['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <?php if ($user['role'] == 'enseignant' && $user['status'] == 'pending'): ?>
                                                    <a href="?action=validateTeacher&id=<?php echo $user['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Validate</a>
                                                <?php endif; ?>
                                                <?php if ($user['role'] != 'administrateur'): ?>
                                                    <a href="?action=deleteUser&id=<?php echo $user['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php elseif ($_GET['section'] == 'courses'): ?>
                <!-- Courses Management -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Courses Management</h2>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($courses as $course): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($course['titre']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($course['enseignant']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($course['categorie']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $course['status'] == 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                    <?php echo htmlspecialchars($course['status']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <?php if ($course['status'] == 'pending'): ?>
                                                    <a href="?action=approveCourse&id=<?php echo $course['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Approve</a>
                                                <?php endif; ?>
                                                <a href="?action=deleteCourse&id=<?php echo $course['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            <?php elseif ($_GET['section'] == 'tags'): ?>
                <!-- Tags Management -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h2 class="text-xl font-bold mb-4">Tags Management</h2>
                        
                        <!-- Add Tag Form -->
                        <form action="?action=addTag" method="POST" class="mb-6">
                            <div class="flex gap-4">
                                <input type="text" name="tag_name" required placeholder="Enter tag name" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Add Tag</button>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($tags as $tag): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($tag['nom']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap"><?php echo date('M d, Y', strtotime($tag['created_at'])); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="?action=deleteTag&id=<?php echo $tag['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this tag?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>