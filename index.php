<?php
// index.php
// require_once 'config/database.php';
// require_once 'controllers/UtilisateurController.php';
// require_once 'controllers/CoursController.php';
// require_once 'controllers/AdminController.php';
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        require_once  'views/index.php';
        break;
    case 'login':
        require_once 'views/login.php';
        break;
    case 'register':
        require_once 'views/register.php';
        break;
    default:
        require_once 'views/404.php';
        break;
}

?>