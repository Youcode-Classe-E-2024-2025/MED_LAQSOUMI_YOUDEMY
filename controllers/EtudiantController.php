<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/User.php';

class EtudiantController {
    public function index() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $studentId = $_SESSION['user']['id'];
        $myCourses = Course::getEnrolledCourses($studentId);
        require_once __DIR__ . '/../views/student/dashboard.php';
    }

    public function courses() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;

        $coursesData = Course::getPublishedCourses($page, 9, $search, $categoryId);
        $categories = Category::getAll();

        require_once __DIR__ . '/../views/student/courses/index.php';
    }

    public function viewCourse($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            header('Location: index.php?action=login');
            exit;
        }

        $studentId = $_SESSION['user']['id'];
        $course = Course::getCourseWithDetails($id);
        $isEnrolled = Course::isStudentEnrolled($id, $studentId);
        
        if ($isEnrolled) {
            // Get enrollment details including completion status
            $enrollment = Course::getEnrollmentDetails($id, $studentId);
            $course['completed'] = $enrollment['completed'];
        } else {
            $course['completed'] = false;
        }

        require_once __DIR__ . '/../views/student/courses/view-course.php';
    }

    public function enroll() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $courseId = isset($_GET['course']) ? (int)$_GET['course'] : 0;
        $studentId = $_SESSION['user']['id'];

        try {
            if (!$courseId) {
                throw new Exception("Invalid course selected.");
            }

            // Check if course exists and is published
            $course = Course::getWithDetails($courseId);
            if (!$course || !$course['published']) {
                throw new Exception("Course not found or not available.");
            }

            // Check if already enrolled
            if (Course::isStudentEnrolled($courseId, $studentId)) {
                throw new Exception("You are already enrolled in this course.");
            }

            // Enroll student
            if (Course::enrollStudent($courseId, $studentId)) {
                $_SESSION['success'] = "Successfully enrolled in the course!";
                header('Location: index.php?action=etudiant&page=course&id=' . $courseId);
                exit;
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=etudiant&page=courses');
            exit;
        }
    }

    public function myCourses() {
        if (!$this->isStudent()) {
            header('Location: index.php?action=login');
            exit;
        }

        $studentId = $_SESSION['user']['id'];
        $enrolledCourses = Course::getEnrolledCourses($studentId);
        require_once __DIR__ . '/../views/student/courses/my-courses.php';
    }

    private function isStudent() {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant';
    }
}