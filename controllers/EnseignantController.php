<?php
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';

class EnseignantController {
    private $db;
    private $enseignant;
    private $course;

    public function __construct($db) {
        $this->db = $db;
        $this->enseignant = new Enseignant($db);
        $this->course = new Course($db);
    }

    public function teacherDashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $teacherId = $_SESSION['user_id'];
        
        // Debug information
        try {
            $courses = $this->course->getTeacherCourses($teacherId);
            $totalCourses = count($courses);
            
            if (empty($courses)) {
                error_log("No courses found for teacher ID: " . $teacherId);
            } else {
                error_log("Found " . count($courses) . " courses for teacher ID: " . $teacherId);
            }
            
            $totalStudents = $this->enseignant->getTotalStudents($teacherId);
            error_log("Total students for teacher ID " . $teacherId . ": " . $totalStudents);
            
            // Debug course data
            foreach ($courses as $course) {
                error_log("Course ID: " . $course['id'] . ", Title: " . $course['titre']);
            }
            
            require_once __DIR__ . '/../views/teacher_dashboard.php';
            
        } catch (Exception $e) {
            error_log("Error in teacherDashboard: " . $e->getMessage());
            $_SESSION['error'] = "An error occurred while loading the dashboard";
            header('Location: index.php');
            exit;
        }
    }

    public function ajouterCours() {
        // Debug information
        if (!isset($_SESSION)) {
            die("Session not started");
        }
        
        if (!isset($_SESSION['user_id'])) {
            die("Not logged in");
        }
        
        if (!isset($_SESSION['role'])) {
            die("No role set");
        }
        
        if ($_SESSION['role'] !== 'enseignant') {
            die("Not a teacher. Current role: " . $_SESSION['role']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $image = $_POST['image'] ?? 'https://placehold.co/300';
            $categorieId = $_POST['categorie_id'] ?? null;

            if (empty($titre) || empty($description) || empty($contenu) || empty($categorieId)) {
                $_SESSION['error'] = "Tous les champs sont obligatoires";
                header('Location: index.php?action=ajouterCours');
                exit;
            }

            $result = $this->course->addCourse([
                'titre' => $titre,
                'description' => $description,
                'contenu' => $contenu,
                'image' => $image,
                'categorie_id' => $categorieId,
                'enseignant_id' => $_SESSION['user_id']
            ]);

            if ($result) {
                $_SESSION['success'] = "Cours ajouté avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du cours";
            }
            header('Location: index.php?action=teacherDashboard');
            exit;
        }

        try {
            $categories = (new Category($this->db))->getAllCategories();
            if (empty($categories)) {
                die("No categories found in database");
            }
        } catch (Exception $e) {
            die("Error loading categories: " . $e->getMessage());
        }

        require_once __DIR__ . '/../views/add_course.php';
    }

    public function modifierCours() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $coursId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $categorieId = $_POST['categorie_id'] ?? null;

            $result = $this->course->updateCourse($coursId, [
                'titre' => $titre,
                'description' => $description,
                'contenu' => $contenu,
                'categorie_id' => $categorieId
            ]);

            if ($result) {
                $_SESSION['success'] = "Cours modifié avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du cours";
            }
            header('Location: index.php?action=teacherDashboard');
            exit;
        }

        $course = $this->course->getCourseById($coursId);
        $categories = (new Category($this->db))->getAllCategories();
        require_once __DIR__ . '/../views/edit_course.php';
    }

    public function supprimerCours() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $coursId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if ($coursId) {
            $result = $this->course->deleteCourse($coursId, $_SESSION['user_id']);
            if ($result) {
                $_SESSION['success'] = "Cours supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du cours";
            }
        }
        
        header('Location: index.php?action=teacherDashboard');
        exit;
    }

    public function consulterInscriptions() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $coursId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $inscriptions = $this->enseignant->getTeacherEnrollments($_SESSION['user_id']);
        $course = $coursId ? $this->course->getCourseById($coursId) : null;
        
        require_once __DIR__ . '/../views/course_enrollments.php';
    }
}