<?php
require_once __DIR__ . '/Utilisateur.php';

class Enseignant extends Utilisateur {
    public function __construct($db) {
        parent::__construct($db);
        $this->role = 'enseignant';
    }

    public function ajouterCours($titre, $description, $contenu, $tags, $categorieId) {
        try {
            $this->db->beginTransaction();

            // Insert course
            $stmt = $this->db->prepare("
                INSERT INTO cours (titre, description, contenu, categorie_id, enseignant_id, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$titre, $description, $contenu, $categorieId, $_SESSION['user_id']]);
            $coursId = $this->db->lastInsertId();

            // Add tags
            if (!empty($tags)) {
                $stmtTag = $this->db->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)");
                foreach ($tags as $tagId) {
                    $stmtTag->execute([$coursId, $tagId]);
                }
            }

            $this->db->commit();
            return $coursId;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function modifierCours($coursId, $titre, $description, $contenu, $tags, $categorieId) {
        try {
            $this->db->beginTransaction();

            // Update course
            $stmt = $this->db->prepare("
                UPDATE cours 
                SET titre = ?, description = ?, contenu = ?, categorie_id = ?
                WHERE id = ? AND enseignant_id = ?
            ");
            $stmt->execute([$titre, $description, $contenu, $categorieId, $coursId, $_SESSION['user_id']]);

            // Remove old tags
            $stmtDelete = $this->db->prepare("DELETE FROM cours_tags WHERE cours_id = ?");
            $stmtDelete->execute([$coursId]);

            // Add new tags
            if (!empty($tags)) {
                $stmtTag = $this->db->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)");
                foreach ($tags as $tagId) {
                    $stmtTag->execute([$coursId, $tagId]);
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function supprimerCours($coursId) {
        try {
            $this->db->beginTransaction();

            // Delete course tags
            $stmtTags = $this->db->prepare("DELETE FROM cours_tags WHERE cours_id = ?");
            $stmtTags->execute([$coursId]);

            // Delete course inscriptions
            $stmtInscriptions = $this->db->prepare("DELETE FROM inscriptions WHERE cours_id = ?");
            $stmtInscriptions->execute([$coursId]);

            // Delete course
            $stmtCours = $this->db->prepare("DELETE FROM cours WHERE id = ? AND enseignant_id = ?");
            $result = $stmtCours->execute([$coursId, $_SESSION['user_id']]);

            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    public function consulterInscriptions($coursId) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.nom, u.email, i.date_inscription as dateInscription
            FROM utilisateurs u
            JOIN inscriptions i ON u.id = i.etudiant_id
            JOIN cours c ON i.cours_id = c.id
            WHERE c.id = ? AND c.enseignant_id = ?
            ORDER BY i.date_inscription DESC
        ");
        $stmt->execute([$coursId, $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMesCours() {
        $stmt = $this->db->prepare("
            SELECT c.*, cat.nom as categorie_nom,
                   (SELECT COUNT(*) FROM inscriptions i WHERE i.cours_id = c.id) as total_inscrits
            FROM cours c
            LEFT JOIN categories cat ON c.categorie_id = cat.id
            WHERE c.enseignant_id = ?
            ORDER BY c.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTeacherCourses($teacherId) {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, cat.nom as category_name 
                FROM cours c 
                LEFT JOIN categories cat ON c.categorie_id = cat.id 
                WHERE c.enseignant_id = ?
                ORDER BY c.date_creation DESC
            ");
            $stmt->execute([$teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getTeacherEnrollments($teacherId) {
        try {
            $query = "
                SELECT 
                    i.*,
                    c.titre as cours_titre,
                    u.nom as etudiant_nom,
                    u.email as etudiant_email,
                    u.id as etudiant_id,
                    c.id as cours_id
                FROM inscriptions i
                JOIN cours c ON i.cours_id = c.id
                JOIN utilisateurs u ON i.etudiant_id = u.id
                WHERE c.enseignant_id = ?
                ORDER BY i.date_inscription DESC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getTotalStudents($teacherId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT i.etudiant_id) as total
                FROM inscriptions i
                JOIN cours c ON i.cours_id = c.id
                WHERE c.enseignant_id = ?
            ");
            $stmt->execute([$teacherId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }

    public function getTotalRevenue($teacherId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COALESCE(SUM(i.montant), 0) as total
                FROM inscriptions i
                JOIN cours c ON i.cours_id = c.id
                WHERE c.enseignant_id = ?
            ");
            $stmt->execute([$teacherId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return 0;
        }
    }
}
