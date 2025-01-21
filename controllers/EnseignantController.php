<?php
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/Course.php';
// require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Category.php';

class EnseignantController {
    private $db;
    private $enseignant;
    private $course;

    public function __construct($db) {
        $this->db = $db;
        $this->enseignant = new Enseignant($this->db);
        $this->course = new Course($this->db);
    }

    public function ajouterCours() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'] ?? '';
            $description = $_POST['description'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $categorieId = $_POST['categorie_id'] ?? null;
            $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];

            $result = $this->enseignant->ajouterCours($titre, $description, $contenu, $tags, $categorieId);
            
            if ($result) {
                $_SESSION['success'] = "Cours ajouté avec succès";
                header('Location: index.php?action=teacherDashboard');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout du cours";
            }
        }

        // Get categories and tags for the form
        $categories = (new Category($this->db))->getCategories();
        // $tags = (new Tag($this->db))->getAllTags();
        
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
            $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];

            $result = $this->enseignant->modifierCours($coursId, $titre, $description, $contenu, $tags, $categorieId);
            
            if ($result) {
                $_SESSION['success'] = "Cours modifié avec succès";
                header('Location: index.php?action=teacherDashboard');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la modification du cours";
            }
        }

        $course = $this->course->getCourseById($coursId);
        $categories = (new Category($this->db))->getCategories();
        // $tags = (new Tag($this->db))->getAllTags();
        
        require_once __DIR__ . '/../views/edit_course.php';
    }

    public function supprimerCours() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $coursId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if ($coursId) {
            $result = $this->enseignant->supprimerCours($coursId);
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
        
        if ($coursId) {
            $inscriptions = $this->enseignant->consulterInscriptions($coursId);
            $course = $this->course->getCourseById($coursId);
            
            require_once __DIR__ . '/../views/course_inscriptions.php';
        } else {
            header('Location: index.php?action=teacherDashboard');
            exit;
        }
    }
}