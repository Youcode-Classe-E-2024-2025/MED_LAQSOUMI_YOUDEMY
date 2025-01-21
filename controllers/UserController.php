<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Teacher.php';
require_once __DIR__ . '/../models/Administrator.php';

class UserController extends Controller {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->user->connecter($email, $password)) {
                $this->redirect('/dashboard');
            } else {
                $this->render('login', ['error' => 'Invalid credentials']);
            }
        } else {
            $this->render('login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            if ($this->user->sEnregistrer($nom, $email, $password, $role)) {
                $this->redirect('/login');
            } else {
                $this->render('register', ['error' => 'Registration failed']);
            }
        } else {
            $this->render('register');
        }
    }

    public function logout() {
        $this->user->deconnecter();
        $this->redirect('/login');
    }

    public function dashboard() {
        $this->requireLogin();
        $role = $this->getUserRole();
        $userId = $_SESSION['user_id'];

        switch ($role) {
            case 'etudiant':
                $student = new Student();
                $courses = $student->getMesCours();
                $this->render('student/dashboard', ['courses' => $courses]);
                break;

            case 'enseignant':
                $teacher = new Teacher();
                $courses = $teacher->getCourses();
                $stats = $teacher->getStats();
                $this->render('teacher/dashboard', [
                    'courses' => $courses,
                    'stats' => $stats
                ]);
                break;

            case 'administrateur':
                $admin = new Administrator();
                $stats = $admin->consulterStatistiques();
                $pendingTeachers = $admin->getPendingTeachers();
                $this->render('admin/dashboard', [
                    'stats' => $stats,
                    'pendingTeachers' => $pendingTeachers
                ]);
                break;

            default:
                $this->redirect('/login');
        }
    }

    public function profile() {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $userDetails = $this->user->getUserDetails($userId);
        $this->render('profile', ['user' => $userDetails]);
    }
}
