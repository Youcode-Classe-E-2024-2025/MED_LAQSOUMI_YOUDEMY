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
                <?php elseif ($role === 'enseignant'): ?>
                    <span class="my-2 lg:ml-6">Welcome <?= htmlspecialchars($userName) ?></span>
                    <a href="index.php?action=teacherDashboard" class="my-2 lg:ml-6">Dashboard</a>
                    <a href="index.php?action=courses" class="my-2 lg:ml-6">Courses</a>
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

    <!-- Course Grid -->
    <div class="container mx-auto px-6 py-12 mt-24">
        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <?php foreach ($data as $item) : ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg cursor-pointer" onclick="openModal(<?= htmlspecialchars(json_encode($item)) ?>)">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="Thumbnail" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($item['titre']) ?></h3>
                        <p class="text-gray-600 mb-4"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold">Teacher: <?= htmlspecialchars($item['teacher_name']) ?></span>
                            <span class="text-primary font-bold"><?= htmlspecialchars($item['category_name']) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="flex justify-center items-center space-x-2">
            <?php if ($page > 1): ?>
                <a href="?action=courses&page=<?= $page - 1 ?>" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">
                    Previous
                </a>
            <?php endif; ?>

            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                <a href="?action=courses&page=<?= $i ?>" 
                   class="px-4 py-2 border rounded-lg <?= $i == $page ? 'bg-primary text-white' : 'hover:bg-primary hover:text-white' ?> transition-colors">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?action=courses&page=<?= $page + 1 ?>" class="px-4 py-2 border rounded-lg hover:bg-primary hover:text-white transition-colors">
                    Next
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Course Modal -->
    <div id="courseModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title"></h3>
                            <div class="mt-2">
                                <img class="w-full h-48 object-cover object-center rounded-lg" id="modal-image" src="" alt="">
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500" id="modal-description"></p>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600" id="modal-teacher"></p>
                                <p class="text-sm text-gray-600" id="modal-category"></p>
                                <?php if ($role === 'etudiant'): ?>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600" id="modal-content"></p>
                                </div>
                                <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
    <script>
        let currentCourse = null;

        function openModal(course) {
            currentCourse = course;
            document.getElementById('modal-title').textContent = course.titre;
            document.getElementById('modal-image').src = course.image;
            document.getElementById('modal-description').textContent = course.description;
            document.getElementById('modal-teacher').textContent = 'Teacher: ' + course.teacher_name;
            document.getElementById('modal-category').textContent = 'Category: ' + course.category_name;
            if (document.getElementById('modal-content')) {
                document.getElementById('modal-content').textContent = course.contenu;
            }
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('courseModal').classList.add('hidden');
            currentCourse = null;
        }

        <?php if ($role === 'etudiant'): ?>
        document.getElementById('enrollButton').addEventListener('click', function() {
            if (!currentCourse) {
                alert('No course selected');
                return;
            }

            const formData = new FormData();
            formData.append('cours_id', currentCourse.id);

            fetch('index.php?action=inscrireCours', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Successfully enrolled in the course!');
                    window.location.href = 'index.php?action=myCourses';
                } else {
                    alert('Error: ' + (data.message || 'Failed to enroll in the course'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to enroll in the course. Please try again.');
            });

            closeModal();
        });
        <?php endif; ?>

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