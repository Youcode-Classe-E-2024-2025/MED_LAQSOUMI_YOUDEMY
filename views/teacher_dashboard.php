<?php
require_once 'header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
    header('Location: /YOUDEMY/login');
    exit;
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Teacher Dashboard</h1>
    
    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Courses</h5>
                    <p class="card-text" id="totalCourses">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text" id="totalStudents">Loading...</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Most Popular Course</h5>
                    <p class="card-text" id="popularCourse">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Management Section -->
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">My Courses</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                Add New Course
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="coursesList">
                        <tr>
                            <td colspan="4" class="text-center">Loading courses...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addCourseForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="titre" class="form-label">Title</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="contenu" class="form-label">Content</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="categorie_id" class="form-label">Category</label>
                        <select class="form-control" id="categorie_id" name="categorie_id" required>
                            <option value="">Select a category</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <select class="form-control" id="tags" name="tags[]" multiple>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Course Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitCourse()">Add Course</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editCourseForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_course_id" name="course_id">
                    <input type="hidden" id="current_image" name="current_image">
                    <div class="mb-3">
                        <label for="edit_titre" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_titre" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_contenu" class="form-label">Content</label>
                        <textarea class="form-control" id="edit_contenu" name="contenu" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_categorie_id" class="form-label">Category</label>
                        <select class="form-control" id="edit_categorie_id" name="categorie_id" required>
                            <option value="">Select a category</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tags" class="form-label">Tags</label>
                        <select class="form-control" id="edit_tags" name="tags[]" multiple>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_image" class="form-label">Course Image</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <div id="current_image_preview" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateCourse()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- View Enrollments Modal -->
<div class="modal fade" id="enrollmentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Course Enrollments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Enrollment Date</th>
                            </tr>
                        </thead>
                        <tbody id="enrollmentsList">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load statistics
function loadStatistics() {
    fetch('/YOUDEMY/teacher/statistics')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalCourses').textContent = data.statistics.total_courses;
                document.getElementById('totalStudents').textContent = data.statistics.total_students;
                if (data.statistics.enrollments_per_course.length > 0) {
                    const mostPopular = data.statistics.enrollments_per_course[0];
                    document.getElementById('popularCourse').textContent = 
                        `${mostPopular.titre} (${mostPopular.enrollment_count} students)`;
                } else {
                    document.getElementById('popularCourse').textContent = 'No courses yet';
                }
            }
        })
        .catch(error => console.error('Error loading statistics:', error));
}

// Load courses
function loadCourses() {
    fetch('/YOUDEMY/course/list')
        .then(response => response.json())
        .then(data => {
            const coursesList = document.getElementById('coursesList');
            coursesList.innerHTML = '';
            
            data.courses.forEach(course => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${course.titre}</td>
                    <td>${course.category_name}</td>
                    <td>${course.enrollment_count || 0}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="viewEnrollments(${course.id})">
                            View Enrollments
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="editCourse(${course.id})">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCourse(${course.id})">
                            Delete
                        </button>
                    </td>
                `;
                coursesList.appendChild(row);
            });
        })
        .catch(error => console.error('Error loading courses:', error));
}

// Submit new course
function submitCourse() {
    const form = document.getElementById('addCourseForm');
    const formData = new FormData(form);

    fetch('/YOUDEMY/course/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#addCourseModal').modal('hide');
            form.reset();
            loadCourses();
            loadStatistics();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error adding course:', error));
}

// Update course
function updateCourse() {
    const form = document.getElementById('editCourseForm');
    const formData = new FormData(form);

    fetch('/YOUDEMY/course/update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editCourseModal').modal('hide');
            loadCourses();
            loadStatistics();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error updating course:', error));
}

// Delete course
function deleteCourse(courseId) {
    if (!confirm('Are you sure you want to delete this course?')) {
        return;
    }

    const formData = new FormData();
    formData.append('course_id', courseId);

    fetch('/YOUDEMY/course/delete', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCourses();
            loadStatistics();
        } else {
            alert('Error: ' + data.error);
        }
    })
    .catch(error => console.error('Error deleting course:', error));
}

// View enrollments
function viewEnrollments(courseId) {
    fetch(`/YOUDEMY/course/enrollments?course_id=${courseId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const enrollmentsList = document.getElementById('enrollmentsList');
                enrollmentsList.innerHTML = '';
                
                data.enrollments.forEach(enrollment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${enrollment.student_name}</td>
                        <td>${enrollment.student_email}</td>
                        <td>${new Date(enrollment.date_inscription).toLocaleDateString()}</td>
                    `;
                    enrollmentsList.appendChild(row);
                });
                
                $('#enrollmentsModal').modal('show');
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => console.error('Error loading enrollments:', error));
}

// Load categories and tags for forms
function loadFormData() {
    // Load categories
    fetch('/YOUDEMY/categories')
        .then(response => response.json())
        .then(data => {
            const categoryOptions = data.categories.map(cat => 
                `<option value="${cat.id}">${cat.nom}</option>`
            ).join('');
            document.getElementById('categorie_id').innerHTML += categoryOptions;
            document.getElementById('edit_categorie_id').innerHTML += categoryOptions;
        });

    // Load tags
    fetch('/YOUDEMY/tags')
        .then(response => response.json())
        .then(data => {
            const tagOptions = data.tags.map(tag => 
                `<option value="${tag.id}">${tag.nom}</option>`
            ).join('');
            document.getElementById('tags').innerHTML = tagOptions;
            document.getElementById('edit_tags').innerHTML = tagOptions;
        });
}

// Edit course - load data into form
function editCourse(courseId) {
    fetch(`/YOUDEMY/course/${courseId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const course = data.course;
                document.getElementById('edit_course_id').value = course.id;
                document.getElementById('edit_titre').value = course.titre;
                document.getElementById('edit_description').value = course.description;
                document.getElementById('edit_contenu').value = course.contenu;
                document.getElementById('edit_categorie_id').value = course.categorie_id;
                document.getElementById('current_image').value = course.image;
                
                if (course.image) {
                    document.getElementById('current_image_preview').innerHTML = 
                        `<img src="/YOUDEMY/${course.image}" class="img-thumbnail" style="max-height: 100px">`;
                }

                // Set selected tags
                const tagSelect = document.getElementById('edit_tags');
                Array.from(tagSelect.options).forEach(option => {
                    option.selected = course.tags.includes(parseInt(option.value));
                });

                $('#editCourseModal').modal('show');
            }
        })
        .catch(error => console.error('Error loading course details:', error));
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadStatistics();
    loadCourses();
    loadFormData();
});
</script>

<?php require_once 'footer.php'; ?>