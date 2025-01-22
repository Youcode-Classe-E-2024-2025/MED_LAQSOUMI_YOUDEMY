<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - YOUDEMY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-counter"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .container-fluid {
            padding: 1rem;
            width: 100%;
        }

        .card {
            margin-bottom: 1rem;
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: transform 0.15s ease-in-out;
        }

        .stats-card {
            border-radius: 0.35rem;
        }

        .stats-card .card-body {
            padding: 0.75rem;
        }

        .border-left-primary { border-left: 3px solid #4e73df; }
        .border-left-success { border-left: 3px solid #1cc88a; }
        .border-left-warning { border-left: 3px solid #f6c23e; }
        .border-left-info { border-left: 3px solid #36b9cc; }

        .text-xs {
            font-size: 0.65rem;
            letter-spacing: 0.05em;
        }

        .stat-icon {
            font-size: 1.35rem;
            opacity: 0.3;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block !important;
            }
            
            .sidebar {
                position: fixed;
                left: -256px;
                transition: left 0.3s ease;
                z-index: 50;
                height: 100vh;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .chart-container {
                min-height: 300px !important;
            }
        }

        /* Chart responsiveness */
        .chart-container {
            position: relative;
            min-height: 400px;
            width: 100%;
        }

        /* Grid system */
        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
        
        @media (min-width: 640px) {
            .sm\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Mobile Menu Toggle -->
        <button class="sidebar-toggle fixed top-4 left-4 z-50 p-2 rounded-lg bg-gray-800 text-white md:hidden">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <aside class="sidebar w-64 bg-gray-800 text-white">
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
        <main class="flex-1 p-4 md:p-8">
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
                <div class="container-fluid">
                    <!-- Dashboard Header -->
                    <div class="dashboard-header mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Dashboard Overview</h1>
                    </div>

                    <!-- Statistics Cards Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <!-- Total Users Card -->
                        <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-left-primary">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase mb-1">TOTAL USERS</p>
                                    <div class="text-xl font-bold text-gray-800" id="totalUsersCount"><?php echo $totalUsers; ?></div>
                                </div>
                                <i class="fas fa-users stat-icon text-gray-300"></i>
                            </div>
                        </div>

                        <!-- Total Courses Card -->
                        <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-left-success">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase mb-1">TOTAL COURSES</p>
                                    <div class="text-xl font-bold text-gray-800" id="totalCoursesCount"><?php echo $totalCourses; ?></div>
                                </div>
                                <i class="fas fa-book stat-icon text-gray-300"></i>
                            </div>
                        </div>

                        <!-- Pending Teachers Card -->
                        <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-left-warning">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase mb-1">PENDING TEACHERS</p>
                                    <div class="text-xl font-bold text-gray-800" id="pendingTeachersCount"><?php echo $pendingTeachers; ?></div>
                                </div>
                                <i class="fas fa-user-clock stat-icon text-gray-300"></i>
                            </div>
                        </div>

                        <!-- Total Tags Card -->
                        <div class="stats-card bg-white rounded-lg shadow p-4 border-l-4 border-left-info">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xs font-bold text-gray-600 uppercase mb-1">TOTAL TAGS</p>
                                    <div class="text-xl font-bold text-gray-800" id="totalTagsCount"><?php echo $totalTags; ?></div>
                                </div>
                                <i class="fas fa-tags stat-icon text-gray-300"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- User Growth Chart -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h2 class="text-lg font-bold text-gray-800 mb-4">User Growth Overview</h2>
                            <div class="chart-container">
                                <canvas id="userGrowthChart"></canvas>
                            </div>
                        </div>

                        <!-- User Distribution Chart -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h2 class="text-lg font-bold text-gray-800 mb-4">User Distribution</h2>
                            <div class="chart-container">
                                <canvas id="userDistributionChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Course and Activity Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Course Distribution Chart -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h2 class="text-lg font-bold text-gray-800 mb-4">Course Distribution</h2>
                            <div class="chart-container">
                                <canvas id="courseDistributionChart"></canvas>
                            </div>
                        </div>

                        <!-- Recent Activities -->
                        <div class="bg-white rounded-lg shadow p-4">
                            <h2 class="text-lg font-bold text-gray-800 mb-4">Recent Activities</h2>
                            <div class="timeline">
                                <?php foreach ($recentActivities as $activity): ?>
                                    <div class="timeline-item">
                                        <div class="d-flex align-items-center">
                                            <div class="timeline-icon">
                                                <?php if ($activity['action'] === 'Created Course'): ?>
                                                    <i class="fas fa-book text-success"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-user-plus text-primary"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="font-weight-bold" style="font-size: 0.8rem;">
                                                    <?php echo htmlspecialchars($activity['user_name']); ?>
                                                </div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    <?php echo htmlspecialchars($activity['action']); ?>
                                                    <?php if ($activity['details']): ?>
                                                        - <?php echo htmlspecialchars($activity['details']); ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-muted" style="font-size: 0.7rem;">
                                                    <?php echo date('M d, H:i', strtotime($activity['date'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($_GET['section'] == 'users'): ?>
                <!-- Users Management -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Users Management</h2>
                    </div>
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

            <?php elseif ($_GET['section'] == 'courses'): ?>
                <!-- Courses Management -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Courses Management</h2>
                    </div>
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

            <?php elseif ($_GET['section'] == 'tags'): ?>
                <!-- Tags Management -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Tags Management</h2>
                    </div>
                    
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
            <?php endif; ?>
        </main>
    </div>

    <script src="assets/js/admin-charts.js"></script>
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(e.target) && 
                    !sidebarToggle.contains(e.target) && 
                    sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });

            // Initialize charts and counters
            try {
                // Prepare the data
                const statsData = {
                    totalUsers: <?php echo $totalUsers ?? 0; ?>,
                    totalCourses: <?php echo $totalCourses ?? 0; ?>,
                    pendingTeachers: <?php echo $pendingTeachers ?? 0; ?>,
                    totalTags: <?php echo $totalTags ?? 0; ?>
                };

                const userData = {
                    labels: <?php echo json_encode(array_keys($userGrowthData ?? [])); ?>,
                    data: <?php echo json_encode(array_values($userGrowthData ?? [])); ?>,
                    totalUsers: <?php echo $totalUsers ?? 0; ?>,
                    students: <?php echo $studentCount ?? 0; ?>,
                    teachers: <?php echo $teacherCount ?? 0; ?>,
                    admins: <?php echo $adminCount ?? 0; ?>,
                    pendingTeachers: <?php echo $pendingTeachers ?? 0; ?>
                };

                const courseData = {
                    labels: <?php echo json_encode(array_keys($courseDistributionData ?? [])); ?>,
                    data: <?php echo json_encode(array_values($courseDistributionData ?? [])); ?>,
                    totalCourses: <?php echo $totalCourses ?? 0; ?>,
                    totalTags: <?php echo $totalTags ?? 0; ?>
                };
                initializeCharts(userData, courseData);

                initializeStatCounters(statsData);

            } catch (error) {
                console.error('Error initializing dashboard:', error);
            }
        });
    </script>
</body>
</html>