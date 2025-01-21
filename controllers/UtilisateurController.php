<?php
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/Administrateur.php';

class UtilisateurController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = "All fields are required.";
            } else {
                try {
                    // First get the user's role
                    $query = "SELECT role FROM utilisateurs WHERE email = ?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$email]);
                    $role = $stmt->fetchColumn();

                    if ($role) {
                        // Create the appropriate user type based on role
                        switch ($role) {
                            case 'etudiant':
                                $utilisateur = new Etudiant($this->db);
                                break;
                            case 'enseignant':
                                $utilisateur = new Enseignant($this->db);
                                break;
                            case 'administrateur':
                                $utilisateur = new Administrateur($this->db);
                                break;
                            default:
                                throw new Exception('Invalid role');
                        }

                        // Set credentials and attempt login
                        $utilisateur->setEmail($email);
                        $utilisateur->setMotDePasse($password);
                        
                        if ($utilisateur->connecter()) {
                            session_start();
                            $_SESSION['user_id'] = $utilisateur->getId();
                            $_SESSION['role'] = $utilisateur->getRole();
                            $_SESSION['name'] = $utilisateur->getNom();
                            $_SESSION['email'] = $utilisateur->getEmail();
                            
                            header('Location: index.php?action=home');
                            exit;
                        } else {
                            $error = "Invalid email or password.";
                        }
                    } else {
                        $error = "User not found.";
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $error = "An error occurred during login. Please try again.";
                }
            }
        }
        
        require_once __DIR__ . '/../views/login.php';
    }

    public function register() {
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $role = $_POST['role'] ?? '';

            if ($password !== $confirm_password) {
                $error = "Passwords do not match.";
            } else if (empty($nom) || empty($email) || empty($password) || empty($role)) {
                $error = "All fields are required.";
            } else {
                try {
                    $userId = Utilisateur::sEnregistrer($this->db, $nom, $email, $password, $role);
                    if ($userId) {
                        // Create appropriate user type based on role
                        switch ($role) {
                            case 'etudiant':
                                $utilisateur = new Etudiant($this->db);
                                break;
                            case 'enseignant':
                                $utilisateur = new Enseignant($this->db);
                                break;
                            case 'administrateur':
                                $utilisateur = new Administrateur($this->db);
                                break;
                            default:
                                throw new Exception('Invalid role');
                        }
                        
                        // Set credentials and attempt login
                        $utilisateur->setEmail($email);
                        $utilisateur->setMotDePasse($password);
                        
                        if ($utilisateur->connecter()) {
                            session_start();
                            $_SESSION['user_id'] = $utilisateur->getId();
                            $_SESSION['role'] = $utilisateur->getRole();
                            $_SESSION['name'] = $utilisateur->getNom();
                            $_SESSION['email'] = $utilisateur->getEmail();
                            
                            header('Location: index.php?action=home');
                            exit;
                        }
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $error = "Registration failed. Please try again.";
                }
            }
        }
        
        require_once __DIR__ . '/../views/register.php';
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }

    public function getRole()
    {
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        return $role;
    }
}
