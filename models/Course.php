<?php

class Course {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll($page = 1, $limit = 10) {
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


    public function getCoursesByKeyword($keyword, $page = 1, $limit = 10) {
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

