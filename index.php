<?php
session_start();
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/TeacherController.php';
require_once __DIR__ . '/controllers/EtudiantController.php';
require_once __DIR__ . '/controllers/CourseController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'home';
$page = isset($_GET['page']) ? $_GET['page'] : null;

// Initialize controllers
$authController = new AuthController();
$courseController = new CourseController();

switch ($action) {
    // Auth routes
    case 'login':
        $authController->login();
        break;
    case 'register':
        $authController->register();
        break;
    case 'logout':
        $authController->logout();
        break;

    // Admin routes
    case 'admin':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error'] = "Access denied. Admin privileges required.";
            header('Location: index.php?action=login');
            exit;
        }
        $adminController = new AdminController();
        switch ($page) {
            case 'users':
                $adminController->gererUtilisateurs();
                break;
            case 'categories':
                $adminController->gererCategories();
                break;
            case 'tags':
                $adminController->gererTags();
                break;
            default:
                $adminController->index();
                break;
        }
        break;

    // Teacher routes
    case 'enseignant':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant') {
            $_SESSION['error'] = "Access denied. Teacher privileges required.";
            header('Location: index.php?action=login');
            exit;
        }
        $teacherController = new TeacherController();
        switch ($page) {
            case 'courses':
                $teacherController->gererCours();
                break;
            case 'add-course':
                $teacherController->ajouterCours();
                break;
            case 'edit-course':
                $teacherController->modifierCours();
                break;
            default:
                $teacherController->index();
                break;
        }
        break;

    // Student routes
    case 'etudiant':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            $_SESSION['error'] = "Access denied. Student privileges required.";
            header('Location: index.php?action=login');
            exit;
        }
        $etudiantController = new EtudiantController();
        switch ($page) {
            case 'courses':
                $courseController->getAll();
                break;
            case 'course':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $courseController->view($id);
                break;
            case 'enroll':
                $courseId = isset($_GET['course']) ? (int)$_GET['course'] : 0;
                $courseController->enroll($courseId);
                break;
            case 'enrolled':
                $courseController->myEnrollments();
                break;
            case 'complete':
                $courseId = isset($_GET['course']) ? (int)$_GET['course'] : 0;
                $courseController->markCompleted($courseId);
                break;
            default:
                $etudiantController->index();
                break;
        }
        break;

    // Public routes
    case 'courses':
        $courseController->getAll();
        break;
    case 'course':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $courseController->view($id);
        break;
    case 'home':
    default:
        require_once __DIR__ . '/views/home.php';
        break;
}