<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/Etudiant.php';
// 
class UtilisateurController
{
    private $db;
    private $user;
    private $email;
    private $password;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new Utilisateur($this->db);
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $this->password = trim($_POST['password']);

            if (empty($this->email) || empty($this->password)) {
                $error = "Both email and password are required.";
            } else {
                $user = $this->user->connecter($this->email, $this->password);
                if ($user) {
                    session_start();
                    $_SESSION['name'] = $user['nom'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: index.php?action=home');
                    exit;
                } else {
                    $error = "Invalid email or password.";
                }
            }
        }
        require_once 'views/login.php';
    }


    public function logout()
    {
        session_start();
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        header("Location: index.php?action=home");
        exit();
    }

    public function register()
    {
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
                $userId = $this->user->sEnregistrer($nom, $email, $password, $role);
                if ($userId) {
                    $user = $this->user->connecter($email, $password);

                    if ($user) {
                        session_start();
                        $_SESSION['name'] = $user['nom'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: index.php?action=home');
                        exit;
                    } else {
                        $error = "Invalid email or password.";
                    }
                }
            }
        }
        require './views/register.php';
    }

    public function getRole()
    {
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        return $role;
    }
}
