<?php
require_once __DIR__ . '/../models/Administrateur.php';
require_once __DIR__ . '/../models/Enseignant.php';
require_once __DIR__ . '/../models/Course.php';
// require_once __DIR__ . '/../models/Tag.php';

class AdminController {
    private $db;
    private $admin;

    public function __construct($db) {
        $this->db = $db;
        $this->admin = new Administrateur($this->db);
    }

    public function validerCompteEnseignant() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $enseignantId = isset($_POST['enseignant_id']) ? (int)$_POST['enseignant_id'] : null;
        
        if ($enseignantId) {
            $result = $this->admin->validerCompteEnseignant($enseignantId);
            if ($result) {
                $_SESSION['success'] = "Compte enseignant validé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la validation du compte enseignant";
            }
        }
        
        header('Location: index.php?action=adminDashboard');
        exit;
    }

    public function gererUtilisateurs() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $action = $_POST['action'] ?? '';
        $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;

        if ($action && $userId) {
            switch ($action) {
                case 'activer':
                case 'desactiver':
                case 'supprimer':
                    $result = $this->admin->gererUtilisateurs($userId, $action);
                    if ($result) {
                        $_SESSION['success'] = "Action effectuée avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'exécution de l'action";
                    }
                    break;
            }
        }

        $users = $this->admin->getAllUsers();
        require_once __DIR__ . '/../views/admin_users.php';
    }

    public function gererContenus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $action = $_POST['action'] ?? '';
        $coursId = isset($_POST['cours_id']) ? (int)$_POST['cours_id'] : null;

        if ($action && $coursId) {
            switch ($action) {
                case 'approuver':
                case 'rejeter':
                case 'supprimer':
                    $result = $this->admin->gererContenus($coursId, $action);
                    if ($result) {
                        $_SESSION['success'] = "Action effectuée avec succès";
                    } else {
                        $_SESSION['error'] = "Erreur lors de l'exécution de l'action";
                    }
                    break;
            }
        }

        $courses = $this->admin->getAllCourses();
        require_once __DIR__ . '/../views/admin_courses.php';
    }

    public function insererTags() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tags = isset($_POST['tags']) ? explode(',', $_POST['tags']) : [];
            if (!empty($tags)) {
                $result = $this->admin->insererTags($tags);
                if ($result) {
                    $_SESSION['success'] = "Tags ajoutés avec succès";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout des tags";
                }
            }
        }

        // $existingTags = (new Tag($this->db))->getAllTags();
        require_once __DIR__ . '/../views/admin_tags.php';
    }

    public function consulterStatistiques() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=loginPage');
            exit;
        }

        $stats = $this->admin->consulterStatistiques();
        require_once __DIR__ . '/../views/admin_stats.php';
    }
}