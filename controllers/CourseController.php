<?php
require_once __DIR__ . '/../models/Course.php';

class CourseController {
    private $db;
    private $course;

    public function __construct($db) {
        $this->db = $db;
        $this->course = new Course($this->db);
    }

    public function getAll() {
        $courses = $this->course->getAll();
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 9;
        $totalCourses = count($courses);
        $totalPages = ceil($totalCourses / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $courses = array_slice($courses, $offset, $perPage);
        $courses = array_map(function ($course) {
            $course['image'] = base64_encode($course['image']);
            return $course;
        }, $courses);
        require_once __DIR__ . '/../views/course.php';
    }

}

