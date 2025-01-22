<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Youdemy</title>
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
<body class="font-sans font-normal antialiased bg-gray-50 text-gray-900 min-h-screen">
    <!-- Header Section -->
    <div class="h-24 w-full absolute top-0 left-0 bg-primary"></div>
    <div class="h-24 z-20 relative container mx-auto flex items-center justify-between px-6">
        <a href="index.php?action=home" class="text-xl font-extrabold italic tracking-tighter text-white uppercase">YOUDEMY</a>
        <div class="flex items-center space-x-4">
            <span class="text-white">Welcome, Teacher</span>
            <a href="index.php?action=logout" class="bg-white text-primary px-4 py-2 rounded-full font-semibold hover:bg-opacity-90 transition duration-300">
                Logout
            </a>
        </div>
    </div>

    <main class="container mx-auto px-6 py-12 mt-24">
        <!-- Statistics Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Courses</p>
                        <h3 class="text-2xl font-bold text-primary mt-1" id="totalCourses">-</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-full">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Students</p>
                        <h3 class="text-2xl font-bold text-primary mt-1" id="totalStudents">-</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-full">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Most Popular Course</p>
                        <h3 class="text-2xl font-bold text-primary mt-1" id="popularCourse">-</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded-full">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Management Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900">My Courses</h2>
                <button onclick="openAddCourseModal()" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-full hover:bg-secondary transition-colors duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Course
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="coursesList">
                        <!-- Course list will be populated here -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="min-h-screen px-4 text-center">
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Course</h3>
                            <form id="addCourseForm" class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <input type="text" name="titre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Category</label>
                                    <select name="categorie_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Image</label>
                                    <input type="file" name="image" accept="image/*" class="mt-1 block w-full">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitCourse()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Add Course
                    </button>
                    <button type="button" onclick="closeAddCourseModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openAddCourseModal() {
            document.getElementById('addCourseModal').classList.remove('hidden');
        }

        function closeAddCourseModal() {
            document.getElementById('addCourseModal').classList.add('hidden');
        }

        // Course submission using ajouterCours method
        async function submitCourse() {
            const form = document.getElementById('addCourseForm');
            const formData = new FormData(form);
            
            try {
                const response = await fetch('index.php?action=ajouterCours', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                if (response.ok) {
                    closeAddCourseModal();
                    loadTeacherCourses();
                    form.reset();
                } else {
                    alert('Failed to add course');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred');
            }
        }

        // Load teacher's courses using consulterCours method
        async function loadTeacherCourses() {
            try {
                const response = await fetch('index.php?action=consulterCours', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load courses');
                }

                const courses = await response.json();
                
                const coursesList = document.getElementById('coursesList');
                coursesList.innerHTML = courses.map(course => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full object-cover" src="${course.image}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${course.titre}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${course.categorie.nom}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${course.inscriptions ? course.inscriptions.length : 0}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="editCourse(${course.id})" class="text-primary hover:text-secondary mr-3">Edit</button>
                            <button onclick="deleteCourse(${course.id})" class="text-red-600 hover:text-red-900">Delete</button>
                            <button onclick="viewEnrollments(${course.id})" class="text-primary hover:text-secondary ml-3">View Students</button>
                        </td>
                    </tr>
                `).join('');
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Edit course using modifierCours method
        async function editCourse(courseId) {
            try {
                const response = await fetch(`index.php?action=modifierCours&id=${courseId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(document.getElementById('editCourseForm'))
                });
                
                if (!response.ok) {
                    throw new Error('Failed to update course');
                }
                
                loadTeacherCourses();
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update course');
            }
        }

        // Delete course using supprimerCours method
        async function deleteCourse(courseId) {
            if (!confirm('Are you sure you want to delete this course?')) {
                return;
            }

            try {
                const response = await fetch(`index.php?action=supprimerCours&id=${courseId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to delete course');
                }
                
                loadTeacherCourses();
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete course');
            }
        }

        // View course enrollments using consulterInscriptions method
        async function viewEnrollments(courseId) {
            try {
                const response = await fetch(`index.php?action=consulterInscriptions&id=${courseId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Failed to load enrollments');
                }

                const data = await response.json();
                const enrollments = data.inscriptions || [];
                const course = data.course || {};
                
                // Update enrollments display
                const enrollmentsList = document.getElementById('enrollmentsList');
                if (enrollmentsList) {
                    enrollmentsList.innerHTML = enrollments.map(enrollment => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${enrollment.etudiant_nom}</div>
                                        <div class="text-sm text-gray-500">${enrollment.etudiant_email}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${enrollment.cours_titre}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${new Date(enrollment.date_inscription).toLocaleDateString()}</div>
                            </td>
                        </tr>
                    `).join('') || '<tr><td colspan="3" class="px-6 py-4 text-center">No enrollments found</td></tr>';
                }

                // Update course title if viewing specific course
                const enrollmentsTitle = document.getElementById('enrollmentsTitle');
                if (enrollmentsTitle && course.titre) {
                    enrollmentsTitle.textContent = `Students Enrolled in ${course.titre}`;
                }
                
            } catch (error) {
                console.error('Error:', error);
                const enrollmentsList = document.getElementById('enrollmentsList');
                if (enrollmentsList) {
                    enrollmentsList.innerHTML = '<tr><td colspan="3" class="px-6 py-4 text-center text-red-600">Failed to load enrollments. Please try again.</td></tr>';
                }
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadTeacherCourses();
            // Load initial statistics from enrolled students
            viewEnrollments();
        });
    </script>
</body>
</html>
