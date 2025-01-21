<?php
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$data = isset($courses) ? $courses : (isset($results) ? $results : []);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Course - Youdemy</title>
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
        <div x-data="{ mobileMenu: false }" class="text-white text-lg fixed bottom-0 left-0 lg:relative p-6 lg:p-0 w-full lg:w-auto max-w-lg">
            <div x-bind:class="{ 'flex': mobileMenu, 'hidden': !mobileMenu }" class="lg:flex flex-col lg:flex-row items-center justify-center bg-primary lg:bg-transparent pt-6 pb-8 lg:m-auto lg:gap-5 lg:p-0 -mb-6 lg:m-0 rounded-t-3xl shadow-2xl lg:shadow-none">
                <?php if ($role === 'etudiant'): ?>
                    <span class="my-2 lg:ml-6">Welcome <?= htmlspecialchars($userName) ?></span>
                    <a href="index.php?action=myCourses" class="my-2 lg:ml-6">My Courses</a>
                    <a href="index.php?action=courses" class="my-2 lg:ml-6">Courses</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php elseif ($role === 'teacher'): ?>
                    <span class="my-2 lg:ml-6">Welcome <?= htmlspecialchars($userName) ?></span>
                    <a href="index.php?action=teacherDashboard" class="my-2 lg:ml-6">Dashboard</a>
                    <a href="index.php?action=createCourse" class="my-2 lg:ml-6">Create Course</a>
                    <a href="index.php?action=logout" class="my-2 lg:ml-6">Logout</a>
                <?php else: ?>
                    <a href="index.php?action=courses" class="my-2 lg:ml-6">Courses</a>
                    <a href="index.php?action=loginPage" class="my-2 lg:ml-6">Login</a>
                    <a href="index.php?action=registerPage" class="my-2 lg:ml-6">Register</a>
                <?php endif; ?>
            </div>
            <!-- Mobile Menu Button -->
            <button @click="mobileMenu = !mobileMenu" type="button" class="lg:hidden bg-primary text-white rounded-3xl w-full py-4 text-center uppercase text-sm shadow-2xl lg:shadow-none focus:outline-none">
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

    <!-- Search and Filter Section -->
    <div class="pt-32 pb-12 bg-primary">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <!-- Search Bar -->
                <div class="bg-white rounded-lg p-4 shadow-lg mb-6">
                    <form action="index.php?action=courses" method="GET">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" name="keyword" placeholder="Search courses..." class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                            </div>
                            <button type="submit" name="action" value="search" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition-colors">
                                Search
                            </button>
                        </div>
                    </form>

                    <!-- Filters -->
                    <div class="mt-4 flex flex-wrap gap-4">
                        <!-- Categories Dropdown -->
                        <select class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                            <option value="">All Categories</option>
                            <option value="development">Development</option>
                            <option value="business">Business</option>
                            <option value="design">Design</option>
                            <option value="marketing">Marketing</option>
                        </select>

                        <!-- Popular Tags -->
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                Web Development
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                JavaScript
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                Python
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm cursor-pointer hover:bg-primary hover:text-white transition-colors">
                                UI/UX
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Grid -->
    <div class="container mx-auto px-6 py-12">
        <!-- Results Count and Sort -->
        <div class="flex justify-between items-center mb-8">
            <p class="text-gray-600">Showing <?= ($page - 1) * $limit + 1 ?>-<?= min($page * $limit, $totalCourses) ?> of <?= $totalCourses ?> courses</p>
            <select class="px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="newest">Newest First</option>
                <option value="popular">Most Popular</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
            </select>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php foreach ($data as $item) : ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg cursor-pointer" onclick="openModal(<?= htmlspecialchars(json_encode(['id' => $item['id'], 'titre' => $item['titre'], 'description' => $item['description'], 'image' => $item['image'], 'teacher_name' => $item['teacher_name'], 'category_name' => $item['category_name'], 'contenu' => $item['contenu']])) ?>)">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="Thumbnail" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($item['titre']) ?></h3>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                        <?php if ($role === 'etudiant'): ?>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold">Teacher: <?= htmlspecialchars($item['teacher_name']) ?></span>
                                <span class="text-primary font-bold"><?= htmlspecialchars($item['category_name']) ?></span>
                            </div>
                        <?php elseif ($role === 'teacher'): ?>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold">Teacher: <?= htmlspecialchars($item['teacher_name']) ?></span>
                                <span class="text-primary font-bold"><?= htmlspecialchars($item['category_name']) ?></span>
                            </div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($item['contenu']) ?></div>
                        <?php else: ?>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-primary font-bold"><?= htmlspecialchars($item['category_name']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center items-center space-x-2">
            <?php if ($page > 1): ?>
                <a href="?action=courses&page=<?= $page - 1 ?>" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">
                    Previous
                </a>
            <?php endif; ?>

            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <?php if ($i == $page): ?>
                    <a href="?action=courses&page=<?= $i ?>" class="px-4 py-2 border rounded-lg bg-primary text-white"><?= $i ?></a>
                <?php else: ?>
                    <a href="?action=courses&page=<?= $i ?>" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?action=courses&page=<?= $page + 1 ?>" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">
                    Next
                </a>
            <?php endif; ?>
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
                        <li><a href="index.php?action=login" class="hover:text-primary">Login</a></li>
                        <li><a href="index.php?action=register" class="hover:text-primary">Register</a></li>
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
                <p>&copy; <?= date('Y') ?> Youdemy. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <!-- Modal -->
    <div id="courseModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                            <div class="mt-2">
                                <img class="w-1/2 h-1/2 object-cover object-center rounded-lg" id="modal-image" src="" alt="">
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-description"></p>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600" id="modal-teacher"></p>
                                <p class="text-sm text-gray-600" id="modal-category"></p>
                                <p class="text-sm text-gray-600" id="modal-content"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <?php if ($role === 'etudiant'): ?>
                        <button type="button" id="enrollButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                            Enroll
                        </button>
                    <?php endif; ?>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Assume userRole is set dynamically, e.g., 'etudiant', 'guest', etc.
        const userRole = '<?= $role ?>'; // Dynamically get the role from the backend.
        let currentCourse = null;

        function openModal(course) {
            if (userRole !== 'etudiant') {
                alert('Please login as a student to enroll in courses.');
                return;
            }

            currentCourse = course;
            document.getElementById('modal-title').textContent = course.titre;
            document.getElementById('modal-image').src = course.image;
            document.getElementById('modal-description').textContent = course.description;
            document.getElementById('modal-teacher').textContent = 'Teacher: ' + course.teacher_name;
            document.getElementById('modal-category').textContent = 'Category: ' + course.category_name;
            document.getElementById('modal-content').textContent = course.contenu;

            const enrollButton = document.getElementById('enrollButton');
            if (enrollButton) {
                enrollButton.onclick = () => enrollInCourse(course.id, <?= $user_id ?>);
            }

            document.getElementById('courseModal').classList.remove('hidden');
        }

        function closeModal() {
            currentCourse = null;
            document.getElementById('courseModal').classList.add('hidden');
        }

        function enrollInCourse(cours_id, user_id) {
            if (!user_id) {
                alert('Please login to enroll in courses.');
                window.location.href = 'index.php?action=loginPage';
                return;
            }

            if (!cours_id) {
                console.error('No course ID provided');
                alert('Error: Course ID is missing');
                return;
            }

            console.log('Starting enrollment process...');
            console.log('Course ID:', cours_id);
            console.log('User ID:', user_id);

            const formData = new FormData();
            formData.append('user_id', user_id);
            formData.append('cours_id', cours_id);

            // Log the form data
            for (let [key, value] of formData.entries()) {
                console.log('Form data -', key + ':', value);
            }

            fetch('index.php?action=inscrireCours', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response received');
                console.log('Response status:', response.status);
                console.log('Response headers:', [...response.headers.entries()]);
                
                // First check if we got a successful response
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Try to parse as JSON, but first log the raw response
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(data => {
                console.log('Parsed response data:', data);
                
                if (!data) {
                    throw new Error('Empty response from server');
                }
                
                if (data.status === 'error') {
                    console.error('Server reported error:', data.message);
                    alert('Error: ' + data.message);
                } else {
                    console.log('Enrollment successful:', data.message);
                    alert(data.message);
                    window.location.href = 'index.php?action=myCourses';
                }
            })
            .catch(error => {
                console.error('Detailed error:', error);
                console.error('Error stack:', error.stack);
                alert('Failed to enroll in the course. Please try again. Error: ' + error.message);
            });

            console.log('Closing modal...');
            closeModal();
        }

        // Close the modal if the user clicks outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('courseModal');
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>
</body>

</html>