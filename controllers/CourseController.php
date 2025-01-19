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
        $limit = 10;
        $courses = $this->course->getAll($page, $limit);
        $totalCourses = $this->course->getTotalCourses();
        $totalPages = ceil($totalCourses / $limit);

        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';

        $courses = array_map(function ($course) {
            $course['image'] = base64_encode($course['image']);
            return $course;
        }, $courses);

        require_once __DIR__ . '/../views/course.php';
    }

}

