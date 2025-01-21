<?php

require_once __DIR__ . '/../config/database.php';

class Category {
    private $id;
    private $nom;
    private $db;

    public function __construct() {
        $db = DatabaseConnection::getInstance();
        $this->db = $db->getConnection();
    }

    public function getCategories() {
        $query = "SELECT * FROM categories ORDER BY nom";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ajouterCategorie($nom) {
        $query = "INSERT INTO categories (nom) VALUES (:nom)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function modifierCategorie($id, $nom) {
        $query = "UPDATE categories SET nom = :nom WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function supprimerCategorie($id) {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
