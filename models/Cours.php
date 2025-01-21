<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Tag.php';
require_once __DIR__ . '/Categorie.php';
require_once __DIR__ . '/Enseignant.php';

class Cours {
    private $db;
    private $id;
    private $titre;
    private $description;
    private $contenu;
    private $categorie;
    private $enseignant;
    private $tags = [];

    public function __construct($db, $id = null) {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->id = $id;
        
        if ($id) {
            $this->chargerCours();
        }
    }

    private function chargerCours() {
        try {
            $query = "SELECT c.*, cat.*, u.*
                     FROM cours c
                     JOIN categories cat ON c.categorie_id = cat.id
                     JOIN utilisateurs u ON c.enseignant_id = u.id
                     WHERE c.id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $this->titre = $data['titre'];
                $this->description = $data['description'];
                $this->contenu = $data['contenu'];
                $this->categorie = new Categorie($this->db, $data['categorie_id'], $data['nom']);
                $this->enseignant = new Enseignant($this->db, $data['enseignant_id'], $data['nom'], $data['email']);
                $this->chargerTags();
            }
        } catch (PDOException $e) {
            error_log('Error loading course: ' . $e->getMessage());
            throw new Exception('Failed to load course');
        }
    }

    private function chargerTags() {
        try {
            $query = "SELECT t.* FROM tags t
                     JOIN cours_tags ct ON t.id = ct.tag_id
                     WHERE ct.cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->id]);
            $this->tags = $stmt->fetchAll(PDO::FETCH_CLASS, 'Tag');
        } catch (PDOException $e) {
            error_log('Error loading course tags: ' . $e->getMessage());
            throw new Exception('Failed to load course tags');
        }
    }

    public function ajouterTag($tag) {
        try {
            if (!$this->id) {
                throw new Exception('Course must be saved before adding tags');
            }

            $query = "INSERT INTO cours_tags (cours_id, tag_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->id, $tag->getId()]);
            $this->tags[] = $tag;
            return true;
        } catch (PDOException $e) {
            error_log('Error adding tag to course: ' . $e->getMessage());
            throw new Exception('Failed to add tag to course');
        }
    }

    public function afficherDetails() {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'contenu' => $this->contenu,
            'categorie' => $this->categorie ? $this->categorie->getNom() : null,
            'enseignant' => $this->enseignant ? $this->enseignant->getNom() : null,
            'tags' => array_map(function($tag) { return $tag->getNom(); }, $this->tags)
        ];
    }

    public function sauvegarder() {
        try {
            if ($this->id) {
                $query = "UPDATE cours SET titre = ?, description = ?, contenu = ?, 
                         categorie_id = ?, enseignant_id = ? WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $this->titre,
                    $this->description,
                    $this->contenu,
                    $this->categorie->getId(),
                    $this->enseignant->getId(),
                    $this->id
                ]);
            } else {
                $query = "INSERT INTO cours (titre, description, contenu, categorie_id, enseignant_id) 
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $this->titre,
                    $this->description,
                    $this->contenu,
                    $this->categorie->getId(),
                    $this->enseignant->getId()
                ]);
                $this->id = $this->db->lastInsertId();
            }
            return true;
        } catch (PDOException $e) {
            error_log('Error saving course: ' . $e->getMessage());
            throw new Exception('Failed to save course');
        }
    }

    public function supprimer() {
        try {
            // First delete related records in cours_tags
            $query = "DELETE FROM cours_tags WHERE cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->id]);

            // Then delete related records in inscriptions
            $query = "DELETE FROM inscriptions WHERE cours_id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->id]);

            // Finally delete the course
            $query = "DELETE FROM cours WHERE id = ?";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$this->id]);
        } catch (PDOException $e) {
            error_log('Error deleting course: ' . $e->getMessage());
            throw new Exception('Failed to delete course');
        }
    }

    public static function rechercherCours($db, $searchTerm = '', $categorie = null, $page = 1, $perPage = 10) {
        try {
            $params = [];
            $conditions = [];
            $query = "SELECT DISTINCT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom 
                     FROM cours c
                     JOIN categories cat ON c.categorie_id = cat.id
                     JOIN utilisateurs u ON c.enseignant_id = u.id
                     LEFT JOIN cours_tags ct ON c.id = ct.cours_id
                     LEFT JOIN tags t ON ct.tag_id = t.id";

            if ($searchTerm) {
                $conditions[] = "(c.titre LIKE ? OR c.description LIKE ? OR t.nom LIKE ?)";
                $searchTerm = "%$searchTerm%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
            }

            if ($categorie) {
                $conditions[] = "c.categorie_id = ?";
                $params[] = $categorie;
            }

            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }

            $query .= " GROUP BY c.id ORDER BY c.titre";
            
            // Add pagination
            $offset = ($page - 1) * $perPage;
            $query .= " LIMIT ? OFFSET ?";
            $params[] = $perPage;
            $params[] = $offset;

            $stmt = $db->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Error searching courses: ' . $e->getMessage());
            throw new Exception('Failed to search courses');
        }
    }

    public function getAll($page = 1, $limit = 12)
    {
        try {
            $offset = ($page - 1) * $limit;
            $query = "SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom, u.email as enseignant_email
                     FROM cours c
                     JOIN categories cat ON c.categorie_id = cat.id
                     JOIN utilisateurs u ON c.enseignant_id = u.id
                     LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$limit, $offset]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $result = [];
            foreach ($courses as $course) {
                $courseObj = new Cours($this->db, $course['id']);
                $result[] = $courseObj->afficherDetails();
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('Error fetching courses: ' . $e->getMessage());
            throw new Exception('Failed to fetch courses');
        }
    }

    public function getTotalCourses()
    {
        try {
            $query = "SELECT COUNT(*) FROM cours";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error counting courses: ' . $e->getMessage());
            throw new Exception('Failed to count courses');
        }
    }

    public function getCoursesByKeyword($keyword, $page = 1, $limit = 12)
    {
        try {
            $offset = ($page - 1) * $limit;
            $searchTerm = "%$keyword%";
            
            $query = "SELECT c.*, cat.nom as categorie_nom, u.nom as enseignant_nom, u.email as enseignant_email
                     FROM cours c
                     JOIN categories cat ON c.categorie_id = cat.id
                     JOIN utilisateurs u ON c.enseignant_id = u.id
                     WHERE c.titre LIKE ? OR c.description LIKE ?
                     LIMIT ? OFFSET ?";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$searchTerm, $searchTerm, $limit, $offset]);
            $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $result = [];
            foreach ($courses as $course) {
                $courseObj = new Cours($this->db, $course['id']);
                $result[] = $courseObj->afficherDetails();
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log('Error searching courses: ' . $e->getMessage());
            throw new Exception('Failed to search courses');
        }
    }

    public function getTotalCoursesByKeyword($keyword)
    {
        try {
            $searchTerm = "%$keyword%";
            $query = "SELECT COUNT(*) FROM cours WHERE titre LIKE ? OR description LIKE ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$searchTerm, $searchTerm]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log('Error counting search results: ' . $e->getMessage());
            throw new Exception('Failed to count search results');
        }
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getContenu() { return $this->contenu; }
    public function getCategorie() { return $this->categorie; }
    public function getEnseignant() { return $this->enseignant; }
    public function getTags() { return $this->tags; }
    public function getEnseignantId() { return $this->enseignant ? $this->enseignant->getId() : null; }

    // Setters
    public function setTitre($titre) { $this->titre = $titre; }
    public function setDescription($description) { $this->description = $description; }
    public function setContenu($contenu) { $this->contenu = $contenu; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
    public function setEnseignant($enseignant) { $this->enseignant = $enseignant; }
}
