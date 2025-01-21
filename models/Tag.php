<?php
require_once 'Model.php';

class Tag extends Model {
    protected static $table = 'tags';

    public static function create($data) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO tags (nom) VALUES (?)");
        return $stmt->execute([$data['nom']]);
    }

    public static function update($id, $data) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE tags SET nom = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $id]);
    }

    public static function bulkCreate($tags) {
        $db = self::getConnection();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO tags (nom) VALUES (?)");
            foreach ($tags as $tag) {
                if (!empty(trim($tag))) {
                    $stmt->execute([trim($tag)]);
                }
            }
            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getForCourse($courseId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT t.* FROM tags t 
                             JOIN cours_tags ct ON t.id = ct.tag_id 
                             WHERE ct.cours_id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getWithCourseCount() {
        $db = self::getConnection();
        $sql = "SELECT t.*, COUNT(ct.cours_id) as course_count 
                FROM tags t 
                LEFT JOIN cours_tags ct ON t.id = ct.tag_id 
                GROUP BY t.id, t.nom 
                ORDER BY t.nom";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function canDelete($tagId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM cours_tags WHERE tag_id = ?");
        $stmt->execute([$tagId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] == 0;
    }

    public static function delete($tagId) {
        if (!self::canDelete($tagId)) {
            throw new Exception("Cannot delete tag: it is used by one or more courses");
        }
        
        $db = self::getConnection();
        $stmt = $db->prepare("DELETE FROM tags WHERE id = ?");
        return $stmt->execute([$tagId]);
    }
}