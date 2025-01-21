<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Etudiant.php';
require_once __DIR__ . '/Cours.php';

class Inscription {
    private $db;
    private $etudiantId;
    private $coursId;
    private $dateInscription;

    public function __construct($db, $etudiantId = null, $coursId = null) {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->etudiantId = $etudiantId;
        $this->coursId = $coursId;
        $this->dateInscription = date('Y-m-d H:i:s');
    }

    public function sauvegarder() {
        try {
            // Check if inscription already exists
            $query = "SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = ? AND cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->etudiantId, $this->coursId]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception('Student is already enrolled in this course');
            }

            // Create new inscription
            $query = "INSERT INTO inscriptions (etudiant_id, cours_id, date_inscription) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$this->etudiantId, $this->coursId, $this->dateInscription]);
        } catch (PDOException $e) {
            error_log('Error saving inscription: ' . $e->getMessage());
            throw new Exception('Failed to save inscription');
        }
    }

    public function supprimer() {
        try {
            $query = "DELETE FROM inscriptions WHERE etudiant_id = ? AND cours_id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$this->etudiantId, $this->coursId]);
        } catch (PDOException $e) {
            error_log('Error deleting inscription: ' . $e->getMessage());
            throw new Exception('Failed to delete inscription');
        }
    }

    // Getters
    public function getEtudiantId() { return $this->etudiantId; }
    public function getCoursId() { return $this->coursId; }
    public function getDateInscription() { return $this->dateInscription; }

    // Setters
    public function setEtudiantId($etudiantId) { $this->etudiantId = $etudiantId; }
    public function setCoursId($coursId) { $this->coursId = $coursId; }
    public function setDateInscription($dateInscription) { $this->dateInscription = $dateInscription; }
}