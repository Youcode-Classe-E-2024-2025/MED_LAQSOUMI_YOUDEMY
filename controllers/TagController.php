<?php
require_once __DIR__ . '/../models/Tag.php';

class TagController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTags() {
        try {
            $tagModel = new Tag($this->db);
            $tags = $tagModel->getAllTags();
            echo json_encode(['success' => true, 'tags' => $tags]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function addTag() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            if (!$nom) {
                throw new Exception('Tag name is required');
            }

            $tagModel = new Tag($this->db);
            $tagId = $tagModel->addTag($nom);

            echo json_encode(['success' => true, 'tag_id' => $tagId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateTag() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
            
            if (!$id || !$nom) {
                throw new Exception('Invalid tag data');
            }

            $tagModel = new Tag($this->db);
            $tagModel->updateTag($id, $nom);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteTag() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
            if (!$id) {
                throw new Exception('Invalid tag ID');
            }

            $tagModel = new Tag($this->db);
            $tagModel->deleteTag($id);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
