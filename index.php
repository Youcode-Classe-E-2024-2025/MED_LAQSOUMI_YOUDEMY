<?php
session_start();

require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/EtudiantController.php';
require_once __DIR__ . '/controllers/EnseignantController.php';
require_once __DIR__ . '/controllers/CourseController.php';

$homeController = new HomeController();
$authController = new AuthController();
$adminController = new AdminController();
$etudiantController = new EtudiantController();
$enseignantController = new EnseignantController();
$courseController = new CourseController();

$action = $_GET['action'] ?? 'home';
$page = $_GET['page'] ?? 'index';

switch ($action) {
    case 'home':
        if ($page === 'course' && isset($_GET['id'])) {
            $homeController->viewCourse($_GET['id']);
        } else {
            $homeController->index();
        }
        break;

    case 'register':
        $homeController->register();
        break;

    case 'login':
        $authController->login();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'admin':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: index.php?action=login');
            exit;
        }

        switch ($page) {
            case 'categories':
                $adminController->gererCategories();
                break;
            case 'tags':
                $adminController->gererTags();
                break;
            case 'courses':
                $adminController->gererCours();
                break;
            default:
                $adminController->index();
                break;
        }
        break;

    case 'etudiant':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: index.php?action=login');
            exit;
        }

        switch ($page) {
            case 'courses':
                $etudiantController->courses();
                break;
            case 'course':
                if (isset($_GET['id'])) {
                    $etudiantController->viewCourse($_GET['id']);
                } else {
                    header('Location: index.php?action=etudiant&page=courses');
                }
                break;
            case 'enroll':
                $etudiantController->enroll();
                break;
            case 'my-courses':
                $etudiantController->myCourses();
                break;
            default:
                $etudiantController->index();
                break;
        }
        break;

    case 'enseignant':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant') {
            header('Location: index.php?action=login');
            exit;
        }

        switch ($page) {
            case 'courses':
                $enseignantController->courses();
                break;
            case 'course':
                if (isset($_GET['id'])) {
                    $enseignantController->editCourse($_GET['id']);
                } else {
                    $enseignantController->createCourse();
                }
                break;
            default:
                $enseignantController->index();
                break;
        }
        break;

    case 'visitor':
        switch ($page) {
            case 'courses':
                $courseController->getAll();
                break;
            case 'course':
                $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
                $courseController->view($id);
                break;
            default:
                require_once __DIR__ . '/views/home.php';
                break;
        }
        break;

    case 'student':
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: index.php?action=login');
            exit;
        }
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
                require_once __DIR__ . '/views/home.php';
                break;
        }
        break;

    default:
        header('Location: index.php');
        break;
}