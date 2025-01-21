<?php
require_once __DIR__ . '/../models/Etudiant.php';
require_once __DIR__ . '/../models/Course.php';

class EtudiantController {
    private $db;
    private $etudiant;
    private $course;

    public function __construct($db) {
        $this->db = $db;
        $this->etudiant = new Etudiant($this->db);
        $this->course = new Course($this->db);
    }

    public function consulterCours() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $courses = $this->course->getAll($page, $limit);
        $totalCourses = $this->course->getTotalCourses();
        $totalPages = ceil($totalCourses / $limit);

        require_once __DIR__ . '/../views/catalogue.php';
    }

    public function sInscrireCours() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $coursId = isset($_POST['cours_id']) ? (int)$_POST['cours_id'] : null;
        if (!$coursId) {
            $_SESSION['error'] = "ID du cours non spécifié";
            header('Location: index.php?action=courses');
            exit;
        }

        $result = $this->etudiant->sInscrireCours($coursId);
        if ($result) {
            $_SESSION['success'] = "Inscription au cours réussie";
        } else {
            $_SESSION['error'] = "Erreur lors de l'inscription au cours";
        }
        header('Location: index.php?action=courses');
        exit;
    }

    public function getMesCours() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'etudiant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $courses = $this->etudiant->consulterCours();
        $totalCourses = count($courses);
        $totalPages = ceil($totalCourses / $limit);
        
        // Apply pagination to courses array
        $offset = ($page - 1) * $limit;
        $courses = array_slice($courses, $offset, $limit);

        require_once __DIR__ . '/../views/myCourses.php';
    }
}