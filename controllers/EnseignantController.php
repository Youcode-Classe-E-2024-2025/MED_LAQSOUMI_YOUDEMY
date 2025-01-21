<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Statistics.php';

class EnseignantController {
    public function index() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $stats = Statistics::getTeacherStats($_SESSION['user']['id']);
        require_once __DIR__ . '/../views/enseignant/dashboard.php';
    }

    public function gererCours() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $cours = Course::getByTeacher($_SESSION['user']['id']);
        require_once __DIR__ . '/../views/enseignant/courses/index.php';
    }

    public function ajouterCours() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $courseData = [
                    'titre' => $_POST['titre'],
                    'description' => $_POST['description'],
                    'contenu' => $_POST['contenu'],
                    'categorie_id' => $_POST['categorie_id'],
                    'enseignant_id' => $_SESSION['user']['id'],
                    'tags' => $_POST['tags'] ?? []
                ];

                Course::create($courseData);
                $_SESSION['success'] = "Course created successfully.";
                header('Location: index.php?action=enseignant&page=courses');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Error creating course: " . $e->getMessage();
            }
        }

        $categories = Category::findAll();
        $tags = Tag::findAll();
        require_once __DIR__ . '/../views/enseignant/courses/form.php';
    }

    public function modifierCours() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['id'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=enseignant&page=courses');
            exit;
        }

        $course = Course::getWithDetails($courseId);
        if (!$course || $course['enseignant_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Course not found or access denied.";
            header('Location: index.php?action=enseignant&page=courses');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $courseData = [
                    'titre' => $_POST['titre'],
                    'description' => $_POST['description'],
                    'contenu' => $_POST['contenu'],
                    'categorie_id' => $_POST['categorie_id'],
                    'tags' => $_POST['tags'] ?? []
                ];

                Course::update($courseId, $courseData);
                $_SESSION['success'] = "Course updated successfully.";
                header('Location: index.php?action=enseignant&page=courses');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Error updating course: " . $e->getMessage();
            }
        }

        $categories = Category::findAll();
        $tags = Tag::findAll();
        $selectedTags = array_column($course['tags'], 'id');
        require_once __DIR__ . '/../views/enseignant/courses/form.php';
    }

    public function supprimerCours() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['id'] ?? null;
        if ($courseId) {
            try {
                $course = Course::findById($courseId);
                if ($course && $course['enseignant_id'] == $_SESSION['user']['id']) {
                    Course::delete($courseId);
                    $_SESSION['success'] = "Course deleted successfully.";
                } else {
                    $_SESSION['error'] = "Course not found or access denied.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error deleting course: " . $e->getMessage();
            }
        }
        header('Location: index.php?action=enseignant&page=courses');
    }

    public function voirInscriptions() {
        if (!$this->isTeacher()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['course'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=enseignant&page=courses');
            exit;
        }

        $course = Course::findById($courseId);
        if (!$course || $course['enseignant_id'] != $_SESSION['user']['id']) {
            $_SESSION['error'] = "Course not found or access denied.";
            header('Location: index.php?action=enseignant&page=courses');
            exit;
        }

        $inscriptions = Enrollment::getCourseEnrollments($courseId);
        require_once __DIR__ . '/../views/enseignant/courses/enrollments.php';
    }

    private function isTeacher() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'enseignant' && $_SESSION['user']['validated'];
    }
}
