<?php

class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function checkPassword($password, $hashedPassword) {
        return password_verify($password, $hashedPassword);
    }

    public function register($nom, $email, $password, $role) {
        try {
            $query = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$nom, $email, $this->hashPassword($password), $role]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log('Registration error: ' . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password) {
        $query = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && $this->checkPassword($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['nom'];
            return $user; // Change this line to return the user object
        } else {
            return false;
        }
    }

    public function getUserById($id) {
        try {
            $query = "SELECT * FROM utilisateurs WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get user error: ' . $e->getMessage());
            return false;
        }
    }
}