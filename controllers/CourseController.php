<?php
require_once __DIR__ . '/../models/Course.php';

class CourseController
{
    private $db;
    private $course;

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

        // session_start();
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

    public function getMyCourses() {
        try {
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Ensure user is logged in and is a student
            if (!isset($_SESSION['user_id'])) {
                throw new Exception('User not logged in');
            }
            
            if ($_SESSION['role'] !== 'etudiant') {
                throw new Exception('User is not a student');
            }

            $userId = $_SESSION['user_id'];
            $role = $_SESSION['role'];
            $userName = $_SESSION['name'];

            try {
                $courses = $this->course->getMyCourses($userId);
                
                // Transform image paths if needed
                $courses = array_map(function ($course) {
                    if (isset($course['image'])) {
                        $course['image'] = $course['image'];
                    }
                    return $course;
                }, $courses);

                // Load the view with the courses
                require_once __DIR__ . '/../views/myCourses.php';
            } catch (Exception $e) {
                error_log("Error loading courses: " . $e->getMessage());
                $_SESSION['error'] = "Error loading courses: " . $e->getMessage();
                header("Location: index.php?action=courses");
                exit;
            }
        } catch (Exception $e) {
            error_log("Access error: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php?action=loginPage");
            exit;
        }
    }

    public function inscrireCours() {
        // Start output buffering to catch any unwanted output
        ob_start();
        
        try {
            // Set headers
            header('Content-Type: application/json');
            
            // Debug information
            error_log("=== Starting Course Enrollment ===");
            error_log("Request Method: " . $_SERVER['REQUEST_METHOD']);
            error_log("POST Data: " . print_r($_POST, true));
            
            // Ensure the request is POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Log session state
            error_log("Session State: " . print_r($_SESSION, true));

            // Get parameters from POST data
            $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
            $cours_id = filter_input(INPUT_POST, 'cours_id', FILTER_VALIDATE_INT);

            error_log("Parsed Parameters - User ID: $user_id, Course ID: $cours_id");

            // Validate parameters
            if ($user_id === false || $user_id === null || $cours_id === false || $cours_id === null) {
                error_log("Invalid parameters - user_id: $user_id, cours_id: $cours_id");
                throw new Exception('Invalid parameters provided');
            }

            // Verify session exists
            if (!isset($_SESSION['user_id'])) {
                error_log("No user_id in session");
                throw new Exception('User not logged in');
            }

            // Verify user ID matches session
            if ($_SESSION['user_id'] != $user_id) {
                error_log("User ID mismatch - Session: {$_SESSION['user_id']}, Request: $user_id");
                throw new Exception('Invalid user ID');
            }

            // Verify user is a student
            if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'etudiant') {
                error_log("Invalid role - Expected: etudiant, Got: " . (isset($_SESSION['role']) ? $_SESSION['role'] : 'none'));
                throw new Exception('User is not a student');
            }

            try {
                // Check if already enrolled
                if ($this->course->isEnrolled($user_id, $cours_id)) {
                    error_log("User already enrolled - User ID: $user_id, Course ID: $cours_id");
                    throw new Exception('Already enrolled in this course');
                }

                // Try to enroll
                $result = $this->course->inscrireCours($user_id, $cours_id);
                error_log("Enrollment result: " . ($result ? 'success' : 'failure'));
                
                if ($result) {
                    // Clear any previous output
                    ob_clean();
                    
                    $response = ['status' => 'success', 'message' => 'Successfully enrolled in course'];
                    error_log("Sending success response: " . json_encode($response));
                    echo json_encode($response);
                    exit;
                } else {
                    throw new Exception('Failed to enroll in course');
                }
            } catch (Exception $e) {
                error_log("Error during enrollment: " . $e->getMessage());
                throw new Exception('Failed to enroll: ' . $e->getMessage());
            }

        } catch (Exception $e) {
            error_log("Final error: " . $e->getMessage());
            error_log("Error trace: " . $e->getTraceAsString());
            
            // Clear any previous output
            ob_clean();
            
            http_response_code(400);
            $response = ['status' => 'error', 'message' => $e->getMessage()];
            error_log("Sending error response: " . json_encode($response));
            echo json_encode($response);
            exit;
        }
        
        error_log("=== End Course Enrollment ===");
        ob_end_flush();
    }

    public function addCourse() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $data = [
                'titre' => filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING),
                'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                'contenu' => filter_input(INPUT_POST, 'contenu', FILTER_SANITIZE_STRING),
                'categorie_id' => filter_input(INPUT_POST, 'categorie_id', FILTER_VALIDATE_INT),
                'enseignant_id' => $_SESSION['user_id'],
                'image' => ''
            ];

            // Handle file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/courses/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileInfo = pathinfo($_FILES['image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extension, $allowedExtensions)) {
                    throw new Exception('Invalid file type');
                }

                $fileName = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $data['image'] = 'uploads/courses/' . $fileName;
                }
            }

            // Handle tags
            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                $data['tags'] = array_map('intval', $_POST['tags']);
            }

            $courseModel = new Course($this->db);
            $courseId = $courseModel->addCourse($data);

            echo json_encode(['success' => true, 'course_id' => $courseId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function updateCourse() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $courseId = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            if (!$courseId) {
                throw new Exception('Invalid course ID');
            }

            $data = [
                'titre' => filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING),
                'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                'contenu' => filter_input(INPUT_POST, 'contenu', FILTER_SANITIZE_STRING),
                'categorie_id' => filter_input(INPUT_POST, 'categorie_id', FILTER_VALIDATE_INT),
                'enseignant_id' => $_SESSION['user_id'],
                'image' => filter_input(INPUT_POST, 'current_image', FILTER_SANITIZE_STRING)
            ];

            // Handle file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../uploads/courses/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileInfo = pathinfo($_FILES['image']['name']);
                $extension = strtolower($fileInfo['extension']);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($extension, $allowedExtensions)) {
                    throw new Exception('Invalid file type');
                }

                $fileName = uniqid() . '.' . $extension;
                $targetPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // Delete old image if exists
                    if (!empty($data['image']) && file_exists(__DIR__ . '/../' . $data['image'])) {
                        unlink(__DIR__ . '/../' . $data['image']);
                    }
                    $data['image'] = 'uploads/courses/' . $fileName;
                }
            }

            // Handle tags
            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                $data['tags'] = array_map('intval', $_POST['tags']);
            }

            $courseModel = new Course($this->db);
            $courseModel->updateCourse($courseId, $data);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deleteCourse() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $courseId = filter_input(INPUT_POST, 'course_id', FILTER_VALIDATE_INT);
            if (!$courseId) {
                throw new Exception('Invalid course ID');
            }

            $courseModel = new Course($this->db);
            $courseModel->deleteCourse($courseId, $_SESSION['user_id']);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getEnrollments() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $courseId = filter_input(INPUT_GET, 'course_id', FILTER_VALIDATE_INT);
            if (!$courseId) {
                throw new Exception('Invalid course ID');
            }

            $courseModel = new Course($this->db);
            $enrollments = $courseModel->getEnrollments($courseId, $_SESSION['user_id']);

            echo json_encode(['success' => true, 'enrollments' => $enrollments]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getTeacherStatistics() {
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'enseignant') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }

            $courseModel = new Course($this->db);
            $stats = $courseModel->getTeacherStatistics($_SESSION['user_id']);

            echo json_encode(['success' => true, 'statistics' => $stats]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
