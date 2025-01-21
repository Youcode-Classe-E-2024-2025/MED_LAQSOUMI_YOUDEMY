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
                $stmt->execute([$tag]);
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
}