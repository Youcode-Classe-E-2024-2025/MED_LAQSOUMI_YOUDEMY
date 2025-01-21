<?php

require_once __DIR__ . '/../config/database.php';

class Categorie {
    private $db;
    private $id;
    private $nom;

    public function __construct($db, $id = null, $nom = null) {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->id = $id;
        $this->nom = $nom;

        if ($id && !$nom) {
            $this->chargerCategorie();
        }
    }

    private function chargerCategorie() {
        try {
            $query = "SELECT * FROM categories WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $this->nom = $data['nom'];
            }
        } catch (PDOException $e) {
            error_log('Error loading category: ' . $e->getMessage());
            throw new Exception('Failed to load category');
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
}