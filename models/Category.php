<?php

require_once __DIR__ . '/../config/database.php';

class Category {
    private $db;

    public function __construct($db) {
        if ($db instanceof DatabaseConnection) {
            $this->db = $db->getConnection();
        } else {
            $this->db = $db;
        }
    }

    public function getAllCategories() {
        try {
            $query = "SELECT * FROM categories ORDER BY nom ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
}
