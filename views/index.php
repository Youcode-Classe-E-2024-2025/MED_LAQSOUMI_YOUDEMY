<?php
// session_start();
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'submitCourseRequest':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'enseignant') {
                $courseTitle = $_POST['course-title'] ?? '';
                $courseProposal = $_POST['course-proposal'] ?? '';
                $teacherExpertise = $_POST['teacher-expertise'] ?? '';
                $courseCategory = $_POST['course-category'] ?? '';
                
                // Here you would typically save this data to your database
                // For now, we'll just print a success message
                echo "Course proposal submitted successfully. An administrator will review your request.";
                
                // Redirect back to the home page or a confirmation page
                header("Location: index.php?action=home&message=proposalSubmitted");
                exit;
            } else {
                // Handle invalid requests
                echo "Invalid request or insufficient permissions.";
            }
            break;
    }
}
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
                        'sans': ['Roboto Condensed', 'sans-serif']
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
        <a href="index.php?action=home" class="text-xl font-extrabold italic tracking-tighter text-white uppercase">YOUDEMY</a>
        <div x-data="{ mobileMenu : false }" class="text-white text-lg fixed bottom-0 left-0 lg:relative p-6 lg:p-0 w-full lg:w-auto max-w-lg">
            <div x-bind:class="{ 'flex' : mobileMenu, 'hidden' : !mobileMenu }" class="lg:flex flex-col lg:flex-row items-center justify-center bg-primary lg:bg-transparent pt-6 pb-8 lg:p-0 -mb-6 lg:m-0 rounded-t-3xl shadow-2xl lg:shadow-none">
                <?php if ($role === 'etudiant'): ?>
                    <span class="my-2 lg:ml-6">Welcome <strong><?php echo htmlspecialchars($userName); ?></strong></span>
                    <a href="index.php?action=myCourses" class="my-2 lg:ml-6">My Courses</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php elseif ($role === 'enseignant'): ?>
                    <span class="my-2 lg:ml-6">Welcome <strong><?php echo htmlspecialchars($userName); ?></strong></span>
                    <a href="index.php?action=teacherDashboard" class="my-2 lg:ml-6">Dashboard</a>
                    <a href="index.php?action=createCourse" class="my-2 lg:ml-6">Create Course</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php elseif ($role === 'administrateur'): ?>
                    <span class="my-2 lg:ml-6">Welcome <strong><?php echo htmlspecialchars($userName); ?></strong></span>
                    <a href="index.php?action=adminDashboard" class="my-2 lg:ml-6">Dashboard</a>
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
    <?php if ($role === 'enseignant'): ?>
    <div class="relative w-full bg-primary py-32">
        <div class="relative z-10 text-center text-white text-center mx-auto max-w-xl">
            <h2 class="text-3xl lg:text-5xl mb-4 font-bold uppercase italic">Start Teaching</h2>
            <p class="text-xl mb-6">Share your knowledge and create your course on Youdemy</p>
            <button onclick="openTeachingModal()" class="inline-block rounded-full border-2 border-white text-lg px-8 py-3 hover:bg-white hover:text-primary">Create Course</button>
        </div>
    </div>
<?php elseif ($role === 'etudiant'): ?>
    <!-- Students don't see this section -->
<?php else: ?>
    <div class="relative w-full bg-primary py-32">
        <div class="relative z-10 text-center text-white text-center mx-auto max-w-xl">
            <h2 class="text-3xl lg:text-5xl mb-4 font-bold uppercase italic">Become an Instructor</h2>
            <p class="text-xl mb-6">Share your knowledge and earn money teaching on Youdemy</p>
            <a href="index.php?action=registerPage" class="inline-block rounded-full border-2 border-white text-lg px-8 py-3 hover:bg-white hover:text-primary">Start Teaching</a>
        </div>
    </div>
<?php endif; ?>

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

    <?php if ($role === 'enseignant'): ?>
    <!-- Modal for teachers -->
    <div id="teachingModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="index.php?action=submitCourseRequest" method="POST">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Course Teaching Proposal
                        </h3>
                        <div class="mt-2 space-y-4">
                            <p class="text-sm text-gray-500">
                                Please provide details about the course you'd like to teach. Describe your expertise and what students will learn.
                            </p>
                            <div>
                                <label for="course-title" class="block text-sm font-medium text-gray-700">Course Title</label>
                                <input type="text" name="course-title" id="course-title" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="course-proposal" class="block text-sm font-medium text-gray-700">Teaching Proposal</label>
                                <textarea name="course-proposal" id="course-proposal" rows="5" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="I will teach... Students will learn..."></textarea>
                            </div>
                            <div>
                                <label for="teacher-expertise" class="block text-sm font-medium text-gray-700">Your Expertise</label>
                                <textarea name="teacher-expertise" id="teacher-expertise" rows="3" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Describe your qualifications and experience in this subject..."></textarea>
                            </div>
                            <div>
                                <label for="course-category" class="block text-sm font-medium text-gray-700">Course Category</label>
                                <select name="course-category" id="course-category" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select a category</option>
                                    <option value="development">Development</option>
                                    <option value="business">Business</option>
                                    <option value="design">Design</option>
                                    <option value="marketing">Marketing</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Submit Proposal
                        </button>
                        <button type="button" onclick="closeTeachingModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openTeachingModal() {
            document.getElementById('teachingModal').classList.remove('hidden');
        }

        function closeTeachingModal() {
            document.getElementById('teachingModal').classList.add('hidden');
        }
    </script>
<?php endif; ?>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</body>

</html>

