<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../models/Enrollment.php';
require_once __DIR__ . '/../models/Statistics.php';

class EtudiantController {
    public function index() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $stats = Statistics::getStudentStats($_SESSION['user']['id']);
        require_once __DIR__ . '/../views/etudiant/dashboard.php';
    }

    public function parcourirCours() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $filters = [
            'search' => $_GET['search'] ?? null,
            'category' => $_GET['category'] ?? null,
            'sort' => $_GET['sort'] ?? 'recent'
        ];

        $page = isset($_GET['p']) ? max(1, intval($_GET['p'])) : 1;
        $perPage = 12;

        $cours = Course::search($filters, $page, $perPage);
        $categories = Category::findAll();

        // Build query string for pagination
        $queryParams = array_filter($filters);
        $query_string = !empty($queryParams) ? '&' . http_build_query($queryParams) : '';

        // Add enrolled status for each course
        foreach ($cours as &$course) {
            $course['is_enrolled'] = Enrollment::isEnrolled($_SESSION['user']['id'], $course['id']);
        }

        require_once __DIR__ . '/../views/etudiant/courses/index.php';
    }

    public function voirCours() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['id'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=etudiant&page=courses');
            exit;
        }

        $course = Course::getWithDetails($courseId);
        if (!$course) {
            $_SESSION['error'] = "Course not found.";
            header('Location: index.php?action=etudiant&page=courses');
            exit;
        }

        $isEnrolled = Enrollment::isEnrolled($_SESSION['user']['id'], $courseId);
        require_once __DIR__ . '/../views/etudiant/courses/view.php';
    }

    public function sInscrire() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['course'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=etudiant&page=courses');
            exit;
        }

        try {
            if (!Enrollment::isEnrolled($_SESSION['user']['id'], $courseId)) {
                Enrollment::create([
                    'etudiant_id' => $_SESSION['user']['id'],
                    'cours_id' => $courseId
                ]);
                $_SESSION['success'] = "Successfully enrolled in the course.";
            } else {
                $_SESSION['error'] = "You are already enrolled in this course.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error enrolling in course: " . $e->getMessage();
        }

        header('Location: index.php?action=etudiant&page=course&id=' . $courseId);
    }

    public function mesCoursInscrits() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $enrollments = Enrollment::getStudentEnrollments($_SESSION['user']['id']);
        require_once __DIR__ . '/../views/etudiant/courses/enrolled.php';
    }

    public function marquerTermine() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = $_GET['course'] ?? null;
        if (!$courseId) {
            header('Location: index.php?action=etudiant&page=enrolled');
            exit;
        }

        try {
            if (Enrollment::isEnrolled($_SESSION['user']['id'], $courseId)) {
                Enrollment::markCompleted($_SESSION['user']['id'], $courseId);
                $_SESSION['success'] = "Course marked as completed.";
            } else {
                $_SESSION['error'] = "You are not enrolled in this course.";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error marking course as completed: " . $e->getMessage();
        }

        header('Location: index.php?action=etudiant&page=enrolled');
    }

    private function isStudent() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant';
    }
}