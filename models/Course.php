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
}

