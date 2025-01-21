<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Statistics.php';

class AdminController {
    public function index() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $stats = Statistics::getGlobalStats();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function gererUtilisateurs() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        // Handle user actions
        if (isset($_GET['validate'])) {
            try {
                User::validateTeacher($_GET['validate']);
                $_SESSION['success'] = "Teacher validated successfully.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error validating teacher: " . $e->getMessage();
            }
            header('Location: index.php?action=admin&page=users');
            exit;
        }

        if (isset($_GET['activate'])) {
            try {
                User::activateUser($_GET['activate']);
                $_SESSION['success'] = "User activated successfully.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error activating user: " . $e->getMessage();
            }
            header('Location: index.php?action=admin&page=users');
            exit;
        }

        if (isset($_GET['suspend'])) {
            try {
                User::suspendUser($_GET['suspend']);
                $_SESSION['success'] = "User suspended successfully.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error suspending user: " . $e->getMessage();
            }
            header('Location: index.php?action=admin&page=users');
            exit;
        }

        if (isset($_GET['delete'])) {
            try {
                User::deleteUser($_GET['delete']);
                $_SESSION['success'] = "User deleted successfully.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting user: " . $e->getMessage();
            }
            header('Location: index.php?action=admin&page=users');
            exit;
        }

        $utilisateurs = User::getAll();
        require_once __DIR__ . '/../views/admin/users/index.php';
    }

    public function gererCategories() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                Category::create(['nom' => $_POST['nom']]);
                $_SESSION['success'] = "Category created successfully.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error creating category: " . $e->getMessage();
            }
        }

        $categories = Category::getWithCourseCount();
        require_once __DIR__ . '/../views/admin/categories/index.php';
    }

    public function supprimerCategorie() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $categoryId = $_GET['id'] ?? null;
        if ($categoryId) {
            try {
                if (Category::canDelete($categoryId)) {
                    Category::delete($categoryId);
                    $_SESSION['success'] = "Category deleted successfully.";
                } else {
                    $_SESSION['error'] = "Cannot delete category: it has associated courses.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting category: " . $e->getMessage();
            }
        }
        header('Location: index.php?action=admin&page=categories');
    }

    public function gererTags() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_POST['tags'])) {
                    // Bulk insert
                    $tags = array_map('trim', explode(',', $_POST['tags']));
                    Tag::bulkCreate($tags);
                    $_SESSION['success'] = "Tags created successfully.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error creating tags: " . $e->getMessage();
            }
        }

        $tags = Tag::getWithCourseCount();
        require_once __DIR__ . '/../views/admin/tags/index.php';
    }

    public function supprimerTag() {
        if (!$this->isAdmin()) {
            header('Location: index.php?action=login');
            exit;
        }

        $tagId = $_GET['id'] ?? null;
        if ($tagId) {
            try {
                if (Tag::canDelete($tagId)) {
                    Tag::delete($tagId);
                    $_SESSION['success'] = "Tag deleted successfully.";
                } else {
                    $_SESSION['error'] = "Cannot delete tag: it is used by courses.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting tag: " . $e->getMessage();
            }
        }
        header('Location: index.php?action=admin&page=tags');
    }

    private function isAdmin() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }
}