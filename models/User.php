<?php
require_once 'Model.php';

class User extends Model {
    protected static $table = 'utilisateurs';

    public static function create($data) {
        $db = self::getConnection();
        $stmt = $db->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            $data['role']
        ]);
    }

    public static function update($id, $data) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE utilisateurs SET nom = ?, email = ?, role = ? WHERE id = ?");
        return $stmt->execute([
            $data['nom'],
            $data['email'],
            $data['role'],
            $id
        ]);
    }

    public static function authenticate($email, $password) {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }

    public static function validateTeacher($id) {
        $db = self::getConnection();
        $stmt = $db->prepare("UPDATE utilisateurs SET validated = 1 WHERE id = ? AND role = 'enseignant'");
        return $stmt->execute([$id]);
    }

    public static function getTeachers() {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE role = 'enseignant'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getStudents() {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE role = 'etudiant'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $db = self::getConnection();
        $stmt = $db->prepare("SELECT * FROM utilisateurs ORDER BY role, nom");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
