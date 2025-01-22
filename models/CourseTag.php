<?php

class CourseTag {
    private $db;

    public function __construct($db) {
        if ($db instanceof DatabaseConnection) {
            $this->db = $db->getConnection();
        } else {
            $this->db = $db;
        }
    }

    public function addTagToCourse($courseId, $tagId) {
        try {
            $query = "INSERT INTO cours_tags (cours_id, tag_id) VALUES (:cours_id, :tag_id)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':cours_id' => $courseId,
                ':tag_id' => $tagId
            ]);
        } catch (PDOException $e) {
            // If the relationship already exists, we'll ignore the error
            if ($e->getCode() == '23000') { // Duplicate entry error
                return false;
            }
            throw $e;
        }
    }

    public function removeTagFromCourse($courseId, $tagId) {
        $query = "DELETE FROM cours_tags WHERE cours_id = :cours_id AND tag_id = :tag_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':cours_id' => $courseId,
            ':tag_id' => $tagId
        ]);
    }

    public function getTagsForCourse($courseId) {
        $query = "SELECT t.* FROM tags t 
                 INNER JOIN cours_tags ct ON t.id = ct.tag_id 
                 WHERE ct.cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':cours_id' => $courseId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCoursesForTag($tagId) {
        $query = "SELECT c.* FROM cours c 
                 INNER JOIN cours_tags ct ON c.id = ct.cours_id 
                 WHERE ct.tag_id = :tag_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':tag_id' => $tagId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function removeAllTagsFromCourse($courseId) {
        $query = "DELETE FROM cours_tags WHERE cours_id = :cours_id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':cours_id' => $courseId]);
    }
}
