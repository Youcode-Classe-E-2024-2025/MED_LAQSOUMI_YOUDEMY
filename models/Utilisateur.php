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
        $query = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$nom, $email, $this->hashPassword($password), $role]);
        return $this->db->lastInsertId();
    }


    public function login($email, $password) {
        $query = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && $this->checkPassword($password, $user['mot_de_passe'])) {
            return $user;
        } else {
            return false;
        }
    }

    public function getUserById($id) {
        $query = "SELECT * FROM utilisateurs WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}

