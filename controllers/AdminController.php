<?php
class AdminController {
    private $adminModel;
    private $db;

    public function __construct($db) {
        require_once 'models/Administrateur.php';
        $this->db = $db;
        $this->adminModel = new Administrateur($db);
    }

    public function adminDashboard() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        $data = [];

        // Get dashboard statistics
        $data['totalUsers'] = $this->adminModel->getTotalUsers();
        $data['totalCourses'] = $this->adminModel->getTotalCourses();
        $data['pendingTeachers'] = $this->adminModel->getPendingTeachersCount();
        $data['totalTags'] = $this->adminModel->getTotalTags();

        // Get recent activities
        $data['recentActivities'] = $this->adminModel->getRecentActivities();

        // Get users list for the users section
        if (isset($_GET['section']) && $_GET['section'] == 'users') {
            $data['users'] = $this->adminModel->getAllUsers();
        }

        // Get courses list for the courses section
        if (isset($_GET['section']) && $_GET['section'] == 'courses') {
            $data['courses'] = $this->adminModel->getAllCourses();
        }

        // Get tags list for the tags section
        if (isset($_GET['section']) && $_GET['section'] == 'tags') {
            $data['tags'] = $this->adminModel->getAllTagsList();
        }

        // Extract data to make variables available in view
        extract($data);

        // Load the view
        require_once 'views/admin_dashboard.php';
    }

    public function validateTeacher() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $teacherId = $_GET['id'];
            if ($this->adminModel->validateTeacher($teacherId)) {
                $_SESSION['message'] = "Teacher account validated successfully.";
            } else {
                $_SESSION['error'] = "Error validating teacher account.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=users');
        exit();
    }

    public function deleteUser() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $userId = $_GET['id'];
            if ($this->adminModel->deleteUser($userId)) {
                $_SESSION['message'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting user.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=users');
        exit();
    }

    public function approveCourse() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $courseId = $_GET['id'];
            if ($this->adminModel->approveCourse($courseId)) {
                $_SESSION['message'] = "Course approved successfully.";
            } else {
                $_SESSION['error'] = "Error approving course.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=courses');
        exit();
    }

    public function deleteCourse() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $courseId = $_GET['id'];
            if ($this->adminModel->deleteCourse($courseId)) {
                $_SESSION['message'] = "Course deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting course.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=courses');
        exit();
    }

    public function addTag() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_POST['tag_name'])) {
            $tagName = trim($_POST['tag_name']);
            if ($this->adminModel->addTag($tagName)) {
                $_SESSION['message'] = "Tag added successfully.";
            } else {
                $_SESSION['error'] = "Error adding tag.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=tags');
        exit();
    }

    public function deleteTag() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $tagId = $_GET['id'];
            if ($this->adminModel->deleteTag($tagId)) {
                $_SESSION['message'] = "Tag deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting tag.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=tags');
        exit();
    }
}