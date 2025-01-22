<?php

class AdminModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilisateurs");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalCourses() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM courses");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getPendingTeachersCount() {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'enseignant' AND status = 'pending'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getTotalTags() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM tags");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getUserCountByMonth($month) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM utilisateurs WHERE DATE_FORMAT(date_creation, '%Y-%m') = ?");
        $stmt->execute([$month]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getCourseCountByCategory() {
        $stmt = $this->db->query("
            SELECT c.nom as category, COUNT(co.id) as count 
            FROM categories c 
            LEFT JOIN courses co ON c.id = co.categorie_id 
            GROUP BY c.id, c.nom
        ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $distribution = [];
        foreach ($results as $row) {
            $distribution[$row['category']] = (int)$row['count'];
        }
        return $distribution;
    }

    public function getUserCountByRole($role) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM utilisateurs WHERE role = ?");
        $stmt->execute([$role]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    public function getRecentActivities($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT u.nom, u.prenom, 'Created Course' as action, c.date_creation as date
            FROM courses c
            JOIN utilisateurs u ON c.enseignant_id = u.id
            UNION ALL
            SELECT u.nom, u.prenom, 'Joined Platform' as action, u.date_creation as date
            FROM utilisateurs u
            ORDER BY date DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
