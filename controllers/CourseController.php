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
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $courses = $this->course->getAll($page, $limit);
        $totalCourses = $this->course->getTotalCourses();
        $totalPages = ceil($totalCourses / $limit);

        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';

        $courses = array_map(function ($course) {
            $course['image'] = $course['image'];
            return $course;
        }, $courses);

        require_once __DIR__ . '/../views/course.php';
    }

    public function handleSearch() {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
    
        $results = $this->course->getCoursesByKeyword($keyword, $page, $limit);
        $totalCourses = $this->course->getTotalCoursesByKeyword($keyword);
        $totalPages = ceil($totalCourses / $limit);
    
        // Start session and get user role and name
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';

        $results = array_map(function ($course) {
            $course['image'] = $course['image'];
            return $course;
        }, $results);

        // Load the view
        require_once __DIR__ . '/../views/course.php';
    }
}

