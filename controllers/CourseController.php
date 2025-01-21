<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';

class CourseController {
    public function getAll() {
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 12;

        $filters = [
            'search' => $_GET['search'] ?? null,
            'category' => $_GET['category'] ?? null,
            'sort' => $_GET['sort'] ?? 'recent'
        ];

        try {
            $courses = Course::search($filters, $page, $perPage);
            
            // Add enrolled status for each course if user is logged in
            if (isset($_SESSION['user'])) {
                foreach ($courses as &$course) {
                    $course['is_enrolled'] = Enrollment::isEnrolled($_SESSION['user']['id'], $course['id']);
                }
            }

            require_once __DIR__ . '/../views/etudiant/courses/index.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error loading courses: " . $e->getMessage();
            header('Location: index.php?action=etudiant');
            exit;
        }
    }

    public function view($id) {
        try {
            $course = Course::getWithDetails($id);
            if (!$course) {
                throw new Exception("Course not found");
            }

            $isEnrolled = false;
            if (isset($_SESSION['user'])) {
                $isEnrolled = Enrollment::isEnrolled($_SESSION['user']['id'], $id);
            }

            require_once __DIR__ . '/../views/etudiant/courses/view.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error loading course: " . $e->getMessage();
            header('Location: index.php?action=etudiant&page=courses');
            exit;
        }
    }

    public function enroll($courseId) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            $_SESSION['error'] = "You must be logged in as a student to enroll in courses.";
            header('Location: index.php?action=login');
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
        exit;
    }

    public function myEnrollments() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            $_SESSION['error'] = "You must be logged in as a student to view your courses.";
            header('Location: index.php?action=login');
            exit;
        }

        try {
            $enrollments = Enrollment::getStudentEnrollments($_SESSION['user']['id']);
            require_once __DIR__ . '/../views/etudiant/courses/enrolled.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error loading your courses: " . $e->getMessage();
            header('Location: index.php?action=etudiant');
            exit;
        }
    }

    public function markCompleted($courseId) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
            $_SESSION['error'] = "You must be logged in as a student to mark courses as completed.";
            header('Location: index.php?action=login');
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
        exit;
    }
}
