<?php

require_once __DIR__ . '/../config/database.php';

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
        $query = "SELECT c.*, u.nom as teacher_name, cat.nom as category_name 
                  FROM cours c
                  JOIN utilisateurs u ON c.enseignant_id = u.id
                  JOIN categories cat ON c.categorie_id = cat.id
                  WHERE c.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
}
