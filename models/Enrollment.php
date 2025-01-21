<?php
require_once 'Model.php';

class Enrollment extends Model {
    protected static $table = 'inscriptions';

    public static function create($data) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO inscriptions (etudiant_id, cours_id, date_inscription) VALUES (?, ?, NOW())");
        return $stmt->execute([$data['etudiant_id'], $data['cours_id']]);
    }

    public static function markCompleted($studentId, $courseId) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE inscriptions SET completed = 1 WHERE etudiant_id = ? AND cours_id = ?");
        return $stmt->execute([$studentId, $courseId]);
    }

    public static function getStudentEnrollments($studentId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT i.*, c.titre, c.description, u.nom as enseignant_nom 
                             FROM inscriptions i
                             JOIN cours c ON i.cours_id = c.id
                             JOIN utilisateurs u ON c.enseignant_id = u.id
                             WHERE i.etudiant_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCourseEnrollments($courseId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT i.*, u.nom, u.email 
                             FROM inscriptions i
                             JOIN utilisateurs u ON i.etudiant_id = u.id
                             WHERE i.cours_id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function isEnrolled($studentId, $courseId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM inscriptions 
                             WHERE etudiant_id = ? AND cours_id = ?");
        $stmt->execute([$studentId, $courseId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public static function getStudentProgress($studentId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT 
                                COUNT(*) as total_courses,
                                SUM(CASE WHEN completed = 1 THEN 1 ELSE 0 END) as completed_courses
                             FROM inscriptions 
                             WHERE etudiant_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
