<?php
require_once 'Model.php';

class Statistics extends Model {
    public static function getGlobalStats() {
        $db = self::getConnection();
        try {
            $stats = [];
            
            // Total users by role
            $stmt = $db->query("SELECT role, COUNT(*) as count FROM utilisateurs GROUP BY role");
            $stats['users'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

            // Total courses and enrollments
            $stmt = $db->query("SELECT 
                (SELECT COUNT(*) FROM cours) as total_courses,
                (SELECT COUNT(*) FROM inscriptions) as total_enrollments,
                (SELECT COUNT(*) FROM categories) as total_categories");
            $stats['platform'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Most popular courses
            $stmt = $db->query("SELECT c.*, u.nom as enseignant_nom, 
                              COUNT(i.id) as enrollment_count
                              FROM cours c
                              JOIN utilisateurs u ON c.enseignant_id = u.id
                              LEFT JOIN inscriptions i ON c.id = i.cours_id
                              GROUP BY c.id
                              ORDER BY enrollment_count DESC
                              LIMIT 5");
            $stats['popular_courses'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Top teachers
            $stmt = $db->query("SELECT u.nom, COUNT(c.id) as course_count,
                              COUNT(DISTINCT i.etudiant_id) as student_count
                              FROM utilisateurs u
                              JOIN cours c ON u.id = c.enseignant_id
                              LEFT JOIN inscriptions i ON c.id = i.cours_id
                              WHERE u.role = 'enseignant'
                              GROUP BY u.id
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
                AVG(i.completed) * 100 as avg_completion_rate
                FROM cours c
                LEFT JOIN inscriptions i ON c.id = i.cours_id
                WHERE c.enseignant_id = ?");
            $stmt->execute([$teacherId]);
            $stats['teaching'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Most popular course
            $stmt = $db->prepare("SELECT c.*, COUNT(i.id) as enrollment_count
                                FROM cours c
                                LEFT JOIN inscriptions i ON c.id = i.cours_id
                                WHERE c.enseignant_id = ?
                                GROUP BY c.id
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
                (SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) as completion_rate
                FROM inscriptions
                WHERE etudiant_id = ?");
            $stmt->execute([$studentId]);
            $stats['learning'] = $stmt->fetch(PDO::FETCH_ASSOC);

            // Recent activity
            $stmt = $db->prepare("SELECT i.*, c.titre,
                                CASE 
                                    WHEN i.completed = 1 THEN 'Completed the course'
                                    ELSE 'Enrolled in course'
                                END as description,
                                DATE_FORMAT(i.date_inscription, '%Y-%m-%d') as date
                                FROM inscriptions i
                                JOIN cours c ON i.cours_id = c.id
                                WHERE i.etudiant_id = ?
                                ORDER BY i.date_inscription DESC
                                LIMIT 5");
            $stmt->execute([$studentId]);
            $stats['recent_activity'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (Exception $e) {
            throw new Exception("Error getting student statistics: " . $e->getMessage());
        }
    }
}
