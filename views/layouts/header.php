<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouDemy - Online Learning Platform</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 for better select inputs -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">YouDemy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin&page=users">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin&page=categories">Categories</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin&page=tags">Tags</a>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['user']['role'] === 'enseignant'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=enseignant">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=enseignant&page=courses">My Courses</a>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['user']['role'] === 'etudiant'): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=etudiant">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=etudiant&page=courses">Browse Courses</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=etudiant&page=enrolled">My Courses</a>
                            </li>
                        </ul>
                    <?php endif; ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="index.php?action=logout">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=register">Register</a>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <main class="py-4">
