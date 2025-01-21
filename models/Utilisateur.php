<?php

require_once __DIR__ . '/../config/database.php';

abstract class Utilisateur {
    protected $db;
    protected $id;
    protected $nom;
    protected $email;
    protected $motDePasse;
    protected $role;

    public function __construct($db) {
        $dbInstance = DatabaseConnection::getInstance();
        $this->db = $dbInstance->getConnection();
    }

    public function connecter() {
        try {
            $query = "SELECT * FROM utilisateurs WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($this->motDePasse, $user['mot_de_passe'])) {
                $this->id = $user['id'];
                $this->nom = $user['nom'];
                $this->email = $user['email'];
                $this->role = $user['role'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Login error: ' . $e->getMessage());
            throw new Exception('Login failed');
        }
    }

    public function deconnecter() {
        session_start();
        session_destroy();
    }

    public function getRole() {
        return $this->role;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMotDePasse($motDePasse) {
        $this->motDePasse = $motDePasse;
    }

    public static function sEnregistrer($db, $nom, $email, $motDePasse, $role) {
        try {
            $dbInstance = DatabaseConnection::getInstance();
            $conn = $dbInstance->getConnection();
            
            // Hash the password
            $hashedPassword = password_hash($motDePasse, PASSWORD_DEFAULT);
            
            $query = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$nom, $email, $hashedPassword, $role]);
            
            return $conn->lastInsertId();
        } catch (PDOException $e) {
            error_log('Registration error: ' . $e->getMessage());
            throw new Exception('Registration failed');
        }
    }

    public function getEmail() {
        return $this->email;
    }

    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    // Protected getters/setters for child classes
    protected function setId($id) {
        $this->id = $id;
    }

    protected function setNom($nom) {
        $this->nom = $nom;
    }

    protected function setRole($role) {
        $this->role = $role;
    }
}