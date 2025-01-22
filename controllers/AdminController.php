<?php
class AdminController {
    private $administrateur;
    private $db;

    public function __construct($db) {
        require_once 'models/Administrateur.php';
        $this->db = $db;
        $this->administrateur = new Administrateur($db);
    }

    public function adminDashboard() {
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (!isset($_GET['section'])) {
            $data = $this->getDashboardData();
            extract($data);
            require_once 'views/admin_dashboard.php';
        } elseif ($_GET['section'] == 'users') {
            $data = [];

            // Get users list for the users section
            $data['users'] = $this->administrateur->getAllUsers();

            // Extract data to make variables available in view
            extract($data);

            // Load the view
            require_once 'views/admin_dashboard.php';
        } elseif ($_GET['section'] == 'courses') {
            $data = [];

            // Get courses list for the courses section
            $data['courses'] = $this->administrateur->getAllCourses();

            // Extract data to make variables available in view
            extract($data);

            // Load the view
            require_once 'views/admin_dashboard.php';
        } elseif ($_GET['section'] == 'tags') {
            $data = [];

            // Get tags list for the tags section
            $data['tags'] = $this->administrateur->getAllTagsList();

            // Extract data to make variables available in view
            extract($data);

            // Load the view
            require_once 'views/admin_dashboard.php';
        }
    }

    public function validateTeacher() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'administrateur') {
            header('Location: index.php?action=login');
            exit();
        }

        if (isset($_GET['id'])) {
            $teacherId = $_GET['id'];
            if ($this->administrateur->validateTeacher($teacherId)) {
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
            if ($this->administrateur->deleteUser($userId)) {
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
            if ($this->administrateur->approveCourse($courseId)) {
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
            if ($this->administrateur->deleteCourse($courseId)) {
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
            if ($this->administrateur->addTag($tagName)) {
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
            if ($this->administrateur->deleteTag($tagId)) {
                $_SESSION['message'] = "Tag deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting tag.";
            }
        }

        header('Location: index.php?action=adminDashboard&section=tags');
        exit();
    }

    private function getDashboardData() {
        // Get existing counts
        $totalUsers = $this->administrateur->getTotalUsers();
        $totalCourses = $this->administrateur->getTotalCourses();
        $pendingTeachers = $this->administrateur->getPendingTeachersCount();
        $totalTags = $this->administrateur->getTotalTags();
        
        // Get user growth data (last 6 months)
        $userGrowthData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $userGrowthData[$date] = $this->administrateur->getUserCountByMonth($date);
        }

        // Get course distribution by category
        $courseDistributionData = $this->administrateur->getCourseCountByCategory();

        // Get user counts by role
        $studentCount = $this->administrateur->getUserCountByRole('etudiant');
        $teacherCount = $this->administrateur->getUserCountByRole('enseignant');
        $adminCount = $this->administrateur->getUserCountByRole('administrateur');

        // Get recent activities
        $recentActivities = $this->administrateur->getRecentActivities();

        return [
            'totalUsers' => $totalUsers,
            'totalCourses' => $totalCourses,
            'pendingTeachers' => $pendingTeachers,
            'totalTags' => $totalTags,
            'userGrowthData' => $userGrowthData,
            'courseDistributionData' => $courseDistributionData,
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'adminCount' => $adminCount,
            'recentActivities' => $recentActivities
        ];
    }
}