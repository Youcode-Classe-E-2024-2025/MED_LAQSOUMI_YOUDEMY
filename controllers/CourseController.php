<?php
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Inscription.php';

class CourseController
{
    private $db;
    private $course;
    private $inscription;

    public function __construct($db)
    {
        $this->db = $db;
        $this->course = new Course($this->db);
    }

    public function getAll()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $courses = $this->course->getAll($page, $limit);
        $totalCourses = $this->course->getTotalCourses();
        $totalPages = ceil($totalCourses / $limit);

        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        $courses = array_map(function ($course) {
            $course['image'] = $course['image'];
            return $course;
        }, $courses);

        require_once __DIR__ . '/../views/course.php';
    }

    public function handleSearch()
    {
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

    public function getCourseById($id)
    {
        $course = $this->course->getCourseById($id);
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $courses = $this->course->getCourseById($id);
        $courses = array_map(function ($course) {
            $course['image'] = $course['image'];
            return $course;
        }, $courses);
        require_once __DIR__ . '/../views/myCourses.php';
    }

    public function getMyCourses()
    {
        session_start();
        $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        $userName = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $courses = $this->course->getMyCourses($userId);
        $courses = array_map(function ($course) {
            $course['titre'] = $course['titre'];
            $course['image'] = $course['image'];
            return $course;
        }, $courses);
        require_once __DIR__ . '/../views/myCourses.php';
    }

    public function inscrireCours($user_id, $cours_id) {
        $this->course->inscrireCours($user_id, $cours_id);
        header("Location: index.php?action=myCourses");
        exit;
    }
}
