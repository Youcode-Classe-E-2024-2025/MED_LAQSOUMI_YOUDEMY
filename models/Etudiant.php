<?php
require_once __DIR__ . '/Utilisateur.php';

class Etudiant extends Utilisateur {
    public function __construct() {
        parent::__construct();
        $this->role = 'etudiant';
    }

    public function consulterCours() {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom, i.status as enrollment_status
            FROM inscriptions i
            JOIN cours c ON i.cours_id = c.id
            LEFT JOIN categories cat ON c.categorie_id = cat.id
            LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
            WHERE i.etudiant_id = ?
            ORDER BY i.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function sInscrireCours($coursId) {
        // Check if already enrolled
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = ? AND cours_id = ?");
        $stmt->execute([$_SESSION['user_id'], $coursId]);
        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO inscriptions (etudiant_id, cours_id) VALUES (?, ?)");
        return $stmt->execute([$_SESSION['user_id'], $coursId]);
    }

    public function getMesCours() {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom 
            FROM cours c
            JOIN inscriptions i ON c.id = i.cours_id 
            LEFT JOIN categories cat ON c.categorie_id = cat.id
            LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
            WHERE i.etudiant_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalMesCours() {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchColumn();
    }
}
