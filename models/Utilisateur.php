<?php
require_once __DIR__ . '/../config/database.php';
class User {
    private $db;
    private $id;
    private $role;
    private $name;
    private $email;
    private $password;

    public function __construct($db, $id = null, $role = null, $name = null, $email = null, $password = null) {
        $this->db = $db->getConnection();
        $this->id = $id;
        $this->role = $role;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function hashPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        return $this->password;
    }

    public function checkPassword($password, $hashedPassword) {
        $this->password = password_verify($password, $hashedPassword);
        return $this->password;
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
        try {
            $this->email = $email;
            $this->password = $password;
            $query = "SELECT * FROM utilisateurs WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($user && $this->checkPassword($password, $user['mot_de_passe'])) {
                $_SESSION['user_id'] = $this->id;
                $_SESSION['role'] = $this->role;
                $_SESSION['name'] = $this->name;
                return $user; 
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            return false;
        }
    }

    public function getUserById($id) {
        try {
            $this->id = $id;
            $query = "SELECT * FROM utilisateurs WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get user by ID error: ' . $e->getMessage());
            return false;
        }
    }


    public function getUserByRole($role) {
        try {
            $this->role = $role;
            $query = "SELECT * FROM utilisateurs WHERE role = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$role]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('Get user by ID error: ' . $e->getMessage());
            return false;
        }
    }
}