<?php
require_once __DIR__ . '/Utilisateur.php';

class Administrateur extends Utilisateur {
    public function __construct($db = null) {
        parent::__construct($db);
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

    public function getDashboardStats() {
        $stats = [];
        
        // Total courses
        $stats['total_cours'] = $this->db->query("
            SELECT COUNT(*) FROM cours
        ")->fetchColumn();

        // Total users
        $stats['total_utilisateurs'] = $this->db->query("
            SELECT COUNT(*) FROM utilisateurs
        ")->fetchColumn();

        // Total teachers
        $stats['total_enseignants'] = $this->db->query("
            SELECT COUNT(*) FROM utilisateurs WHERE role = 'enseignant'
        ")->fetchColumn();

        // Total categories
        $stats['total_categories'] = $this->db->query("
            SELECT COUNT(*) FROM categories
        ")->fetchColumn();

        return $stats;
    }

    public function getEnseignantsEnAttente() {
        return $this->db->query("
            SELECT * FROM utilisateurs 
            WHERE role = 'enseignant' 
            AND status = 'pending'
            ORDER BY created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDernieresActivites($limit = 10) {
        return $this->db->query("
            (SELECT 
                CONCAT('Nouveau cours: ', c.titre) as description,
                c.created_at
            FROM cours c
            ORDER BY c.created_at DESC
            LIMIT 5)
            UNION ALL
            (SELECT 
                CONCAT('Nouvelle inscription: ', u.nom, ' dans ', c.titre) as description,
                i.created_at
            FROM inscriptions i
            JOIN utilisateurs u ON i.etudiant_id = u.id
            JOIN cours c ON i.cours_id = c.id
            ORDER BY i.created_at DESC
            LIMIT 5)
            ORDER BY created_at DESC
            LIMIT $limit
        ")->fetchAll(PDO::FETCH_ASSOC);
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
            SELECT 
                c.id, 
                c.titre, 
                u.nom as enseignant, 
                cat.nom as categorie, 
                c.status, 
                c.created_at,
                u.id as enseignant_id,
                cat.id as categorie_id
            FROM cours c
            LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
            LEFT JOIN categories cat ON c.categorie_id = cat.id
            ORDER BY c.created_at DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCountByMonth($month) {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE DATE_FORMAT(created_at, '%Y-%m') = :month";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['month' => $month]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getCourseCountByCategory() {
        $query = "SELECT c.nom as category, COUNT(co.id) as count 
                 FROM categories c 
                 LEFT JOIN cours co ON c.id = co.categorie_id 
                 GROUP BY c.id, c.nom";
        $stmt = $this->db->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $distribution = [];
        foreach ($results as $row) {
            $distribution[$row['category']] = (int)$row['count'];
        }
        return $distribution;
    }

    public function getUserCountByRole($role) {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE role = :role AND status = 'active'";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['role' => $role]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalUsers() {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE status = 'active'";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalCourses() {
        $query = "SELECT COUNT(*) as total FROM cours";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getPendingTeachersCount() {
        $query = "SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'enseignant' AND status = 'pending'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalTags() {
        $query = "SELECT COUNT(*) as total FROM tags";
        $stmt = $this->db->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRecentActivities($limit = 5) {
        $query = "SELECT u.nom as user_name, 'Created Course' as action, co.titre as details, co.created_at as date
                 FROM cours co
                 JOIN utilisateurs u ON co.enseignant_id = u.id
                 UNION ALL
                 SELECT u.nom as user_name, 'Joined Platform' as action, u.role as details, u.created_at as date
                 FROM utilisateurs u
                 ORDER BY date DESC
                 LIMIT :limit";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUsersList() {
        $query = "SELECT id, nom, email, role, status, created_at FROM utilisateurs ORDER BY created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCoursesList() {
        $query = "SELECT c.id, c.titre, u.nom as enseignant, cat.nom as categorie, c.status, c.created_at
                 FROM cours c
                 JOIN utilisateurs u ON c.enseignant_id = u.id
                 JOIN categories cat ON c.categorie_id = cat.id
                 ORDER BY c.created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTagsList() {
        $query = "SELECT id, nom, created_at FROM tags ORDER BY created_at DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validateTeacher($teacherId) {
        $query = "UPDATE utilisateurs SET status = 'active' WHERE id = ? AND role = 'enseignant'";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$teacherId]);
    }

    public function deleteUser($userId) {
        // Don't allow deleting if it's the last admin
        if ($this->isLastAdmin($userId)) {
            return false;
        }

        $query = "DELETE FROM utilisateurs WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId]);
    }

    private function isLastAdmin($userId) {
        $query = "SELECT COUNT(*) as count FROM utilisateurs WHERE role = 'administrateur' AND id != ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    public function approveCourse($courseId) {
        $query = "UPDATE cours SET status = 'approved' WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$courseId]);
    }

    public function deleteCourse($courseId) {
        $query = "DELETE FROM cours WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$courseId]);
    }

    public function addTag($tagName) {
        $query = "INSERT INTO tags (nom) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$tagName]);
    }

    public function deleteTag($tagId) {
        // First, delete any course_tag associations
        $query = "DELETE FROM cours_tags WHERE tag_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$tagId]);

        // Then delete the tag
        $query = "DELETE FROM tags WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$tagId]);
    }
}