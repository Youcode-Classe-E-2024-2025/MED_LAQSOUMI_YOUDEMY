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

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = trim($_POST['role'] ?? '');

            if (empty($nom) || empty($email) || empty($password) || empty($role)) {
                $error = "Please fill in all fields.";
            } else {
                $userId = $this->user->register($nom, $email, $password, $role);
                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['role'] = $role;
                    header('Location: index.php?action=home');
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }

        // Include the view
        require_once '/views/register.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $error = "Both email and password are required.";
            } else {
                if ($this->user->login($email, $password)) {
                    header('Location: index.php?action=dashboard');
                    exit;
                } else {
                    $error = "Invalid email or password.";
                }
            }
        }
        require './views/login.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }

    public function home() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->user->getUserById($userId);

        // Include the view
        require_once '../views/home.php';
    }
}