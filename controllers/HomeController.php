<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';

class HomeController {
    public function index() {
        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

        $coursesData = Course::getPublishedCourses($page, 9, $search, $categoryId);
        $categories = Category::getAll();

        require_once __DIR__ . '/../views/home/index.php';
    }

    public function viewCourse($id) {
        $course = Course::getWithDetails($id);
        
        if (!$course || !$course['published']) {
            $_SESSION['error'] = "Course not found or not available.";
            header('Location: index.php');
            exit;
        }

        require_once __DIR__ . '/../views/home/course-details.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate required fields
                $requiredFields = ['nom', 'email', 'mot_de_passe', 'role'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("All fields are required.");
                    }
                }

                // Validate role
                if (!in_array($_POST['role'], ['etudiant', 'enseignant'])) {
                    throw new Exception("Invalid role selected.");
                }

                // Check if email already exists
                if (User::emailExists($_POST['email'])) {
                    throw new Exception("Email already registered.");
                }

                // Create user
                $userData = [
                    'nom' => $_POST['nom'],
                    'email' => $_POST['email'],
                    'mot_de_passe' => $_POST['mot_de_passe'],
                    'role' => $_POST['role']
                ];

                if (User::create($userData)) {
                    $_SESSION['success'] = "Registration successful! Please login.";
                    header('Location: index.php?action=login');
                    exit;
                }

            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }
}
