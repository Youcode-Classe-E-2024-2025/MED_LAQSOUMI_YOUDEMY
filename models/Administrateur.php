<?php
require_once __DIR__ . '/Utilisateur.php';

class Administrateur extends Utilisateur {
    public function __construct() {
        parent::__construct();
        $this->role = 'administrateur';
    }

    public function validerCompteEnseignant($enseignantId) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET status = 'active' WHERE id = ? AND role = 'enseignant'");
        return $stmt->execute([$enseignantId]);
    }

    public function gererUtilisateurs($userId, $action) {
        switch ($action) {
            case 'activer':
                $stmt = $this->db->prepare("UPDATE utilisateurs SET status = 'active' WHERE id = ?");
                break;
            case 'suspendre':
                $stmt = $this->db->prepare("UPDATE utilisateurs SET status = 'suspended' WHERE id = ?");
                break;
            case 'supprimer':
                $stmt = $this->db->prepare("DELETE FROM utilisateurs WHERE id = ?");
                break;
            default:
                return false;
        }
        return $stmt->execute([$userId]);
    }

    public function gererContenus($coursId, $action) {
        switch ($action) {
            case 'approuver':
                $stmt = $this->db->prepare("UPDATE cours SET status = 'approved' WHERE id = ?");
                break;
            case 'rejeter':
                $stmt = $this->db->prepare("UPDATE cours SET status = 'rejected' WHERE id = ?");
                break;
            case 'supprimer':
                try {
                    $this->db->beginTransaction();
                    
                    // Delete course tags
                    $stmtTags = $this->db->prepare("DELETE FROM cours_tags WHERE cours_id = ?");
                    $stmtTags->execute([$coursId]);

                    // Delete course inscriptions
                    $stmtInscriptions = $this->db->prepare("DELETE FROM inscriptions WHERE cours_id = ?");
                    $stmtInscriptions->execute([$coursId]);

                    // Delete course
                    $stmt = $this->db->prepare("DELETE FROM cours WHERE id = ?");
                    $result = $stmt->execute([$coursId]);

                    $this->db->commit();
                    return $result;
                } catch (PDOException $e) {
                    $this->db->rollBack();
                    error_log($e->getMessage());
                    return false;
                }
            default:
                return false;
        }
        return $stmt->execute([$coursId]);
    }

    public function insererTags($tags) {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("INSERT INTO tags (nom) VALUES (?)");
            foreach ($tags as $tag) {
                $stmt->execute([trim($tag)]);
            }
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function consulterStatistiques() {
        $stats = [];
        
        // Total courses per category
        $stats['cours_par_categorie'] = $this->db->query("
            SELECT c.nom as categorie, COUNT(co.id) as total
            FROM categories c
            LEFT JOIN cours co ON c.id = co.categorie_id
            GROUP BY c.id, c.nom
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Course with most students
        $stats['cours_plus_populaire'] = $this->db->query("
            SELECT c.titre, COUNT(i.etudiant_id) as total_etudiants
            FROM cours c
            JOIN inscriptions i ON c.id = i.cours_id
            GROUP BY c.id, c.titre
            ORDER BY total_etudiants DESC
            LIMIT 1
        ")->fetch(PDO::FETCH_ASSOC);

        // Top 3 teachers
        $stats['top_enseignants'] = $this->db->query("
            SELECT u.nom, COUNT(c.id) as total_cours,
                   (SELECT COUNT(DISTINCT i.etudiant_id) 
                    FROM inscriptions i 
                    JOIN cours c2 ON i.cours_id = c2.id 
                    WHERE c2.enseignant_id = u.id) as total_etudiants
            FROM utilisateurs u
            JOIN cours c ON u.id = c.enseignant_id
            WHERE u.role = 'enseignant'
            GROUP BY u.id, u.nom
            ORDER BY total_cours DESC, total_etudiants DESC
            LIMIT 3
        ")->fetchAll(PDO::FETCH_ASSOC);

        return $stats;
    }

    public function getAllUsers() {
        return $this->db->query("
            SELECT u.*, 
                   (SELECT COUNT(*) FROM cours WHERE enseignant_id = u.id) as total_cours,
                   (SELECT COUNT(*) FROM inscriptions WHERE etudiant_id = u.id) as total_inscriptions
            FROM utilisateurs u
            ORDER BY u.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCourses() {
        return $this->db->query("
            SELECT c.*, u.nom as enseignant_nom, cat.nom as categorie_nom,
                   (SELECT COUNT(*) FROM inscriptions WHERE cours_id = c.id) as total_inscrits
            FROM cours c
            LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
            LEFT JOIN categories cat ON c.categorie_id = cat.id
            ORDER BY c.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
