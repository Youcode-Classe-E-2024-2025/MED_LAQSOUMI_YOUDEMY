<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/Etudiant.php';

class UtilisateurController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new User($this->db);
    }

    public function register($nom, $email, $password, $role) {
       if ($_SERVER('REQUEST_METHOD') === 'POST') {
           $nom = trim($_POST['name']);
           $email = trim($_POST['email']);
           $password = $_POST['password'];
           $role = trim($_POST['role']);
           if (empty($name) || empty($email) || empty($password) || empty($role)) {
               echo "Please fill in all fields.";
               exit;
           }
           $user = new User($this->db);
           $user->register($nom, $email, $password, $role);
       }
       require_once '../views/register.php';
    }

    public function login($email, $password) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            if (empty($email) || empty($password)) {
                echo "Please fill in all fields.";
                exit;
            }else {
                if ($this->user->login($email, $password)) {
                    // $_SESSION['role'] = $this->user->getUserById($this->user->getUserByEmail($email)['id'])['role'];
                    header('Location: index.php?action=home');
                    exit;   

                    }else {
                        $error = "Invalid email or password.";
                    }
            }
        }
    }
}