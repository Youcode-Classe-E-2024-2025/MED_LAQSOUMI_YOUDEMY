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


    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);

            if (empty($email) || empty($password)) {
                $error = "Both email and password are required.";
            } else {
                $user = $this->user->login($email, $password);
                if ($user) {
                    session_start();
                    $_SESSION['name'] = $user['nom'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: index.php?action=home');
                    exit;
                
                    // switch ($user['role']) {
                    //     case 'etudiant':
                    //         // header('Location: index.php?action=home');
                    //         break;
                    //     case 'teacher':
                    //         header('Location: index.php?action=teacherDashboard');
                    //         break;
                    //     case 'admin':
                    //         header('Location: index.php?action=adminDashboard');
                    //         break;
                    //     default:
                    //         header('Location: index.php?action=home');
                    // }
                    // exit;
                } else {
                    $error = "Invalid email or password.";
                }
            }
        }
        require './views/login.php';
    }

    public function logout() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['name']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);
            $role = trim($_POST['role']);

            if ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            }

            if (empty($nom) || empty($email) || empty($password) || empty($role) || empty($confirm_password)) {
                $error = "All fields are required.";
            } else {
                $userId = $this->user->register($nom, $email, $password, $role);
                if ($userId) {
                    session_start();
                    $_SESSION['name'] = $nom;
                    $_SESSION['role'] = $role;
                    $_SESSION['user_id'] = $userId;
                    header('Location: index.php?action=home');
                    exit;
                } else {
                    $error = "Registration failed. Please try again.";
                }
            }
        }
        require './views/register.php';
    }
}

