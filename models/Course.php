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
        $db = DatabaseConnection::getInstance();
        $this->db = $db->getConnection();
        $this->id = null;
        $this->titre = '';
        $this->description = '';
        $this->contenu = '';
        $this->enseignant_id = null;
        $this->categorie_id = null;
        $this->image = '';
        $this->tagid = null;
    }

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
            throw new Exception('Invalid user ID.');
        }
        $query = "SELECT c.*, u.nom as teacher_name, cat.nom as category_name, i.etudiant_id as inscrit
                  FROM inscriptions i 
                  JOIN cours c ON i.cours_id = c.id
                  JOIN utilisateurs u ON c.enseignant_id = u.id
                  JOIN categories cat ON c.categorie_id = cat.id
                  WHERE i.etudiant_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inscrireCours($user_id, $cours_id) {
        if (empty($user_id) || !is_numeric($user_id)) {
            throw new Exception('Invalid user ID.');
        }
        $query = "INSERT INTO inscriptions (etudiant_id, cours_id) VALUES (:user_id, :cours_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':cours_id', $cours_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}