<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Statistics.php';

class TeacherController {
    public function index() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $teacherId = $_SESSION['user']['id'];
        $stats = Statistics::getTeacherStats($teacherId);
        $courses = Course::getByTeacher($teacherId);
        require_once __DIR__ . '/../views/teacher/dashboard.php';
    }

    public function courses() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $teacherId = $_SESSION['user']['id'];
        $courses = Course::getByTeacher($teacherId);
        require_once __DIR__ . '/../views/teacher/courses/index.php';
    }

    public function createCourse() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'titre' => $_POST['titre'],
                    'description' => $_POST['description'],
                    'contenu' => $_POST['contenu'],
                    'categorie_id' => $_POST['categorie_id'],
                    'enseignant_id' => $_SESSION['user']['id']
                ];

                // Handle image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../public/uploads/courses/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $data['image'] = 'uploads/courses/' . $fileName;
                    }
                }

                Course::create($data);
                $_SESSION['success'] = "Course created successfully.";
                header('Location: index.php?action=teacher&page=courses');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Error creating course: " . $e->getMessage();
            }
        }

        $categories = Category::getAll();
        $tags = Tag::getAll();
        require_once __DIR__ . '/../views/teacher/courses/create.php';
    }

    public function editCourse() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['id'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=teacher&page=courses');
            exit;
        }

        $course = Course::getById($courseId);
        if (!$course || $course['enseignant_id'] != $_SESSION['user']['id']) {
            header('Location: index.php?action=teacher&page=courses');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'titre' => $_POST['titre'],
                    'description' => $_POST['description'],
                    'contenu' => $_POST['contenu'],
                    'categorie_id' => $_POST['categorie_id']
                ];

                // Handle image upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../public/uploads/courses/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $data['image'] = 'uploads/courses/' . $fileName;
                    }
                }

                Course::update($courseId, $data);
                $_SESSION['success'] = "Course updated successfully.";
                header('Location: index.php?action=teacher&page=courses');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Error updating course: " . $e->getMessage();
            }
        }

        $categories = Category::getAll();
        $tags = Tag::getAll();
        require_once __DIR__ . '/../views/teacher/courses/edit.php';
    }

    public function deleteCourse() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['id'] ?? null;
        if ($courseId) {
            try {
                $course = Course::getById($courseId);
                if ($course && $course['enseignant_id'] == $_SESSION['user']['id']) {
                    Course::delete($courseId);
                    $_SESSION['success'] = "Course deleted successfully.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting course: " . $e->getMessage();
            }
        }
        header('Location: index.php?action=teacher&page=courses');
    }

    private function isTeacher() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'enseignant';
    }
}
