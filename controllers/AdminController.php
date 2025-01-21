<?php

require_once __DIR__ . '/../models/Administrateur.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Statistics.php';

class AdminController {
    private function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    public function index() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $page = 'dashboard';
        $stats = Statistics::getDashboardStats();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function users() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $page = 'users';
        $users = Administrateur::getAllUsers();
        require_once __DIR__ . '/../views/admin/users/index.php';
    }

    public function deleteUser() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if (isset($_POST['id'])) {
            if (Administrateur::deleteUser($_POST['id'])) {
                $_SESSION['success'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete user.";
            }
        }

        header('Location: index.php?action=admin&page=users');
        exit;
    }

    public function categories() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $page = 'categories';
        $categories = Category::getAll();
        require_once __DIR__ . '/../views/admin/categories/index.php';
    }

    public function addCategory() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nom'])) {
            if (Category::create(['nom' => $_POST['nom']])) {
                $_SESSION['success'] = "Category added successfully.";
            } else {
                $_SESSION['error'] = "Failed to add category.";
            }
        }

        header('Location: index.php?action=admin&page=categories');
        exit;
    }

    public function deleteCategory() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if (isset($_POST['id'])) {
            if (Category::delete($_POST['id'])) {
                $_SESSION['success'] = "Category deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete category.";
            }
        }

        header('Location: index.php?action=admin&page=categories');
        exit;
    }

    public function editCategory() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && !empty($_POST['nom'])) {
            if (Category::update($_POST['id'], ['nom' => $_POST['nom']])) {
                $_SESSION['success'] = "Category updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update category.";
            }
        }

        header('Location: index.php?action=admin&page=categories');
        exit;
    }
}