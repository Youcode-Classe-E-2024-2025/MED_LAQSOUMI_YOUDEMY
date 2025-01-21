<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Cours.php';
require_once __DIR__ . '/Utilisateur.php';

class Statistiques {
    private $db;

    public function __construct() {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
    }

    public function calculerStatistiquesCours($cours = null) {
        try {
            $stats = [];
            
            if ($cours) {
                // Statistics for specific course
                $query = "SELECT 
                            COUNT(i.etudiant_id) as total_enrollments,
                            AVG(CASE WHEN i.completed = 1 THEN 1 ELSE 0 END) * 100 as completion_rate,
                            COUNT(DISTINCT i.etudiant_id) as unique_students
                         FROM inscriptions i
                         WHERE i.cours_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$cours->getId()]);
                $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // Global course statistics
                $query = "SELECT 
                            COUNT(*) as total_courses,
                            COUNT(DISTINCT categorie_id) as total_categories,
                            AVG(
                                SELECT COUNT(*) 
                                FROM inscriptions i 
                                WHERE i.cours_id = c.id
                            ) as avg_enrollments_per_course
                         FROM cours c";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            }
            
            return $stats;
        } catch (PDOException $e) {
            error_log('Error calculating course statistics: ' . $e->getMessage());
            throw new Exception('Failed to calculate course statistics');
        }
    }

    public function calculerStatistiquesUtilisateur($utilisateur = null) {
        try {
            $stats = [];
            
            if ($utilisateur) {
                // Statistics for specific user
                if ($utilisateur->getRole() === 'etudiant') {
                    $query = "SELECT 
                                COUNT(i.cours_id) as enrolled_courses,
                                COUNT(CASE WHEN i.completed = 1 THEN 1 END) as completed_courses,
                                AVG(CASE WHEN i.completed = 1 THEN 1 ELSE 0 END) * 100 as completion_rate
                             FROM inscriptions i
                             WHERE i.etudiant_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$utilisateur->getId()]);
                    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
                } else if ($utilisateur->getRole() === 'enseignant') {
                    $query = "SELECT 
                                COUNT(c.id) as total_courses,
                                COUNT(DISTINCT i.etudiant_id) as total_students,
                                AVG(
                                    SELECT COUNT(*) 
                                    FROM inscriptions i2 
                                    WHERE i2.cours_id = c.id
                                ) as avg_students_per_course
                             FROM cours c
                             LEFT JOIN inscriptions i ON c.id = i.cours_id
                             WHERE c.enseignant_id = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$utilisateur->getId()]);
                    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
                }
            } else {
                // Global user statistics
                $query = "SELECT 
                            role,
                            COUNT(*) as total,
                            COUNT(CASE WHEN status = 'active' THEN 1 END) as active
                         FROM utilisateurs
                         GROUP BY role";
                $stmt = $this->db->prepare($query);
                $stmt->execute();
                $stats['users_by_role'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $stats;
        } catch (PDOException $e) {
            error_log('Error calculating user statistics: ' . $e->getMessage());
            throw new Exception('Failed to calculate user statistics');
        }
    }

    public function calculerStatistiquesGlobales() {
        try {
            $stats = [];
            
            // Total number of courses per category
            $query = "SELECT c.categorie_id, cat.nom, COUNT(*) as count
                     FROM cours c
                     JOIN categories cat ON c.categorie_id = cat.id
                     GROUP BY c.categorie_id, cat.nom
                     ORDER BY count DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['courses_per_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Course with most students
            $query = "SELECT c.id, c.titre, COUNT(i.etudiant_id) as student_count
                     FROM cours c
                     JOIN inscriptions i ON c.id = i.cours_id
                     GROUP BY c.id, c.titre
                     ORDER BY student_count DESC
                     LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['most_popular_course'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Top 3 teachers by number of students
            $query = "SELECT u.id, u.nom, COUNT(DISTINCT i.etudiant_id) as student_count
                     FROM utilisateurs u
                     JOIN cours c ON u.id = c.enseignant_id
                     JOIN inscriptions i ON c.id = i.cours_id
                     WHERE u.role = 'enseignant'
                     GROUP BY u.id, u.nom
                     ORDER BY student_count DESC
                     LIMIT 3";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['top_teachers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // General statistics
            $query = "SELECT 
                        (SELECT COUNT(*) FROM cours) as total_courses,
                        (SELECT COUNT(*) FROM utilisateurs WHERE role = 'etudiant') as total_students,
                        (SELECT COUNT(*) FROM utilisateurs WHERE role = 'enseignant') as total_teachers,
                        (SELECT COUNT(*) FROM inscriptions) as total_enrollments";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['general'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Platform growth
            $query = "SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        COUNT(DISTINCT CASE WHEN role = 'etudiant' THEN id END) as new_students,
                        COUNT(DISTINCT CASE WHEN role = 'enseignant' THEN id END) as new_teachers,
                        COUNT(DISTINCT i.id) as new_enrollments
                     FROM utilisateurs u
                     LEFT JOIN inscriptions i ON u.id = i.etudiant_id
                     GROUP BY month
                     ORDER BY month DESC
                     LIMIT 12";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['growth'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Most popular categories
            $query = "SELECT 
                        cat.nom as category,
                        COUNT(i.id) as total_enrollments
                     FROM categories cat
                     JOIN cours c ON cat.id = c.categorie_id
                     JOIN inscriptions i ON c.id = i.cours_id
                     GROUP BY cat.id
                     ORDER BY total_enrollments DESC
                     LIMIT 5";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $stats['popular_categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $stats;
        } catch (PDOException $e) {
            error_log('Error calculating global statistics: ' . $e->getMessage());
            throw new Exception('Failed to calculate global statistics');
        }
    }

    public function calculerStatistiquesUtilisateurDetail($utilisateur) {
        try {
            $stats = [];

            if ($utilisateur->getRole() === 'enseignant') {
                // Teacher statistics
                $query = "SELECT 
                            COUNT(DISTINCT c.id) as total_courses,
                            COUNT(DISTINCT i.etudiant_id) as total_students,
                            AVG(CASE WHEN i.completed = 1 THEN 1 ELSE 0 END) * 100 as avg_completion_rate
                         FROM cours c
                         LEFT JOIN inscriptions i ON c.id = i.cours_id
                         WHERE c.enseignant_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$utilisateur->getId()]);
                $stats['teaching'] = $stmt->fetch(PDO::FETCH_ASSOC);

                // Most popular course
                $query = "SELECT c.titre, COUNT(i.etudiant_id) as enrollment_count
                         FROM cours c
                         LEFT JOIN inscriptions i ON c.id = i.cours_id
                         WHERE c.enseignant_id = ?
                         GROUP BY c.id, c.titre
                         ORDER BY enrollment_count DESC
                         LIMIT 1";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$utilisateur->getId()]);
                $stats['most_popular_course'] = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                // Student statistics
                $query = "SELECT 
                            COUNT(DISTINCT i.cours_id) as enrolled_courses,
                            COUNT(CASE WHEN i.completed = 1 THEN 1 END) as completed_courses,
                            (COUNT(CASE WHEN i.completed = 1 THEN 1 END) * 100.0 / COUNT(*)) as completion_rate
                         FROM inscriptions i
                         WHERE i.etudiant_id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$utilisateur->getId()]);
                $stats['learning'] = $stmt->fetch(PDO::FETCH_ASSOC);

                // Recent activity
                $query = "SELECT c.titre, i.date_inscription, i.completed
                         FROM inscriptions i
                         JOIN cours c ON i.cours_id = c.id
                         WHERE i.etudiant_id = ?
                         ORDER BY i.date_inscription DESC
                         LIMIT 5";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$utilisateur->getId()]);
                $stats['recent_activity'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $stats;
        } catch (PDOException $e) {
            error_log('Error calculating user statistics: ' . $e->getMessage());
            throw new Exception('Failed to calculate user statistics');
        }
    }
}
