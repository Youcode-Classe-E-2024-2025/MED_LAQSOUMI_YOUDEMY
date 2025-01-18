<?php
// index.php
require_once 'config/database.php';
require_once 'controllers/UtilisateurController.php';
require_once 'controllers/CoursController.php';
require_once 'controllers/AdminController.php';

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/':
        // Show home page
        break;
    case '/login':
        $controller = new UtilisateurController();
        $controller->login();
        break;
    case '/register':
        $controller = new UtilisateurController();
        $controller->register();
        break;
    case '/courses':
        $controller = new CoursController();
        $controller->index();
        break;
    // Add more routes as needed
    default:
        http_response_code(404);
        require 'views/404.php';
        break;
}