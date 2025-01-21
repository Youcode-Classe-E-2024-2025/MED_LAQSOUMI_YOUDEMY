<?php

require_once __DIR__ . '/../config/database.php';

class Inscription {
    private $etudiantId;
    private $coursId;
    private $dateInscription;
    private $db;

    public function __construct() {
        $db = DatabaseConnection::getInstance();
        $this->db = $db->getConnection();
    }

    public function getInscriptionsByStudent($etudiantId) {
        $query = "SELECT i.*, c.titre as cours_titre, u.nom as etudiant_nom
                  FROM inscriptions i
                  JOIN cours c ON i.cours_id = c.id
                  JOIN utilisateurs u ON i.etudiant_id = u.id
                  WHERE i.etudiant_id = :etudiant_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInscriptionsByCourse($coursId) {
        $query = "SELECT i.*, c.titre as cours_titre, u.nom as etudiant_nom
                  FROM inscriptions i
                  JOIN cours c ON i.cours_id = c.id
                  JOIN utilisateurs u ON i.etudiant_id = u.id
                  WHERE i.cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':cours_id', $coursId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inscrire($etudiantId, $coursId) {
        $query = "INSERT INTO inscriptions (etudiant_id, cours_id) VALUES (:etudiant_id, :cours_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->bindParam(':cours_id', $coursId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function desinscrire($etudiantId, $coursId) {
        $query = "DELETE FROM inscriptions WHERE etudiant_id = :etudiant_id AND cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->bindParam(':cours_id', $coursId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function estInscrit($etudiantId, $coursId) {
        $query = "SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = :etudiant_id AND cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':etudiant_id', $etudiantId, PDO::PARAM_INT);
        $stmt->bindParam(':cours_id', $coursId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
