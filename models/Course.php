<?php
require_once 'Model.php';

class Course extends Model {
    protected static $table = 'cours';

    public static function create($data) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO cours (titre, description, contenu, categorie_id, enseignant_id) VALUES (?, ?, ?, ?, ?)");
        $db->beginTransaction();
        try {
            $stmt->execute([
                $data['titre'],
                $data['description'],
                $data['contenu'],
                $data['categorie_id'],
                $data['enseignant_id']
            ]);
            $courseId = $db->lastInsertId();

            // Add tags if present
            if (!empty($data['tags'])) {
                $tagStmt = $db->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)");
                foreach ($data['tags'] as $tagId) {
                    $tagStmt->execute([$courseId, $tagId]);
                }
            }
            
            $db->commit();
            return $courseId;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function update($id, $data) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE cours SET titre = ?, description = ?, contenu = ?, categorie_id = ? WHERE id = ?");
        $db->beginTransaction();
        try {
            $stmt->execute([
                $data['titre'],
                $data['description'],
                $data['contenu'],
                $data['categorie_id'],
                $id
            ]);

            // Update tags
            if (isset($data['tags'])) {
                // Remove old tags
                $deleteStmt = $db->prepare("DELETE FROM cours_tags WHERE cours_id = ?");
                $deleteStmt->execute([$id]);

                // Add new tags
                $tagStmt = $db->prepare("INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)");
                foreach ($data['tags'] as $tagId) {
                    $tagStmt->execute([$id, $tagId]);
                }
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getByTeacher($teacherId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT c.*, cat.nom as categorie_nom FROM cours c 
                             JOIN categories cat ON c.categorie_id = cat.id 
                             WHERE c.enseignant_id = ?");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getWithDetails($id) {
        $db = self::getConnection();
        try {
            // Get course basic info
            $stmt = $db->prepare("SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom,
                                (SELECT COUNT(*) FROM inscriptions WHERE cours_id = c.id) as nombre_inscrits
                                FROM cours c 
                                JOIN categories cat ON c.categorie_id = cat.id
                                JOIN utilisateurs u ON c.enseignant_id = u.id
                                WHERE c.id = ?");
            $stmt->execute([$id]);
            $course = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$course) {
                return null;
            }

            // Get course tags
            $tagStmt = $db->prepare("SELECT t.* FROM tags t 
                                   JOIN cours_tags ct ON t.id = ct.tag_id 
                                   WHERE ct.cours_id = ?");
            $tagStmt->execute([$id]);
            $course['tags'] = $tagStmt->fetchAll(PDO::FETCH_ASSOC);

            // Get course sections and lessons
            $sectionStmt = $db->prepare("SELECT * FROM sections WHERE cours_id = ? ORDER BY ordre");
            $sectionStmt->execute([$id]);
            $course['sections'] = $sectionStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($course['sections'] as &$section) {
                $lessonStmt = $db->prepare("SELECT * FROM lecons WHERE section_id = ? ORDER BY ordre");
                $lessonStmt->execute([$section['id']]);
                $section['lessons'] = $lessonStmt->fetchAll(PDO::FETCH_ASSOC);
            }

            // Get other courses by the same teacher
            $otherCoursesStmt = $db->prepare("SELECT c.id, c.titre, cat.nom as categorie_nom,
                                            (SELECT COUNT(*) FROM inscriptions WHERE cours_id = c.id) as nombre_inscrits
                                            FROM cours c 
                                            JOIN categories cat ON c.categorie_id = cat.id
                                            WHERE c.enseignant_id = ? AND c.id != ?
                                            LIMIT 5");
            $otherCoursesStmt->execute([$course['enseignant_id'], $id]);
            $course['autres_cours'] = $otherCoursesStmt->fetchAll(PDO::FETCH_ASSOC);

            return $course;
        } catch (Exception $e) {
            throw new Exception("Failed to fetch course details: " . $e->getMessage());
        }
    }

    public static function search($filters = [], $page = 1, $perPage = 12) {
        $db = self::getConnection();
        try {
            $conditions = [];
            $params = [];
            $query = "SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom,
                     (SELECT COUNT(*) FROM inscriptions WHERE cours_id = c.id) as nombre_inscrits
                     FROM cours c 
                     JOIN categories cat ON c.categorie_id = cat.id
                     JOIN utilisateurs u ON c.enseignant_id = u.id";

            if (!empty($filters['search'])) {
                $conditions[] = "(c.titre LIKE ? OR c.description LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            if (!empty($filters['category'])) {
                $conditions[] = "c.categorie_id = ?";
                $params[] = $filters['category'];
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }

            if (!empty($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'popular':
                        $query .= " ORDER BY nombre_inscrits DESC";
                        break;
                    case 'recent':
                    default:
                        $query .= " ORDER BY c.created_at DESC";
                        break;
                }
            }

            // Add pagination
            $offset = ($page - 1) * $perPage;
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get tags for each course
            foreach ($courses as &$course) {
                $tagStmt = $db->prepare("SELECT t.* FROM tags t 
                                       JOIN cours_tags ct ON t.id = ct.tag_id 
                                       WHERE ct.cours_id = ?");
                $tagStmt->execute([$course['id']]);
                $course['tags'] = $tagStmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $courses;
        } catch (Exception $e) {
            throw new Exception("Failed to fetch courses: " . $e->getMessage());
        }
    }
}
