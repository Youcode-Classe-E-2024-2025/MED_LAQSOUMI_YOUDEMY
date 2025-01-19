<?php
// session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/UtilisateurController.php';
// require_once __DIR__ . '/controllers/CourseController.php';
// require_once __DIR__ . '/controllers/EnseignantController.php';
// require_once __DIR__ . '/controllers/EtudiantController.php';
// require_once __DIR__ . '/controllers/AdminController.php';


$db = getDatabaseConnection();
$utilisateurController = new UtilisateurController($db);
// $courseController = new CourseController($db);
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
    case 'courses':
        require_once 'views/course.php';
        break;
    case 'login':
        require_once 'controllers/UtilisateurController.php';
        break;
    default:
        require_once 'views/404.php';
        break;
}

?>