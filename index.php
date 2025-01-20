<?php
// session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/UtilisateurController.php';
require_once __DIR__ . '/controllers/CourseController.php';
// require_once __DIR__ . '/controllers/EnseignantController.php';
// require_once __DIR__ . '/controllers/EtudiantController.php';
// require_once __DIR__ . '/controllers/AdminController.php';


$db = DatabaseConnection::getInstance();
$user = new UtilisateurController($db);
$courseController = new CourseController($db);
// $enseignantController = new EnseignantController($db);
// $etudiantController = new EtudiantController($db);
// $adminController = new AdminController($db);




$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        require_once  'views/index.php';
        break;
    case 'loginPage':
        require_once 'views/login.php';
        break;
    case 'registerPage':
        require_once 'views/register.php';
        break;
    case 'profile':
        require_once 'views/profile.php';
        break;
    case 'myCourses':
        require_once 'views/myCourses.php';
        break;
    case 'teacherDashboard':
        require_once 'views/teacher_Dashboard.php';
        break;
    case 'adminDashboard':
        require_once 'views/admin_Dashboard.php';
        break;
    case 'courses':
        $courseController->getAll();
        break;
    case 'search':
        $courseController->handleSearch();
        break;
    case 'login':
        $user->login();
        break;
    case 'logout':
        $user->logout();
        break;
    case 'register':
        $user->register();
        break;
    
    default:
        require_once 'views/404.php';
        break;
}

?>