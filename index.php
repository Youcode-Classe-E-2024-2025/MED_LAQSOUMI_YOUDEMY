<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/UtilisateurController.php';
require_once __DIR__ . '/controllers/CourseController.php';
require_once __DIR__ . '/controllers/EtudiantController.php';
require_once __DIR__ . '/controllers/EnseignantController.php';
require_once __DIR__ . '/controllers/AdminController.php';

$db = DatabaseConnection::getInstance();
$user = new UtilisateurController($db);
$courseController = new CourseController($db);
$etudiantController = new EtudiantController($db);
$enseignantController = new EnseignantController($db);
$adminController = new AdminController($db);

$action = isset($_GET['action']) ? $_GET['action'] : 'home';

switch ($action) {
    case 'home':
        require_once 'views/index.php';
        break;

    // Authentication routes
    case 'loginPage':
        require_once 'views/login.php';
        break;
    case 'registerPage':
        require_once 'views/register.php';
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

    // Student routes
    case 'consulterCours':
        $etudiantController->consulterCours();
        break;
    case 'sInscrireCours':
        $etudiantController->sInscrireCours();
        break;
    case 'myCourses':
        $etudiantController->getMesCours();
        break;

    // Teacher routes
    case 'ajouterCours':
        $enseignantController->ajouterCours();
        break;
    case 'modifierCours':
        $enseignantController->modifierCours();
        break;
    case 'supprimerCours':
        $enseignantController->supprimerCours();
        break;
    case 'consulterInscriptions':
        $enseignantController->consulterInscriptions();
        break;

    // Admin routes
    case 'validerEnseignant':
        $adminController->validerCompteEnseignant();
        break;
    case 'gererUtilisateurs':
        $adminController->gererUtilisateurs();
        break;
    case 'gererContenus':
        $adminController->gererContenus();
        break;
    case 'insererTags':
        $adminController->insererTags();
        break;
    case 'statistiques':
        $adminController->consulterStatistiques();
        break;

    // General course routes
    case 'courses':
        $courseController->getAll();
        break;
    case 'search':
        $courseController->handleSearch();
        break;
    case 'inscrireCours':
        $courseController->inscrireCours();
        break;

    default:
        require_once 'views/404.php';
        break;
}
?>