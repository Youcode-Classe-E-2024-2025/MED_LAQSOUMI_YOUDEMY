<?php
require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getCategories() {
        try {
            $categoryModel = new Category($this->db);
            $categories = $categoryModel->getAllCategories();
            echo json_encode(['success' => true, 'categories' => $categories]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function addCategory() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            if (!$nom) {
                throw new Exception('Category name is required');
            }

            $categoryModel = new Category($this->db);
            $categoryId = $categoryModel->addCategory($nom);

            echo json_encode(['success' => true, 'category_id' => $categoryId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateCategory() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            
            if (!$id || !$nom) {
                throw new Exception('Invalid category data');
            }

            $categoryModel = new Category($this->db);
            $categoryModel->updateCategory($id, $nom);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteCategory() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                throw new Exception('Invalid category ID');
            }

            $categoryModel = new Category($this->db);
            $categoryModel->deleteCategory($id);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
