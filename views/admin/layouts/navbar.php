<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php?action=admin">Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>" href="index.php?action=admin">
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
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
