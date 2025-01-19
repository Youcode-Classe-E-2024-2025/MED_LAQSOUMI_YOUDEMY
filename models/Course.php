<?php

class Cours {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($name, $description, $user_id, $is_public = false) {
        try {
            $query = "INSERT INTO cours (name, description, user_id, is_public) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$name, $description, $user_id, $is_public]);
            if (!$result) {
                throw new \Exception("Failed to create Cours");
            }
            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            error_log("Error creating Cours: " . $e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM cours WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $query = "SELECT * FROM cours";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

