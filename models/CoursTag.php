<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Cours.php';
require_once __DIR__ . '/Tag.php';

class CoursTag {
    private $db;
    private $coursId;
    private $tagId;

    public function __construct($db, $coursId = null, $tagId = null) {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->coursId = $coursId;
        $this->tagId = $tagId;
    }

    public function sauvegarder() {
        try {
            $query = "INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$this->coursId, $this->tagId]);
        } catch (PDOException $e) {
            error_log('Error saving course tag: ' . $e->getMessage());
            throw new Exception('Failed to save course tag');
        }
    }

    public function supprimer() {
        try {
            $query = "DELETE FROM cours_tags WHERE cours_id = ? AND tag_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$this->coursId, $this->tagId]);
        } catch (PDOException $e) {
            error_log('Error deleting course tag: ' . $e->getMessage());
            throw new Exception('Failed to delete course tag');
        }
    }

    // Getters
    public function getCoursId() { return $this->coursId; }
    public function getTagId() { return $this->tagId; }

    // Setters
    public function setCoursId($coursId) { $this->coursId = $coursId; }
    public function setTagId($tagId) { $this->tagId = $tagId; }
}
