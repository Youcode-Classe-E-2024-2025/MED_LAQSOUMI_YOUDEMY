<?php
session_start();

require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/AdminController.php';
require_once 'controllers/EtudiantController.php';
require_once 'controllers/EnseignantController.php';
require_once 'controllers/CourseController.php';

$action = $_GET['action'] ?? 'home';
$page = $_GET['page'] ?? '';

try {
    switch ($action) {
        case 'home':
            $controller = new HomeController();
            $controller->index();
            break;

        case 'login':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->login();
            } else {
                $controller->showLoginForm();
            }
            break;

        case 'register':
            $controller = new AuthController();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->register();
            } else {
                $controller->showRegistrationForm();
            }
            break;

        case 'logout':
            $controller = new AuthController();
            $controller->logout();
            break;

        case 'admin':
            $controller = new AdminController();
            switch ($page) {
                case 'users':
                    $controller->users();
                    break;
                case 'delete-user':
                    $controller->deleteUser();
                    break;
                case 'categories':
                    $controller->categories();
                    break;
                case 'add-category':
                    $controller->addCategory();
                    break;
                case 'edit-category':
                    $controller->editCategory();
                    break;
                case 'delete-category':
                    $controller->deleteCategory();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        case 'etudiant':
            $controller = new EtudiantController();
            switch ($page) {
                case 'courses':
                    $controller->courses();
                    break;
                case 'my-courses':
                    $controller->myCourses();
                    break;
                case 'course':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $controller->viewCourse($id);
                    } else {
                        throw new Exception("Course ID is required");
                    }
                    break;
                case 'enroll':
                    $controller->enroll();
                    break;
                case 'complete-course':
                    $controller->completeCourse();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        case 'enseignant':
            $controller = new EnseignantController();
            switch ($page) {
                case 'courses':
                    $controller->courses();
                    break;
                case 'add-course':
                    $controller->addCourse();
                    break;
                case 'edit-course':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $controller->editCourse($id);
                    } else {
                        throw new Exception("Course ID is required");
                    }
                    break;
                case 'delete-course':
                    $controller->deleteCourse();
                    break;
                default:
                    $controller->index();
                    break;
            }
            break;

        case 'visitor':
            $controller = new CourseController();
            switch ($page) {
                case 'courses':
                    $controller->getAll();
                    break;
                case 'course':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $controller->view($id);
                    } else {
                        throw new Exception("Course ID is required");
                    }
                    break;
                default:
                    require_once __DIR__ . '/views/home.php';
                    break;
            }
            break;

        case 'student':
            $controller = new CourseController();
            switch ($page) {
                case 'courses':
                    $controller->getAll();
                    break;
                case 'course':
                    $id = $_GET['id'] ?? null;
                    if ($id) {
                        $controller->view($id);
                    } else {
                        throw new Exception("Course ID is required");
                    }
                    break;
                case 'enroll':
                    $courseId = $_GET['course'] ?? null;
                    if ($courseId) {
                        $controller->enroll($courseId);
                    } else {
                        throw new Exception("Course ID is required");
                    }
                    break;
                case 'enrolled':
                    $controller->myEnrollments();
                    break;
                case 'complete':
                    $courseId = $_GET['course'] ?? null;
                    if ($courseId) {
                        $controller->markCompleted($courseId);
                    } else {
                        throw new Exception("Course ID is required");
                    }
                    break;
                default:
                    require_once __DIR__ . '/views/home.php';
                    break;
            }
            break;

        default:
            throw new Exception("Page not found");
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: index.php');
    exit;
}