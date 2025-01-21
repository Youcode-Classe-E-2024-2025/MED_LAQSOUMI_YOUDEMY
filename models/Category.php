<?php
require_once 'Model.php';

class Category extends Model {
    protected static $table = 'categories';

    public static function getAll() {
        $db = self::getConnection();
        $sql = "SELECT c.*, COUNT(co.id) as course_count 
                FROM categories c
                LEFT JOIN cours co ON c.id = co.categorie_id
                GROUP BY c.id, c.nom
                ORDER BY c.nom ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO categories (nom) VALUES (?)");
        return $stmt->execute([$data['nom']]);
    }

    public static function update($id, $data) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE categories SET nom = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $id]);
    }

    public static function getWithCourseCount() {
        $db = self::getConnection();
        $stmt = $db->query("SELECT c.*, COUNT(co.id) as course_count 
                           FROM categories c 
                           LEFT JOIN cours co ON c.id = co.categorie_id 
                           GROUP BY c.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function canDelete($id) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM cours WHERE categorie_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }
}
