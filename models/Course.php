<?php
require_once 'Model.php';

class Course extends Model {
    protected static $table = 'cours';

    public static function create($data) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO cours (titre, description, contenu, image, categorie_id, enseignant_id, published) 
                             VALUES (?, ?, ?, ?, ?, ?, ?)");
        $db->beginTransaction();
        try {
            $stmt->execute([
                $data['titre'],
                $data['description'],
                $data['contenu'],
                $data['image'] ?? null,
                $data['categorie_id'],
                $data['enseignant_id'],
                $data['published'] ?? 0
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
        $updateFields = ["titre = ?", "description = ?", "contenu = ?", "categorie_id = ?"];
        $params = [
            $data['titre'],
            $data['description'],
            $data['contenu'],
            $data['categorie_id']
        ];

        // Add image to update if provided
        if (isset($data['image'])) {
            $updateFields[] = "image = ?";
            $params[] = $data['image'];
        }

        // Add published status if provided
        if (isset($data['published'])) {
            $updateFields[] = "published = ?";
            $params[] = $data['published'];
        }

        $params[] = $id;  // Add id for WHERE clause

        $sql = "UPDATE cours SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $stmt = $db->prepare($sql);

        $db->beginTransaction();
        try {
            $stmt->execute($params);

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

    public static function getPublishedCourses($page = 1, $perPage = 9, $search = '', $categoryId = null) {
        $db = self::getConnection();
        
        $params = [];
        $types = [];  // Store parameter types for proper binding
        $conditions = ["c.published = 1"];
        
        if ($search) {
            $conditions[] = "(c.titre LIKE ? OR c.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types[] = PDO::PARAM_STR;
            $types[] = PDO::PARAM_STR;
        }
        
        if ($categoryId) {
            $conditions[] = "c.categorie_id = ?";
            $params[] = $categoryId;
            $types[] = PDO::PARAM_INT;
        }
        
        $whereClause = implode(" AND ", $conditions);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(DISTINCT c.id) as count 
                     FROM cours c
                     WHERE $whereClause";
        $countStmt = $db->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key + 1, $value, $types[$key] ?? PDO::PARAM_STR);
        }
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Calculate pagination
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        
        // Get courses
        $sql = "SELECT c.*, cat.nom as category_name, u.nom as teacher_name,
                       COUNT(DISTINCT i.etudiant_id) as student_count
                FROM cours c
                LEFT JOIN categories cat ON c.categorie_id = cat.id
                LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                LEFT JOIN inscriptions i ON c.id = i.cours_id
                WHERE $whereClause
                GROUP BY c.id, c.titre, c.description, c.image, 
                         c.categorie_id, c.enseignant_id, c.created_at,
                         cat.nom, u.nom
                ORDER BY c.created_at DESC
                LIMIT ?, ?";
        
        $stmt = $db->prepare($sql);
        
        // Bind where clause parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key + 1, $value, $types[$key] ?? PDO::PARAM_STR);
        }
        
        // Bind pagination parameters
        $paramCount = count($params);
        $stmt->bindValue($paramCount + 1, $offset, PDO::PARAM_INT);
        $stmt->bindValue($paramCount + 2, $perPage, PDO::PARAM_INT);
        
        $stmt->execute();
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'courses' => $courses,
            'total' => $total,
            'per_page' => $perPage,
            'total_pages' => $totalPages,
            'current_page' => $page
        ];
    }

    public static function getEnrolledCourses($studentId) {
        $db = self::getConnection();
        $sql = "SELECT c.*, cat.nom as category_name, u.nom as teacher_name,
                       i.created_at as enrollment_date, i.completed,
                       (SELECT COUNT(*) FROM inscriptions i2 WHERE i2.cours_id = c.id) as student_count
                FROM cours c
                INNER JOIN inscriptions i ON c.id = i.cours_id
                LEFT JOIN categories cat ON c.categorie_id = cat.id
                LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                WHERE i.etudiant_id = ?
                ORDER BY i.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCourseWithDetails($id) {
        $db = self::getConnection();
        $sql = "SELECT c.*, cat.nom as category_name, u.nom as teacher_name,
                       (SELECT COUNT(*) FROM inscriptions i WHERE i.cours_id = c.id) as student_count
                FROM cours c
                LEFT JOIN categories cat ON c.categorie_id = cat.id
                LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                WHERE c.id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getEnrollmentDetails($courseId, $studentId) {
        $db = self::getConnection();
        $sql = "SELECT * FROM inscriptions 
                WHERE cours_id = ? AND etudiant_id = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$courseId, $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function isStudentEnrolled($courseId, $studentId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM inscriptions 
                             WHERE cours_id = ? AND etudiant_id = ?");
        $stmt->execute([$courseId, $studentId]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0;
    }

    public static function enrollStudent($courseId, $studentId) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO inscriptions (cours_id, etudiant_id, created_at) 
                             VALUES (?, ?, CURRENT_TIMESTAMP)");
        return $stmt->execute([$courseId, $studentId]);
    }

    public static function markCourseCompleted($courseId, $studentId) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE inscriptions SET completed = 1 
                             WHERE cours_id = ? AND etudiant_id = ?");
        return $stmt->execute([$courseId, $studentId]);
    }

    public static function getAllWithDetails() {
        $db = self::getConnection();
        $sql = "SELECT c.*, 
                       cat.nom as category_name,
                       u.nom as teacher_name,
                       COUNT(DISTINCT i.etudiant_id) as student_count
                FROM cours c
                LEFT JOIN categories cat ON c.categorie_id = cat.id
                LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                LEFT JOIN inscriptions i ON c.id = i.cours_id
                GROUP BY c.id, c.titre, c.description, c.contenu, c.image, 
                         c.categorie_id, c.enseignant_id, c.created_at, c.updated_at,
                         cat.nom, u.nom
                ORDER BY c.created_at DESC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getWithDetails($id) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT c.*, 
                                    cat.nom as category_name,
                                    u.nom as teacher_name,
                                    COUNT(DISTINCT i.etudiant_id) as student_count
                             FROM cours c
                             LEFT JOIN categories cat ON c.categorie_id = cat.id
                             LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                             LEFT JOIN inscriptions i ON c.id = i.cours_id
                             WHERE c.id = ?
                             GROUP BY c.id, c.titre, c.description, c.contenu, c.image, 
                                      c.categorie_id, c.enseignant_id, c.created_at, c.updated_at,
                                      cat.nom, u.nom");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function publish($id) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE cours SET published = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function unpublish($id) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE cours SET published = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function delete($id) {
        $db = self::getConnection();
        $db->beginTransaction();
        try {
            // Delete enrollments
            $stmt = $db->prepare("DELETE FROM inscriptions WHERE cours_id = ?");
            $stmt->execute([$id]);

            // Delete tag associations
            $stmt = $db->prepare("DELETE FROM cours_tags WHERE cours_id = ?");
            $stmt->execute([$id]);

            // Delete the course
            $stmt = $db->prepare("DELETE FROM cours WHERE id = ?");
            $stmt->execute([$id]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getByTeacher($teacherId) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT c.*, cat.nom as categorie_nom,
                             COUNT(DISTINCT i.etudiant_id) as student_count
                             FROM cours c 
                             LEFT JOIN categories cat ON c.categorie_id = cat.id 
                             LEFT JOIN inscriptions i ON c.id = i.cours_id
                             WHERE c.enseignant_id = ?
                             GROUP BY c.id, c.titre, c.description, c.contenu, c.image, 
                                      c.categorie_id, c.enseignant_id, c.created_at, c.updated_at,
                                      cat.nom
                             ORDER BY c.created_at DESC");
        $stmt->execute([$teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($query = '', $filters = [], $page = 1, $perPage = 12) {
        $db = self::getConnection();
        
        $conditions = [];
        $params = [];
        
        if (!empty($query)) {
            $conditions[] = "(c.titre LIKE ? OR c.description LIKE ?)";
            $params[] = "%$query%";
            $params[] = "%$query%";
        }
        
        if (!empty($filters['category'])) {
            $conditions[] = "c.categorie_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['tags'])) {
            $tagIds = implode(',', array_map('intval', $filters['tags']));
            $conditions[] = "EXISTS (SELECT 1 FROM cours_tags ct WHERE ct.cours_id = c.id AND ct.tag_id IN ($tagIds))";
        }
        
        $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
        
        // Get total count
        $countSql = "SELECT COUNT(DISTINCT c.id) as count 
                     FROM cours c 
                     LEFT JOIN cours_tags ct ON c.id = ct.cours_id 
                     $where";
        $stmt = $db->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Get paginated results
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom,
                COUNT(DISTINCT i.etudiant_id) as student_count
                FROM cours c 
                LEFT JOIN categories cat ON c.categorie_id = cat.id
                LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                LEFT JOIN inscriptions i ON c.id = i.cours_id
                $where
                GROUP BY c.id, c.titre, c.description, c.contenu, c.image, 
                         c.categorie_id, c.enseignant_id, c.created_at, c.updated_at,
                         cat.nom, u.nom
                ORDER BY c.created_at DESC
                LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'courses' => $courses,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }

    public static function myEnrollments($studentId, $page = 1, $perPage = 12) {
        $db = self::getConnection();
        
        // Get total count
        $countStmt = $db->prepare("SELECT COUNT(*) as count FROM inscriptions WHERE etudiant_id = ?");
        $countStmt->execute([$studentId]);
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Get paginated results
        $offset = ($page - 1) * $perPage;
        $stmt = $db->prepare("SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom,
                             i.progress, i.completed, i.date_inscription
                             FROM inscriptions i
                             JOIN cours c ON i.cours_id = c.id
                             LEFT JOIN categories cat ON c.categorie_id = cat.id
                             LEFT JOIN utilisateurs u ON c.enseignant_id = u.id
                             WHERE i.etudiant_id = ?
                             ORDER BY i.date_inscription DESC
                             LIMIT ? OFFSET ?");
        $stmt->execute([$studentId, $perPage, $offset]);
        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'courses' => $courses,
            'total' => $total,
            'pages' => ceil($total / $perPage),
            'current_page' => $page
        ];
    }
}
