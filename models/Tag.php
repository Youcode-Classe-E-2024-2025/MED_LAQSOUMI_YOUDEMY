<?php

require_once __DIR__ . '/../config/database.php';

class Tag {
    private $id;
    private $nom;
    private $db;

    public function __construct($db) {
        if ($db instanceof DatabaseConnection) {
            $this->db = $db->getConnection();
        } else {
            $this->db = $db;
        }
    }

    public function getAllTags() {
        try {
            $query = "SELECT * FROM tags ORDER BY nom";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Failed to get tags');
        }
    }

    public function getTagById($id) {
        try {
            $query = "SELECT * FROM tags WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Failed to get tag');
        }
    }

    public function addTag($nom) {
        try {
            $query = "INSERT INTO tags (nom) VALUES (:nom)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception('Failed to add tag');
            }
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception('Failed to add tag');
        }
    }

    public function updateTag($id, $nom) {
        try {
            $query = "UPDATE tags SET nom = :nom WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            if (!$stmt->execute()) {
                throw new Exception('Failed to update tag');
            }
            return true;
        } catch (PDOException $e) {
            throw new Exception('Failed to update tag');
        }
    }

    public function deleteTag($id) {
        try {
            $query = "DELETE FROM tags WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new Exception('Failed to delete tag');
            }
            return true;
        } catch (PDOException $e) {
            throw new Exception('Failed to delete tag');
        }
    }

    public function getTagsForCourse($courseId) {
        try {
            $query = "SELECT t.* 
                     FROM tags t
                     JOIN cours_tags ct ON t.id = ct.tag_id
                     WHERE ct.cours_id = :cours_id
                     ORDER BY t.nom";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('Failed to get course tags');
        }
    }
}
