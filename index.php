<?php
session_start();

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/UtilisateurController.php';
require_once __DIR__ . '/controllers/CourseController.php';
require_once __DIR__ . '/controllers/AdminController.php';
require_once __DIR__ . '/controllers/EnseignantController.php';
require_once __DIR__ . '/controllers/EtudiantController.php';
require_once __DIR__ . '/controllers/CategoryController.php';
require_once __DIR__ . '/controllers/TagController.php';

$db = DatabaseConnection::getInstance()->getConnection();

$user = new UtilisateurController($db);
$courseController = new CourseController($db);
$adminController = new AdminController($db);
$enseignantController = new EnseignantController($db);
$etudiantController = new EtudiantController($db);
$categoryController = new CategoryController($db);
$tagController = new TagController($db);

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        require_once 'views/index.php';
        break;
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
    case 'teacherDashboard':
        $enseignantController->teacherDashboard();
        break;
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
    case 'course/add':
        $courseController->addCourse();
        break;
    case 'course/update':
        $courseController->updateCourse();
        break;
    case 'course/delete':
        $courseController->deleteCourse();
        break;
    case 'course/enrollments':
        $courseController->getEnrollments();
        break;
    case 'teacher/statistics':
        $courseController->getTeacherStatistics();
        break;
    case 'course/details':
        $courseController->getCourseById($id);
        break;

    // Admin routes
    case 'adminDashboard':
        $adminController->adminDashboard();
        break;
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
    case 'validateTeacher':
        $adminController->validateTeacher();
        break;
    case 'deleteUser':
        $adminController->deleteUser();
        break;
    case 'approveCourse':
        $adminController->approveCourse();
        break;
    case 'deleteCourse':
        $adminController->deleteCourse();
        break;
    case 'addTag':
        $adminController->addTag();
        break;
    case 'deleteTag':
        $adminController->deleteTag();
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

    // Category and tag routes
    case 'categories':
        $categoryController->getCategories();
        break;
    case 'tags':
        $tagController->getTags();
        break;

    default:
        require_once 'views/404.php';
        break;
}
?>