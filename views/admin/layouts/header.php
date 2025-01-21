<?php 
$hideMainNav = true;
require_once __DIR__ . '/../../layouts/header.php'; 
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php?action=admin">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= !isset($page) || $page === 'dashboard' ? 'active' : '' ?>" href="index.php?action=admin">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'users' ? 'active' : '' ?>" href="index.php?action=admin&page=users">
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'categories' ? 'active' : '' ?>" href="index.php?action=admin&page=categories">
                        <i class="fas fa-folder"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'courses' ? 'active' : '' ?>" href="index.php?action=admin&page=courses">
                        <i class="fas fa-book"></i> Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'statistics' ? 'active' : '' ?>" href="index.php?action=admin&page=statistics">
                        <i class="fas fa-chart-bar"></i> Statistics
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="fas fa-home"></i> Back to Site
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['user']['nom']) ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="index.php?action=admin&page=profile">
                                <i class="fas fa-user-cog"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="index.php?action=logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
