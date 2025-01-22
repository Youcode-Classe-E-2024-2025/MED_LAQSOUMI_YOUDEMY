<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/CourseTag.php';

class Course {
    private $db;
    private $id;
    private $titre;
    private $description;
    private $contenu;
    private $enseignant_id;
    private $categorie_id;
    private $image;
    private $tagid = null;
    private $courseTagManager;

    public function __construct($db) {
        if ($db instanceof DatabaseConnection) {
            $this->db = $db->getConnection();
        } else {
            $this->db = $db;
        }
        $this->id = null;
        $this->titre = '';
        $this->description = '';
        $this->contenu = '';
        $this->enseignant_id = null;
        $this->categorie_id = null;
        $this->image = '';
        $this->tagid = null;
        $this->courseTagManager = new CourseTag($db);
    }

    // public function ajouterTag($tagId){
    //     $query = "INSERT INTO cours_tags (course_id, tag_id) VALUES (:course_id, :tag_id)";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bindParam(':course_id', $this->id, PDO::PARAM_INT);
    //     $stmt->bindParam(':tag_id', $tagId, PDO::PARAM_INT);
    //     return $stmt->execute();
    // }

    // public function afficherDetails() {
    //     $query = "SELECT c.*, u.nom as teacher_name, cat.nom as category_name 
    //               FROM cours c
    //               JOIN utilisateurs u ON c.enseignant_id = u.id
    //               JOIN categories cat ON c.categorie_id = cat.id
    //               WHERE c.id = ?";
    //     $stmt = $this->db->prepare($query);
    //     $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
    //     $stmt->execute();
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }


    public function getCourseById($id) {
        try {
            if (!$id || !is_numeric($id)) {
                error_log("Invalid course ID: " . var_export($id, true));
                return null;
            }

            $query = "SELECT c.*, cat.nom as category_name 
                    FROM cours c
                    LEFT JOIN categories cat ON c.categorie_id = cat.id
                    WHERE c.id = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                error_log("Failed to execute getCourseById query");
                return null;
            }
            
            $course = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$course) {
                error_log("No course found with ID: " . $id);
                return null;
            }
            
            error_log("Retrieved course data: " . json_encode($course));
            return $course;
            
        } catch (PDOException $e) {
            error_log("Database error in getCourseById: " . $e->getMessage());
            return null;
        } catch (Exception $e) {
            error_log("General error in getCourseById: " . $e->getMessage());
            return null;
        }
    }



    public function getAll($page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT c.*, u.nom as teacher_name, cat.nom as category_name 
                  FROM cours c
                  JOIN utilisateurs u ON c.enseignant_id = u.id
                  JOIN categories cat ON c.categorie_id = cat.id
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getTotalCourses() {
        $query = "SELECT COUNT(*) FROM cours";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public function getCoursesByKeyword($keyword, $page = 1, $limit = 12) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT c.*, u.nom as teacher_name, cat.nom as category_name 
              FROM cours c
              JOIN utilisateurs u ON c.enseignant_id = u.id
              JOIN categories cat ON c.categorie_id = cat.id
              WHERE c.titre LIKE :keyword OR 
                    c.description LIKE :keyword OR
                    c.contenu LIKE :keyword OR
                    u.nom LIKE :keyword OR
                    cat.nom LIKE :keyword
              LIMIT :limit OFFSET :offset";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalCoursesByKeyword($keyword) {
        $query = "SELECT COUNT(*) FROM cours c
              JOIN utilisateurs u ON c.enseignant_id = u.id
              JOIN categories cat ON c.categorie_id = cat.id
              WHERE c.titre LIKE :keyword OR 
                    c.description LIKE :keyword OR
                    c.contenu LIKE :keyword OR
                    u.nom LIKE :keyword OR
                    cat.nom LIKE :keyword";
    
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function getMyCourses($user_id) {
        if (empty($user_id) || !is_numeric($user_id)) {
            throw new Exception('Invalid user ID');
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, u.nom as teacher_name, cat.nom as category_name,
                       i.date_inscription
                FROM cours c
                INNER JOIN inscriptions i ON c.id = i.cours_id
                INNER JOIN utilisateurs u ON c.enseignant_id = u.id
                INNER JOIN categories cat ON c.categorie_id = cat.id
                WHERE i.etudiant_id = :user_id
                ORDER BY i.date_inscription DESC
            ");
            
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching courses: " . $e->getMessage());
            throw new Exception('Error fetching enrolled courses');
        }
    }

    public function inscrireCours($user_id, $cours_id) {
        if (empty($user_id) || !is_numeric($user_id) || empty($cours_id) || !is_numeric($cours_id)) {
            throw new Exception('Invalid user ID or course ID');
        }

        try {
            error_log("Starting enrollment - User ID: $user_id, Course ID: $cours_id");
            
            $this->db->beginTransaction();

            // Check if the user exists
            $userCheck = $this->db->prepare("SELECT id FROM utilisateurs WHERE id = :user_id AND role = 'etudiant'");
            $userCheck->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $userCheck->execute();
            
            if (!$userCheck->fetch()) {
                $this->db->rollBack();
                throw new Exception('Invalid student ID');
            }

            // Check if the course exists
            $courseCheck = $this->db->prepare("SELECT id FROM cours WHERE id = :cours_id");
            $courseCheck->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
            $courseCheck->execute();
            
            if (!$courseCheck->fetch()) {
                $this->db->rollBack();
                throw new Exception('Course does not exist');
            }

            // Check if already enrolled
            $enrollCheck = $this->db->prepare("SELECT id FROM inscriptions WHERE etudiant_id = :user_id AND cours_id = :cours_id");
            $enrollCheck->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $enrollCheck->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
            $enrollCheck->execute();
            
            if ($enrollCheck->fetch()) {
                $this->db->rollBack();
                throw new Exception('Already enrolled in this course');
            }

            // Insert the enrollment
            $insert = $this->db->prepare("
                INSERT INTO inscriptions (etudiant_id, cours_id, date_inscription) 
                VALUES (:user_id, :cours_id, NOW())
            ");
            $insert->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $insert->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
            
            if (!$insert->execute()) {
                $error = $insert->errorInfo();
                $this->db->rollBack();
                error_log("Insert error: " . print_r($error, true));
                throw new Exception('Failed to enroll in course');
            }

            $this->db->commit();
            error_log("Successfully enrolled user $user_id in course $cours_id");
            return true;

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw new Exception('Database error: ' . $e->getMessage());
        }
    }

    public function isEnrolled($user_id, $cours_id) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM inscriptions 
                WHERE etudiant_id = :user_id 
                AND cours_id = :cours_id
            ");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log("Error checking enrollment: " . $e->getMessage());
            throw new Exception('Error checking enrollment status');
        }
    }

    public function addCourse($data) {
        try {
            $query = "INSERT INTO cours (titre, description, contenu, image, enseignant_id, categorie_id) 
                     VALUES (:titre, :description, :contenu, :image, :enseignant_id, :categorie_id)";
            
            // If no image provided, use a default placeholder
            $image = $data['image'] ?? 'https://placehold.co/300';
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':titre', $data['titre']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':contenu', $data['contenu']);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':enseignant_id', $data['enseignant_id']);
            $stmt->bindParam(':categorie_id', $data['categorie_id']);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error adding course: " . $e->getMessage());
            return false;
        }
    }

    public function updateCourse($courseId, $data) {
        try {
            // Debug log
            error_log("Updating course ID: " . $courseId . " with data: " . json_encode($data));

            // Validate inputs
            if (!$courseId || !is_numeric($courseId)) {
                error_log("Invalid course ID: " . var_export($courseId, true));
                return false;
            }

            $query = "UPDATE cours 
                     SET titre = :titre, 
                         description = :description, 
                         contenu = :contenu, 
                         categorie_id = :categorie_id, 
                         image = :image
                     WHERE id = :id";
            
            $stmt = $this->db->prepare($query);
            
            // Bind parameters
            $params = [
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':contenu' => $data['contenu'],
                ':categorie_id' => $data['categorie_id'],
                ':image' => $data['image'] ?? 'https://placehold.co/300',
                ':id' => $courseId
            ];
            
            // Debug log
            error_log("SQL Query: " . $query);
            error_log("Parameters: " . json_encode($params));
            
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }
            
            $result = $stmt->execute();
            
            if (!$result) {
                error_log("Failed to update course. PDO Error: " . json_encode($stmt->errorInfo()));
                return false;
            }
            
            // Check if any rows were affected
            if ($stmt->rowCount() === 0) {
                error_log("No rows were updated for course ID: " . $courseId);
                return false;
            }
            
            error_log("Successfully updated course ID: " . $courseId);
            return true;
            
        } catch (PDOException $e) {
            error_log("Database error in updateCourse: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("General error in updateCourse: " . $e->getMessage());
            return false;
        }
    }

    public function deleteCourse($courseId, $teacherId) {
        try {
            $this->db->beginTransaction();

            // Verify the course belongs to the teacher
            $checkQuery = "SELECT id FROM cours WHERE id = :id AND enseignant_id = :enseignant_id";
            $checkStmt = $this->db->prepare($checkQuery);
            $checkStmt->bindParam(':id', $courseId, PDO::PARAM_INT);
            $checkStmt->bindParam(':enseignant_id', $teacherId, PDO::PARAM_INT);
            $checkStmt->execute();

            if (!$checkStmt->fetch()) {
                throw new Exception('Course not found or unauthorized');
            }

            // Delete course tags
            $deleteTagsQuery = "DELETE FROM cours_tags WHERE cours_id = :cours_id";
            $deleteTagsStmt = $this->db->prepare($deleteTagsQuery);
            $deleteTagsStmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
            $deleteTagsStmt->execute();

            // Delete course enrollments
            $deleteEnrollQuery = "DELETE FROM inscriptions WHERE cours_id = :cours_id";
            $deleteEnrollStmt = $this->db->prepare($deleteEnrollQuery);
            $deleteEnrollStmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
            $deleteEnrollStmt->execute();

            // Delete the course
            $deleteCourseQuery = "DELETE FROM cours WHERE id = :id AND enseignant_id = :enseignant_id";
            $deleteCourseStmt = $this->db->prepare($deleteCourseQuery);
            $deleteCourseStmt->bindParam(':id', $courseId, PDO::PARAM_INT);
            $deleteCourseStmt->bindParam(':enseignant_id', $teacherId, PDO::PARAM_INT);
            
            if (!$deleteCourseStmt->execute()) {
                throw new Exception('Failed to delete course');
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getEnrollments($courseId, $teacherId) {
        try {
            $query = "SELECT i.*, u.nom as student_name, u.email as student_email
                     FROM inscriptions i
                     JOIN utilisateurs u ON i.etudiant_id = u.id
                     JOIN cours c ON i.cours_id = c.id
                     WHERE c.id = :cours_id AND c.enseignant_id = :enseignant_id
                     ORDER BY i.date_inscription DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':cours_id', $courseId, PDO::PARAM_INT);
            $stmt->bindParam(':enseignant_id', $teacherId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Failed to get course enrollments');
        }
    }

    public function getTeacherStatistics($teacherId) {
        try {
            $stats = [];
            
            // Get total courses
            $coursesQuery = "SELECT COUNT(*) FROM cours WHERE enseignant_id = :enseignant_id";
            $coursesStmt = $this->db->prepare($coursesQuery);
            $coursesStmt->bindParam(':enseignant_id', $teacherId, PDO::PARAM_INT);
            $coursesStmt->execute();
            $stats['total_courses'] = $coursesStmt->fetchColumn();

            // Get total students
            $studentsQuery = "SELECT COUNT(DISTINCT i.etudiant_id) 
                            FROM inscriptions i
                            JOIN cours c ON i.cours_id = c.id
                            WHERE c.enseignant_id = :enseignant_id";
            $studentsStmt = $this->db->prepare($studentsQuery);
            $studentsStmt->bindParam(':enseignant_id', $teacherId, PDO::PARAM_INT);
            $studentsStmt->execute();
            $stats['total_students'] = $studentsStmt->fetchColumn();

            // Get enrollments per course
            $enrollmentsQuery = "SELECT c.titre, COUNT(i.id) as enrollment_count
                               FROM cours c
                               LEFT JOIN inscriptions i ON c.id = i.cours_id
                               WHERE c.enseignant_id = :enseignant_id
                               GROUP BY c.id
                               ORDER BY enrollment_count DESC";
            $enrollmentsStmt = $this->db->prepare($enrollmentsQuery);
            $enrollmentsStmt->bindParam(':enseignant_id', $teacherId, PDO::PARAM_INT);
            $enrollmentsStmt->execute();
            $stats['enrollments_per_course'] = $enrollmentsStmt->fetchAll(PDO::FETCH_ASSOC);

            return $stats;
        } catch (Exception $e) {
            throw new Exception('Failed to get teacher statistics');
        }
    }

    public function getTeacherCourses($teacherId) {
        try {
            // Validate teacher ID
            if (!$teacherId || !is_numeric($teacherId)) {
                error_log("Invalid teacher ID: " . var_export($teacherId, true));
                return [];
            }

            $query = "
                SELECT c.*, cat.nom as category_name 
                FROM cours c 
                LEFT JOIN categories cat ON c.categorie_id = cat.id 
                WHERE c.enseignant_id = :teacher_id
                ORDER BY c.created_at DESC
            ";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':teacher_id', $teacherId, PDO::PARAM_INT);
            $stmt->execute();
            
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($courses)) {
                error_log("No courses found for teacher ID: " . $teacherId);
            } else {
                error_log("Found " . count($courses) . " courses for teacher ID: " . $teacherId);
            }
            
            return $courses;
        } catch (PDOException $e) {
            error_log("Database error in getTeacherCourses: " . $e->getMessage());
            return [];
        } catch (Exception $e) {
            error_log("General error in getTeacherCourses: " . $e->getMessage());
            return [];
        }
    }

    public function addTag($tagId) {
        if (!$this->id) {
            throw new Exception("Course must be saved before adding tags");
        }
        return $this->courseTagManager->addTagToCourse($this->id, $tagId);
    }

    public function removeTag($tagId) {
        if (!$this->id) {
            throw new Exception("Course must be saved before removing tags");
        }
        return $this->courseTagManager->removeTagFromCourse($this->id, $tagId);
    }

    public function getTags() {
        if (!$this->id) {
            return [];
        }
        return $this->courseTagManager->getTagsForCourse($this->id);
    }

    public function setTags($tagIds) {
        if (!$this->id) {
            throw new Exception("Course must be saved before setting tags");
        }
        // First remove all existing tags
        $this->courseTagManager->removeAllTagsFromCourse($this->id);
        // Then add new tags
        foreach ($tagIds as $tagId) {
            $this->courseTagManager->addTagToCourse($this->id, $tagId);
        }
        return true;
    }
}
