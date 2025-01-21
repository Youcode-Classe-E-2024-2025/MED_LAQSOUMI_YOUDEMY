<?php

require_once __DIR__ . '/Utilisateur.php';
require_once __DIR__ . '/Cours.php';

class Etudiant extends Utilisateur {
    public function __construct($db) {
        parent::__construct($db);
        $this->role = 'etudiant';
    }

    public function consulterCours() {
        try {
            $query = "SELECT c.*, u.nom as enseignant_nom, cat.nom as categorie_nom 
                     FROM cours c
                     JOIN utilisateurs u ON c.enseignant_id = u.id
                     JOIN categories cat ON c.categorie_id = cat.id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error consulting courses: ' . $e->getMessage());
            throw new Exception('Failed to retrieve courses');
        }
    }

    public function sInscrireCours(Cours $cours) {
        try {
            // Check if already enrolled
            $query = "SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = ? AND cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->getId(), $cours->getId()]);
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception('Already enrolled in this course');
            }

            // Enroll in course
            $query = "INSERT INTO inscriptions (etudiant_id, cours_id, date_inscription) VALUES (?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->getId(), $cours->getId()]);
        } catch (PDOException $e) {
            error_log('Error enrolling in course: ' . $e->getMessage());
            throw new Exception('Failed to enroll in course');
        }
    }

    public function getMesCours() {
        try {
            $query = "SELECT c.*, u.nom as enseignant_nom, cat.nom as categorie_nom, i.date_inscription
                     FROM cours c
                     JOIN inscriptions i ON c.id = i.cours_id
                     JOIN utilisateurs u ON c.enseignant_id = u.id
                     JOIN categories cat ON c.categorie_id = cat.id
                     WHERE i.etudiant_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->getId()]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error retrieving enrolled courses: ' . $e->getMessage());
            throw new Exception('Failed to retrieve enrolled courses');
        }
    }
}