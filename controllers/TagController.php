<?php

require_once __DIR__ . '/../models/Tag.php';

class TagController {
    private $db;

    public function __construct() {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
    }

    public function index() {
        try {
            $tags = Tag::getTousLesTags($this->db);
            require_once __DIR__ . '/../views/admin/tags/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php');
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tag = new Tag($this->db);
                $tag->setNom($_POST['nom']);
                $tag->sauvegarder();
                $_SESSION['success'] = 'Tag created successfully';
                header('Location: index.php?action=tags');
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                require_once __DIR__ . '/../views/admin/tags/create.php';
            }
        } else {
            require_once __DIR__ . '/../views/admin/tags/create.php';
        }
    }

    public function bulkInsert() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $tagNames = explode(',', $_POST['tags']);
                $tagNames = array_map('trim', $tagNames);
                
                foreach ($tagNames as $tagName) {
                    if (!empty($tagName)) {
                        $tag = new Tag($this->db);
                        $tag->setNom($tagName);
                        $tag->sauvegarder();
                    }
                }
                
                $_SESSION['success'] = 'Tags inserted successfully';
                header('Location: index.php?action=tags');
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                require_once __DIR__ . '/../views/admin/tags/bulk-insert.php';
            }
        } else {
            require_once __DIR__ . '/../views/admin/tags/bulk-insert.php';
        }
    }

    public function delete($id) {
        try {
            $tag = new Tag($this->db, $id);
            $tag->supprimer();
            $_SESSION['success'] = 'Tag deleted successfully';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header('Location: index.php?action=tags');
    }
}
