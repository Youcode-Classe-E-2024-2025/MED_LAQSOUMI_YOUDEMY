<?php
require_once 'Model.php';

class Statistics extends Model {
    public static function getGlobalStats() {
        $db = self::getConnection();
        try {
            $stats = [];
            
            // General statistics
            $stmt = $db->query("SELECT 
                (SELECT COUNT(*) FROM cours) as total_courses,
                (SELECT COUNT(*) FROM utilisateurs WHERE role = 'etudiant') as total_students,
                (SELECT COUNT(*) FROM utilisateurs WHERE role = 'enseignant') as total_teachers,
                (SELECT COUNT(*) FROM inscriptions) as total_enrollments");
            $stats['general'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Most popular courses
            $stmt = $db->query("SELECT c.*, u.nom as enseignant_nom, 
                              COUNT(DISTINCT i.etudiant_id) as student_count
                              FROM cours c
                              JOIN utilisateurs u ON c.enseignant_id = u.id
                              LEFT JOIN inscriptions i ON c.id = i.cours_id
                              GROUP BY c.id, c.titre, c.description, c.contenu, c.image, 
                                       c.categorie_id, c.enseignant_id, c.created_at, c.updated_at, u.nom
                              ORDER BY student_count DESC
                              LIMIT 1");
            $stats['most_popular_course'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Courses per category
            $stmt = $db->query("SELECT cat.nom, COUNT(c.id) as count
                              FROM categories cat
                              LEFT JOIN cours c ON cat.id = c.categorie_id
                              GROUP BY cat.id, cat.nom
                              ORDER BY count DESC");
            $stats['courses_per_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Top teachers
            $stmt = $db->query("SELECT u.nom, COUNT(DISTINCT c.id) as course_count,
                              COUNT(DISTINCT i.etudiant_id) as student_count
                              FROM utilisateurs u
                              LEFT JOIN cours c ON u.id = c.enseignant_id
                              LEFT JOIN inscriptions i ON c.id = i.cours_id
                              WHERE u.role = 'enseignant'
                              GROUP BY u.id, u.nom
                              ORDER BY student_count DESC
                              LIMIT 5");
            $stats['top_teachers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error getting global statistics: " . $e->getMessage());
        }
    }

    public static function getTeacherStats($teacherId) {
        $db = self::getConnection();
        try {
            $stats = [];

            // Course and student counts
            $stmt = $db->prepare("SELECT 
                COUNT(DISTINCT c.id) as total_courses,
                COUNT(DISTINCT i.etudiant_id) as total_students,
                COALESCE(AVG(CASE WHEN i.completed = 1 THEN 100 ELSE 0 END), 0) as avg_completion_rate
                FROM cours c
                LEFT JOIN inscriptions i ON c.id = i.cours_id
                WHERE c.enseignant_id = ?");
            $stmt->execute([$teacherId]);
            $stats['teaching'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Most popular course
            $stmt = $db->prepare("SELECT c.*, COUNT(DISTINCT i.etudiant_id) as enrollment_count
                                FROM cours c
                                LEFT JOIN inscriptions i ON c.id = i.cours_id
                                WHERE c.enseignant_id = ?
                                GROUP BY c.id, c.titre, c.description, c.contenu, c.image, 
                                         c.categorie_id, c.enseignant_id, c.created_at, c.updated_at
                                ORDER BY enrollment_count DESC
                                LIMIT 1");
            $stmt->execute([$teacherId]);
            $stats['most_popular_course'] = $stmt->fetch(PDO::FETCH_ASSOC);

            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error getting teacher statistics: " . $e->getMessage());
        }
    }

    public static function getStudentStats($studentId) {
        $db = self::getConnection();
        try {
            $stats = [];

            // Enrollment stats
            $stmt = $db->prepare("SELECT 
                COUNT(*) as total_courses,
                SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed_courses,
                COALESCE((SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(*), 0)), 0) as completion_rate
                FROM inscriptions
                WHERE etudiant_id = ?");
            $stmt->execute([$studentId]);
            $stats['learning'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Recent activity
            $stmt = $db->prepare("SELECT i.completed, i.progress, i.date_inscription, 
                                i.date_completion, c.titre,
                                CASE 
                                    WHEN i.completed = 1 THEN 'Completed'
                                    ELSE 'Enrolled'
                                END as status
                                FROM inscriptions i
                                JOIN cours c ON i.cours_id = c.id
                                WHERE i.etudiant_id = ?
                                ORDER BY 
                                    CASE 
                                        WHEN i.completed = 1 THEN i.date_completion 
                                        ELSE i.date_inscription 
                                    END DESC
                                LIMIT 5");
            $stmt->execute([$studentId]);
            $stats['recent_activity'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error getting student statistics: " . $e->getMessage());
        }
    }
}
