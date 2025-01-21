<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    public function showLoginForm() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $user = User::authenticate($_POST['email'], $_POST['password']);
                if ($user) {
                    $_SESSION['user'] = $user;
                    $_SESSION['success'] = "Welcome back, " . $user['nom'] . "!";
                    
                    // Redirect based on role
                    switch ($user['role']) {
                        case 'admin':
                            header('Location: index.php?action=admin');
                            break;
                        case 'enseignant':
                            if (!$user['validated']) {
                                $_SESSION['error'] = "Your teacher account is pending validation.";
                                session_destroy();
                                header('Location: index.php?action=login');
                            } else {
                                header('Location: index.php?action=enseignant');
                            }
                            break;
                        case 'etudiant':
                            header('Location: index.php?action=etudiant');
                            break;
                    }
                    exit;
                } else {
                    $_SESSION['error'] = "Invalid email or password.";
                    header('Location: index.php?action=login');
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Login error: " . $e->getMessage();
                header('Location: index.php?action=login');
                exit;
            }
        }
        $this->showLoginForm();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate input
                if (empty($_POST['nom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['role'])) {
                    throw new Exception("All fields are required.");
                }

                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Invalid email format.");
                }

                if (strlen($_POST['password']) < 6) {
                    throw new Exception("Password must be at least 6 characters long.");
                }

                // Create user
                User::create([
                    'nom' => $_POST['nom'],
                    'email' => $_POST['email'],
                    'mot_de_passe' => $_POST['password'],
                    'role' => $_POST['role']
                ]);

                $_SESSION['success'] = "Registration successful! Please log in.";
                if ($_POST['role'] === 'enseignant') {
                    $_SESSION['info'] = "Your teacher account will be reviewed by an administrator.";
                }
                header('Location: index.php?action=login');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Registration error: " . $e->getMessage();
            }
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
